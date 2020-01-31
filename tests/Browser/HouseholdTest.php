<?php

namespace Tests\Browser;

use App\Camper;
use App\Charge;
use App\Family;
use App\User;
use App\Yearattending;
use Illuminate\Support\Facades\DB;
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
            $browser->loginAs($user->id)->visit('/household')->assertSee('Error');
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
        $camper = factory(Camper::class)->create(['family_id' => $family->id, 'firstname' => 'Beta', 'email' => $user->email]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ')');
        $charge = factory(Charge::class)->create(['camper_id' => $camper->id, 'amount' => -200.0, 'year_id' => self::$year->id]);

        $changes = factory(Family::class)->make();

        $this->browse(function (Browser $browser) use ($user, $family, $changes) {
            $browser->loginAs($user->id)->visit('/household')
                ->within(new HouseholdForm, function ($browser) use ($family, $changes) {
                    $browser->changeHousehold($family, $changes);
                });
        });

        $this->assertDatabaseHas('families', ['address1' => $changes->address1,
            'address2' => $changes->address2, 'city' => $changes->city, 'province_id' => $changes->province_id,
            'zipcd' => $changes->zipcd, 'country' => $changes->country, 'is_ecomm' => $changes->is_ecomm,
            'is_scholar' => $changes->is_scholar]);

    }

//    /**
//     * @group Charlie
//     * @throws \Throwable
//     */
//    public function testCharlie()
//    {
//
//        $user = factory(\App\User::class)->create();
//        factory(\App\Role_User::class)->create(['user_id' => $user->id, 'role_id' => \App\Role::where('name', 'admin')->first()->id]);
//
//        $family = factory(\App\Family::class)->make(['is_address_current' => rand(0, 1)]);
//
//        $this->browse(function (Browser $browser) use ($user, $family) {
//            $browser->loginAs($user->id)->visit('/household/f/0')
//                ->within(new HouseholdForm, function ($browser) use ($family) {
//                    $browser->select('select#is_address_current', $family->is_address_current)
//                        ->createHousehold($family);
//                });
//        });
//
//        $this->assertDatabaseHas('families', ['name' => $family->name, 'address1' => $family->address1,
//            'address2' => $family->address2, 'city' => $family->city, 'province_id' => $family->province_id,
//            'zipcd' => $family->zipcd, 'country' => $family->country,
//            'is_address_current' => $family->is_address_current, 'is_ecomm' => $family->is_ecomm,
//            'is_scholar' => $family->is_scholar]);
//
//        $family = \App\Family::latest()->first();
//        $changes = factory(\App\Family::class)->make(['is_address_current' => rand(0, 1)]);
//
//        $this->browse(function (Browser $browser) use ($user, $family, $changes) {
//            $browser->loginAs($user->id)->visit('/household/f/' . $family->id)
//                ->within(new HouseholdForm, function ($browser) use ($family, $changes) {
//                    $browser->assertSelected('select#is_address_current', $family->is_address_current)
//                        ->select('select#is_address_current', $changes->is_address_current)
//                        ->changeHousehold($family, $changes);
//                });
//        });
//
//        $this->assertDatabaseHas('families', ['name' => $changes->name, 'address1' => $changes->address1,
//            'address2' => $changes->address2, 'city' => $changes->city, 'province_id' => $changes->province_id,
//            'zipcd' => $changes->zipcd, 'country' => $changes->country,
//            'is_address_current' => $changes->is_address_current, 'is_ecomm' => $changes->is_ecomm,
//            'is_scholar' => $changes->is_scholar]);
//    }
//
//    /**
//     * @group Charlie
//     * @throws \Throwable
//     */
//    public function testCharlieRO()
//    {
//        $user = factory(\App\User::class)->create();
//        factory(\App\Role_User::class)->create(['user_id' => $user->id, 'role_id' => \App\Role::where('name', 'council')->first()->id]);
//
//        $family = factory(\App\Family::class)->create(['is_address_current' => rand(0, 1)]);
//        $this->browse(function (Browser $browser) use ($user, $family) {
//            $browser->loginAs($user->id)->visit('/household/f/' . $family->id)
//                ->within(new HouseholdForm, function ($browser) use ($family) {
//                    $browser->select('select#is_address_current', $family->is_address_current)
//                        ->assertDisabled('select#is_address_current')
//                        ->viewHousehold($family);
//                });
//        });


//    }
}
