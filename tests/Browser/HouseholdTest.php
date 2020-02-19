<?php

namespace Tests\Browser;

use App\Camper;
use App\Charge;
use App\Enums\Usertype;
use App\Family;
use App\Jobs\GenerateCharges;
use App\User;
use App\Yearattending;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\HouseholdForm;
use Tests\DuskTestCase;
use function factory;

/**
 * @group Household
 */
class HouseholdTest extends DuskTestCase
{

    /**
     * @group Abraham
     * @throws \Throwable
     */
    public function testAbraham()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)->visitRoute('household.index')->assertSee('Error');
        });

    }

    /**
     * @group Beto
     * @throws \Throwable
     */
    public function testBeto()
    {

        $user = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $camper = factory(Camper::class)->create(['family_id' => $family->id, 'firstname' => 'Beto', 'email' => $user->email]);
        factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        GenerateCharges::dispatchNow(self::$year->id);
        factory(Charge::class)->create(['camper_id' => $camper->id, 'amount' => -200.0, 'year_id' => self::$year->id]);

        $changes = factory(Family::class)->make();

        $this->browse(function (Browser $browser) use ($user, $family, $changes) {
            $browser->loginAs($user->id)->visitRoute('household.index')
                ->waitFor('form#household')
                ->within(new HouseholdForm, function ($browser) use ($family, $changes) {
                    $browser->changeHousehold($family, $changes);
                });
        });

        $this->assertDatabaseHas('families', ['address1' => $changes->address1,
            'address2' => $changes->address2, 'city' => $changes->city, 'province_id' => $changes->province_id,
            'zipcd' => $changes->zipcd, 'country' => $changes->country, 'is_ecomm' => $changes->is_ecomm,
            'is_scholar' => $changes->is_scholar]);

    }

    /**
     * @group Charlie
     * @throws \Throwable
     */
    public function testCharlie()
    {
        $user = factory(User::class)->create(['usertype' => Usertype::Admin]);

        $family = factory(Family::class)->make(['is_address_current' => 0]);

        $this->browse(function (Browser $browser) use ($user, $family) {
            $browser->loginAs($user->id)->visitRoute('household.index', ['id' => 0])
                ->waitFor('form#household')
                ->within(new HouseholdForm, function ($browser) use ($family) {
                    $browser->select('select#is_address_current', $family->is_address_current)
                        ->createHousehold($family);
                });
        });

        $this->assertDatabaseHas('families', ['address1' => $family->address1,
            'address2' => $family->address2, 'city' => $family->city, 'province_id' => $family->province_id,
            'zipcd' => $family->zipcd, 'country' => $family->country,
            'is_address_current' => $family->is_address_current, 'is_ecomm' => $family->is_ecomm,
            'is_scholar' => $family->is_scholar]);

        $family = Family::latest()->first();
        $changes = factory(Family::class)->make(['is_address_current' => 1]);

        $this->browse(function (Browser $browser) use ($user, $family, $changes) {
            $browser->loginAs($user->id)->visitRoute('household.index', ['id' => $family->id])
                ->waitFor('form#household')
                ->within(new HouseholdForm, function ($browser) use ($family, $changes) {
                    $browser->assertSelected('select#is_address_current', $family->is_address_current)
                        ->select('select#is_address_current', $changes->is_address_current)
                        ->changeHousehold($family, $changes);
                });
        });

        $this->assertDatabaseHas('families', ['address1' => $changes->address1,
            'address2' => $changes->address2, 'city' => $changes->city, 'province_id' => $changes->province_id,
            'zipcd' => $changes->zipcd, 'country' => $changes->country,
            'is_address_current' => $changes->is_address_current, 'is_ecomm' => $changes->is_ecomm,
            'is_scholar' => $changes->is_scholar]);
    }

    /**
     * @group Charlie
     * @throws \Throwable
     */
    public function testCharlieRO()
    {
        $user = factory(User::class)->create(['usertype' => Usertype::Pc]);

        $family = factory(Family::class)->create();
        $this->browse(function (Browser $browser) use ($user, $family) {
            $browser->loginAs($user->id)->visitRoute('household.index', ['id' => $family->id])
                ->waitFor('form#household')
                ->within(new HouseholdForm, function ($browser) use ($family) {
                    $browser->assertSelected('select#is_address_current', $family->is_address_current)
                        ->assertDisabled('select#is_address_current')
                        ->viewHousehold($family);
                });
        });
    }
}
