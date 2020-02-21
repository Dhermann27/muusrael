<?php

namespace Tests\Browser;

use App\Camper;
use App\Charge;
use App\Enums\Chargetypename;
use App\Enums\Usertype;
use App\Jobs\GenerateCharges;
use App\Program;
use App\Rate;
use App\Room;
use App\User;
use App\Year;
use App\Yearattending;
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
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        GenerateCharges::dispatchNow(self::$year->id);
        factory(Charge::class)->create(['camper_id' => $camper->id, 'amount' => -200.0, 'year_id' => self::$year->id]);

        $room = factory(Room::class)->create(['is_workshop' => 0]);
        $rate = factory(Rate::class)->create(['program_id' => $ya->program_id, 'building_id' => $room->building_id]);

        $this->browse(function (Browser $browser) use ($user, $room) {
            $browser->loginAs($user->id)->visitRoute('roomselection.index')
                ->mouseover('rect#room-' . $room->id)->waitFor('div#mytooltip')
                ->assertSeeIn('div#mytooltip', $room->room_number)
                ->click('rect#room-' . $room->id)->click('button[type="submit"]')
                ->acceptDialog()->waitFor('div.alert')->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'room_id' => $room->id, 'is_setbyadmin' => 0]);
        $this->assertDatabaseMissing('gencharges', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'chargetype_id' => Chargetypename::Deposit, 'charge' => 200.0]);
        $this->assertDatabaseHas('gencharges', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'chargetype_id' => Chargetypename::Fees, 'charge' => $rate->rate * 6]);

        $newroom = factory(Room::class)->create(['is_workshop' => 0]);
        $newrate = factory(Rate::class)->create(['program_id' => $ya->program_id, 'building_id' => $newroom->building_id]);

        $this->browse(function (Browser $browser) use ($user, $room, $newroom) {
            $browser->loginAs($user->id)->visitRoute('roomselection.index')
                ->mouseover('rect#room-' . $room->id)->waitFor('div#mytooltip')
                ->assertSeeIn('div#mytooltip', 'Locked by')
                ->click('rect#room-' . $newroom->id)->click('button[type="submit"]')
                ->acceptDialog()->waitFor('div.alert')->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'room_id' => $newroom->id, 'is_setbyadmin' => 0]);
        $this->assertDatabaseMissing('gencharges', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'chargetype_id' => Chargetypename::Deposit, 'charge' => 200.0]);
        $this->assertDatabaseMissing('gencharges', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'chargetype_id' => Chargetypename::Fees, 'charge' => $rate->rate * 6]);
        $this->assertDatabaseHas('gencharges', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'chargetype_id' => Chargetypename::Fees, 'charge' => $newrate->rate * 6]);

    }

    /**
     * @group Charlie
     * @throws Throwable
     */
    public function testCharlieLocked()
    {

        $user = factory(User::class)->create(['usertype' => Usertype::Admin]);
        factory(Camper::class)->create(['email' => $user->email]);

        $cuser = factory(User::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Charlie', 'email' => $cuser->email]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        GenerateCharges::dispatchNow(self::$year->id);
        factory(Charge::class)->create(['camper_id' => $camper->id, 'amount' => -200.0, 'year_id' => self::$year->id]);

        $room = factory(Room::class)->create(['is_workshop' => 0]);
        $rate = factory(Rate::class)->create(['program_id' => $ya->program_id, 'building_id' => $room->building_id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $room) {
            $browser->loginAs($user->id)->visitRoute('roomselection.index', ['id' => $camper->id])
                ->click('rect#room-' . $room->id)->click('button[type="submit"]')
                ->acceptDialog()->waitFor('div.alert')->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'room_id' => $room->id, 'is_setbyadmin' => 1]);
        $this->assertDatabaseMissing('gencharges', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'chargetype_id' => Chargetypename::Deposit, 'charge' => 200.0]);
        $this->assertDatabaseHas('gencharges', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'chargetype_id' => Chargetypename::Fees, 'charge' => $rate->rate * 6]);

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

    /**
     * @group Deb
     * @throws Throwable
     */
    public function testDebLocked()
    {
        $user = factory(User::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Deb', 'email' => $user->email]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $campers[0]->family_id]);
        $yas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id]);

        $room = factory(Room::class)->create(['is_workshop' => 0]);

        $otherfamily = factory(Camper::class, 2)->create();
        $oyas[0] = factory(Yearattending::class)->create(['camper_id' => $otherfamily[0]->id,
            'year_id' => self::$year->id, 'room_id' => $room->id]);
        $oyas[1] = factory(Yearattending::class)->create(['camper_id' => $otherfamily[1]->id,
            'year_id' => self::$year->id, 'room_id' => $room->id]);
        GenerateCharges::dispatchNow(self::$year->id);

        factory(Charge::class)->create(['camper_id' => $campers[0]->id, 'amount' => -400.0, 'year_id' => self::$year->id]);
        $newroom = factory(Room::class)->create(['is_workshop' => 0]);
        $newrates[0] = factory(Rate::class)->create(['program_id' => $yas[0]->program_id, 'building_id' => $newroom->building_id]);
        $newrates[1] = factory(Rate::class)->create(['program_id' => $yas[1]->program_id, 'building_id' => $newroom->building_id]);

        $this->browse(function (Browser $browser) use ($user, $room) {
            $browser->loginAs($user->id)->visitRoute('roomselection.index')
                ->mouseover('rect#room-' . $room->id)->waitFor('div#mytooltip')
                ->assertSeeIn('div#mytooltip', $room->room_number)
                ->click('rect#room-' . $room->id)->click('button[type="submit"]')
                ->acceptDialog()->assertMissing('div.alert-success');
        });

        $this->assertDatabaseMissing('yearsattending', ['camper_id' => $campers[0]->id, 'year_id' => self::$year->id,
            'room_id' => $room->id, 'is_setbyadmin' => 0]);
        $this->assertDatabaseMissing('yearsattending', ['camper_id' => $campers[1]->id, 'year_id' => self::$year->id,
            'room_id' => $room->id, 'is_setbyadmin' => 0]);

        $this->browse(function (Browser $browser) use ($user, $newroom) {
            $browser->loginAs($user->id)->visitRoute('roomselection.index')
                ->mouseover('rect#room-' . $newroom->id)->waitFor('div#mytooltip')
                ->assertSeeIn('div#mytooltip', $newroom->room_number)
                ->click('rect#room-' . $newroom->id)->click('button[type="submit"]')
                ->acceptDialog()->waitFor('div.alert')->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending', ['camper_id' => $campers[0]->id, 'year_id' => self::$year->id,
            'room_id' => $newroom->id, 'is_setbyadmin' => 0]);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $campers[1]->id, 'year_id' => self::$year->id,
            'room_id' => $newroom->id, 'is_setbyadmin' => 0]);
        $this->assertDatabaseMissing('gencharges', ['camper_id' => $campers[0]->id, 'year_id' => self::$year->id,
            'chargetype_id' => Chargetypename::Deposit, 'charge' => 400.0]);
        $this->assertDatabaseMissing('gencharges', ['camper_id' => $campers[1]->id, 'year_id' => self::$year->id,
            'chargetype_id' => Chargetypename::Deposit, 'charge' => 400.0]);
        $this->assertDatabaseHas('gencharges', ['camper_id' => $campers[0]->id, 'year_id' => self::$year->id,
            'chargetype_id' => Chargetypename::Fees, 'charge' => $newrates[0]->rate * 6]);
        $this->assertDatabaseHas('gencharges', ['camper_id' => $campers[1]->id, 'year_id' => self::$year->id,
            'chargetype_id' => Chargetypename::Fees, 'charge' => $newrates[1]->rate * 6]);

    }

    /**
     * @group Nancy
     * @throws Throwable
     */
    public function testNancyProgramHousing()
    {
        $user = factory(User::class)->create();
        $head = factory(Camper::class)->create(['firstname' => 'Nancy', 'email' => $user->email]);
        $campers = factory(Camper::class, 2)->create(['family_id' => $head->family_id]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $head->id, 'year_id' => self::$year->id]);
        $yas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $yas[2] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id,
            'program_id' => function () {
                return factory(Program::class)->create(['is_program_housing' => 1])->id;
            }]);

        $room = factory(Room::class)->create(['is_workshop' => 0]);

        $this->browse(function (Browser $browser) use ($user, $room) {
            $browser->loginAs($user->id)->visitRoute('roomselection.index')
                ->mouseover('rect#room-' . $room->id)->waitFor('div#mytooltip')
                ->assertSeeIn('div#mytooltip', $room->room_number)
                ->click('rect#room-' . $room->id)->click('button[type="submit"]')
                ->acceptDialog()->assertMissing('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending', ['camper_id' => $head->id, 'year_id' => self::$year->id,
            'room_id' => $room->id, 'is_setbyadmin' => 0]);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $campers[0]->id, 'year_id' => self::$year->id,
            'room_id' => $room->id, 'is_setbyadmin' => 0]);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $campers[1]->id, 'year_id' => self::$year->id,
            'room_id' => null, 'is_setbyadmin' => 0]);
    }

    /**
     * @group Oscar
     * @throws Throwable
     */
    public function testOscarAssign()
    {
        $user = factory(User::class)->create(['usertype' => Usertype::Admin]);

        $cuser = factory(User::class)->create();
        $head = factory(Camper::class)->create(['firstname' => 'Oscar', 'email' => $cuser->email]);
        $campers = factory(Camper::class, 2)->create(['family_id' => $head->family_id]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $head->id, 'year_id' => self::$year->id]);
        $yas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $yas[2] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id]);

        $rooms = factory(Room::class, 6)->create(['is_workshop' => 0]);

        $oyears = factory(Year::class, 3)->create(['is_current' => 0]);
        $oyas[0] = factory(Yearattending::class)->create(['camper_id' => $head->id, 'year_id' => $oyears[0]->id, 'room_id' => $rooms[0]->id]);
        $oyas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => $oyears[1]->id, 'room_id' => $rooms[1]->id]);
        $oyas[2] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => $oyears[2]->id, 'room_id' => $rooms[2]->id]);


        $this->browse(function (Browser $browser) use ($user, $head, $campers, $rooms, $oyears, $oyas) {
            $browser->loginAs($user->id)->visitRoute('roomselection.read', ['id' => $head->id])
                ->assertSee($oyears[0]->year)->assertSee($rooms[0]->room_number)
                ->assertSee($oyears[1]->year)->assertSee($rooms[1]->room_number)
                ->assertSee($oyears[2]->year)->assertSee($rooms[2]->room_number)
                ->select('roomid-' . $head->id, $rooms[3]->id)
                ->select('roomid-' . $campers[0]->id, $rooms[4]->id)
                ->select('roomid-' . $campers[1]->id, $rooms[5]->id)->click('button[type="submit"]')
                ->waitFor('div.alert')->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending', ['camper_id' => $head->id, 'year_id' => self::$year->id,
            'room_id' => $rooms[3]->id, 'is_setbyadmin' => 1]);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $campers[0]->id, 'year_id' => self::$year->id,
            'room_id' => $rooms[4]->id, 'is_setbyadmin' => 1]);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $campers[1]->id, 'year_id' => self::$year->id,
            'room_id' => $rooms[5]->id, 'is_setbyadmin' => 1]);
    }

    // TODO: Add previous year assignment tests
}
