<?php

namespace Tests\Browser;

use App\Camper;
use App\Charge;
use App\Enums\Usertype;
use App\Jobs\GenerateCharges;
use App\Room;
use App\User;
use App\Yearattending;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;
use function factory;

/**
 * @group RoomSelection
 */
class RoomSelectionTest extends DuskTestCase
{
    /**
     * @group Abraham
     * @throws Throwable
     */
    public function testAbraham()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)->visitRoute('roomselection.index')
                ->assertSee('Error');
        });
    }

    /**
     * @group Beto
     * @throws Throwable
     */
    public function testBeto()
    {
        $user = factory(User::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Beto', 'email' => $user->email]);
        factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        GenerateCharges::dispatchNow(self::$year->id);
        factory(Charge::class)->create(['camper_id' => $camper->id, 'amount' => -200.0, 'year_id' => self::$year->id]);

        $room = factory(Room::class)->create(['is_workshop' => 0]);

        $this->browse(function (Browser $browser) use ($user, $room) {
            $browser->loginAs($user->id)->visitRoute('roomselection.index')
                ->mouseover('rect#room-' . $room->id)->waitFor('div#mytooltip')
                ->assertSeeIn('div#mytooltip', $room->room_number)
                ->click('rect#room-' . $room->id)->click('button[type="submit"]')
                ->acceptDialog()->waitFor('div.alert')->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'room_id' => $room->id, 'is_setbyadmin' => 0]);

        $newroom = factory(Room::class)->create(['is_workshop' => 0]);

        $this->browse(function (Browser $browser) use ($user, $room, $newroom) {
            $browser->loginAs($user->id)->visitRoute('roomselection.index')
                ->mouseover('rect#room-' . $room->id)->waitFor('div#mytooltip')
                ->assertSeeIn('div#mytooltip', 'Locked by')
                ->click('rect#room-' . $newroom->id)->click('button[type="submit"]')
                ->acceptDialog()->waitFor('div.alert')->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'room_id' => $newroom->id, 'is_setbyadmin' => 0]);

    }

    /**
     * @group Charlie
     * @throws Throwable
     */
    public function testCharlie()
    {

        $user = factory(User::class)->create(['usertype' => Usertype::Admin]);
        factory(Camper::class)->create(['email' => $user->email]);

        $cuser = factory(User::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Charlie', 'email' => $cuser->email]);
        factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        GenerateCharges::dispatchNow(self::$year->id);
        factory(Charge::class)->create(['camper_id' => $camper->id, 'amount' => -200.0, 'year_id' => self::$year->id]);

        $room = factory(Room::class)->create(['is_workshop' => 0]);

        $this->browse(function (Browser $browser) use ($user, $camper, $room) {
            $browser->loginAs($user->id)->visitRoute('roomselection.index', ['id' => $camper->id])
                ->click('rect#room-' . $room->id)->click('button[type="submit"]')
                ->acceptDialog()->waitFor('div.alert')->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'room_id' => $room->id, 'is_setbyadmin' => 1]);

        $this->browse(function (Browser $browser) use ($cuser, $camper, $room) {
            $browser->loginAs($cuser->id)->visitRoute('roomselection.index')
                ->mouseover('rect#room-' . $room->id)
                ->assertSeeIn('div#mytooltip', 'Your current selection')
                ->assertSee('locked by the Registrar');
        });
    }

    /**
     * @group Charlie
     * @throws Throwable
     */
    public function testCharlieRO()
    {
        $user = factory(User::class)->create(['usertype' => Usertype::Pc]);

        $cuser = factory(User::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Charlie', 'email' => $cuser->email]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'room_id' => function () {
                return factory(Room::class)->create(['is_workshop' => 0])->id;
            }
        ]);

        $this->browse(function (Browser $browser) use ($user, $camper, $ya) {
            $browser->loginAs($user->id)->visitRoute('roomselection.index', ['id' => $camper->id])
                ->mouseover('rect#room-' . $ya->room_id)->waitFor('div#mytooltip')
                ->assertSeeIn('div#mytooltip', 'Locked by')
                ->assertSeeIn('div#mytooltip', $camper->firstname . ' ' . $camper->lastname)
                ->assertMissing('button[type="submit"]');
        });


    }
}
