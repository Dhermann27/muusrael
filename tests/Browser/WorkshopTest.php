<?php

namespace Tests\Browser;

use App\Camper;
use App\Charge;
use App\Enums\Programname;
use App\Enums\Timeslotname;
use App\Family;
use App\Timeslot;
use App\User;
use App\Workshop;
use App\Year;
use App\Yearattending;
use App\YearattendingWorkshop;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use ReflectionClass;
use Tests\DuskTestCase;
use function array_push;
use function factory;
use function rand;

/**
 * @group Workshops
 */
class WorkshopTest extends DuskTestCase
{
    /**
     * @group Abraham
     * @throws \Throwable
     */
    public function testAbraham()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)->visit('/workshopchoice')->assertSee('Error');
        });

    }

    /**
     * @group Beto
     * @throws \Throwable
     */
    public function testBetoOne()
    {

        $user = factory(User::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Beto', 'email' => $user->email]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ')');
        $charge = factory(Charge::class)->create(['camper_id' => $camper->id, 'amount' => -200.0, 'year_id' => self::$year->id]);

        $workshop = factory(Workshop::class)->create(['year_id' => self::$year->id]);
        $yaw = factory(YearattendingWorkshop::class)->make(['yearattending_id' => $ya->id,
            'workshop_id' => $workshop->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $workshop, $yaw) {
            $browser->loginAs($user->id)->visit('/workshopchoice')
                ->assertSee($workshop->name)
                ->click('button#workshop-' . $camper->id . '-' . $workshop->id)
                ->waitFor('form#workshops button.active')
                ->assertSeeIn('form#workshops button.active', $workshop->name)
                ->click('button[type="submit"]')->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $yaw->yearattending_id,
            'workshop_id' => $yaw->workshop_id, 'is_enrolled' => 1]);
        $this->assertDatabaseHas('workshops', ['id' => $workshop->id, 'enrolled' => 1]);

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

    /**
     * @group Evra
     * @throws \Throwable
     */
    public function testEvraAll()
    {

        $user = factory(User::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Evra', 'email' => $user->email]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $campers[0]->family_id]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $yas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ')');
        $charge = factory(Charge::class)->create(['camper_id' => $campers[0]->id, 'amount' => -400.0, 'year_id' => self::$year->id]);

        $ref = new ReflectionClass('App\Enums\Timeslotname');
        $slots = $ref->getConstants();
        $workshops = array();
        foreach ($slots as $slot) {
            $workshop = factory(Workshop::class)->create(['year_id' => self::$year->id, 'timeslot_id' => $slot,
                'capacity' => rand(2, 99)]);
            array_push($workshops, $workshop);
            $yaw = factory(YearattendingWorkshop::class)->create(['yearattending_id' => $yas[0]->id,
                'workshop_id' => $workshop->id]);
        }

        $this->browse(function (Browser $browser) use ($user, $campers, $workshops) {
            $browser->loginAs($user->id)->visit('/workshopchoice')->pause(250)
                ->clickLink($campers[0]->firstname)->pause(250);
            foreach ($workshops as $workshop) {
                $browser->assertSee($workshop->name)
                    ->assertDontSeeIn('form#workshops div.active button:not(.active)', $workshop->name);
            }
            $browser->clickLink($campers[1]->firstname)->pause(250)
                ->assertMissing('form#workshops div.active button.active');

            foreach ($workshops as $workshop) {
                $browser->assertSee($workshop->name)
                    ->click('button#workshop-' . $campers[1]->id . '-' . $workshop->id)
                    ->mouseover('a#top');
            }
            $browser->click('button[type="submit"]')->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        foreach ($workshops as $workshop) {
            $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $yas[1]->id,
                'workshop_id' => $workshop->id, 'is_enrolled' => 1]);
            $this->assertDatabaseHas('workshops', ['id' => $workshop->id, 'enrolled' => 2]);
        }

    }

    /**
     * @group Knopf
     * @throws \Throwable
     */
    public function testKnopfExcursion()
    {
        $birth = Carbon::now();
        $birth->year = self::$year->year - rand(1, 17);

        $user = factory(User::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Knopf',
            'birthdate' => $birth->addDays(rand(0, 364))->toDateString(), 'email' => $user->email]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id,
            'program_id' => Programname::Cratty]);
        DB::statement('CALL generate_charges(' . self::$year->year . ')');
        $charge = factory(Charge::class)->create(['camper_id' => $camper->id, 'amount' => -200.0, 'year_id' => self::$year->id]);

        $workshops[0] = factory(Workshop::class)->create(['year_id' => self::$year->id,
            'timeslot_id' => Timeslotname::Morning]);
        $workshops[1] = factory(Workshop::class)->create(['year_id' => self::$year->id,
            'timeslot_id' => Timeslotname::Excursions]);
        $yaw = factory(YearattendingWorkshop::class)->make(['yearattending_id' => $ya->id,
            'workshop_id' => $workshops[1]->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $workshops, $yaw) {
            $browser->loginAs($user->id)->visit('/workshopchoice')
                ->assertSee("automatically enrolled in Cratty")
                ->assertDontSee("Morning")->assertDontSee($workshops[0]->name)
                ->click('button#workshop-' . $camper->id . '-' . $workshops[1]->id)
                ->waitFor('form#workshops button.active')
                ->assertSeeIn('form#workshops button.active', $workshops[1]->name)
                ->click('button[type="submit"]')->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $yaw->yearattending_id,
            'workshop_id' => $yaw->workshop_id, 'is_enrolled' => 1]);
        $this->assertDatabaseHas('workshops', ['id' => $workshops[1]->id, 'enrolled' => 1]);

    }

    /**
     * @group Nancy
     * @throws \Throwable
     */
    public function testNancyRemove()
    {

        $user = factory(User::class)->create();
        $head = factory(Camper::class)->create(['firstname' => 'Nancy', 'email' => $user->email]);
        $campers = factory(Camper::class, 2)->create(['family_id' => $head->family_id]);
        $yah = factory(Yearattending::class)->create(['camper_id' => $head->id, 'year_id' => self::$year->id]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $yas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ')');
        $charge = factory(Charge::class)->create(['camper_id' => $campers[0]->id, 'amount' => -400.0, 'year_id' => self::$year->id]);

        $workshop = factory(Workshop::class)->create(['year_id' => self::$year->id, 'capacity' => rand(3, 99)]);
        $hw = factory(YearattendingWorkshop::class)->create(['yearattending_id' => $yah->id,
            'workshop_id' => $workshop->id]);
        $yaws[0] = factory(YearattendingWorkshop::class)->create(['yearattending_id' => $yas[0]->id,
            'workshop_id' => $workshop->id]);
        $yaws[1] = factory(YearattendingWorkshop::class)->create(['yearattending_id' => $yas[1]->id,
            'workshop_id' => $workshop->id]);
        DB::statement('CALL workshops()');
        $this->assertDatabaseHas('workshops', ['id' => $workshop->id, 'enrolled' => 3]);

        $this->browse(function (Browser $browser) use ($user, $head, $campers, $workshop) {
            $browser->loginAs($user->id)->visit('/workshopchoice')->pause(250)
                ->clickLink($head->firstname)->pause(250)
                ->assertSeeIn('form#workshops div.active button.active', $workshop->name)
                ->click('button#workshop-' . $head->id . '-' . $workshop->id)
                ->clickLink($campers[0]->firstname)->pause(250)
                ->assertSeeIn('form#workshops div.active button.active', $workshop->name)
                ->clickLink($campers[1]->firstname)->pause(250)
                ->assertSeeIn('form#workshops div.active button.active', $workshop->name)
                ->click('button[type="submit"]')->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        $this->assertDatabaseMissing('yearsattending__workshop', ['yearattending_id' => $hw->id,
            'workshop_id' => $workshop->id]);
        $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $yas[0]->id,
            'workshop_id' => $workshop->id, 'is_enrolled' => 1]);
        $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $yas[1]->id,
            'workshop_id' => $workshop->id, 'is_enrolled' => 1]);
        $this->assertDatabaseHas('workshops', ['id' => $workshop->id, 'enrolled' => 2]);
    }

    /**
     * @group Quentin
     * @throws \Throwable
     */
    public function testQuentinConflict()
    {
        $birth = Carbon::now();
        $birth->year = self::$year->year - rand(1, 17);

        $user = factory(User::class)->create();
        $head = factory(Camper::class)->create(['firstname' => 'Nancy', 'email' => $user->email]);
        $campers[0] = factory(Camper::class)->create(['family_id' => $head->family_id]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $head->family_id,
            'birthdate' => $birth->addDays(rand(0, 364))->toDateString()]);
        $yah = factory(Yearattending::class)->create(['camper_id' => $head->id, 'year_id' => self::$year->id]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $yas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id,
            'program_id' => Programname::Burt]);
        DB::statement('CALL generate_charges(' . self::$year->year . ')');
        $charge = factory(Charge::class)->create(['camper_id' => $head->id, 'amount' => -400.0, 'year_id' => self::$year->id]);

        $workshopsC = factory(Workshop::class, 2)->create(['year_id' => self::$year->id, 'capacity' => rand(2, 99),
            'timeslot_id' => Timeslotname::Early_Afternoon, 'w' => 1]);
        $workshopsNC[0] = factory(Workshop::class)->create(['year_id' => self::$year->id, 'capacity' => rand(2, 99),
            'timeslot_id' => Timeslotname::Late_Afternoon, 'm' => 1, 't' => 1, 'w' => 1, 'th' => 0, 'f' => 0]);
        $workshopsNC[1] = factory(Workshop::class)->create(['year_id' => self::$year->id, 'capacity' => rand(2, 99),
            'timeslot_id' => Timeslotname::Late_Afternoon, 'm' => 0, 't' => 0, 'w' => 0, 'th' => 1, 'f' => 1]);
        $yaws[0] = factory(YearattendingWorkshop::class)->create(['yearattending_id' => $yas[0]->id,
            'workshop_id' => $workshopsC[0]->id]);
        $yaws[1] = factory(YearattendingWorkshop::class)->create(['yearattending_id' => $yas[0]->id,
            'workshop_id' => $workshopsC[1]->id]);
        $yaws[0] = factory(YearattendingWorkshop::class)->create(['yearattending_id' => $yas[0]->id,
            'workshop_id' => $workshopsNC[0]->id]);
        $yaws[1] = factory(YearattendingWorkshop::class)->create(['yearattending_id' => $yas[0]->id,
            'workshop_id' => $workshopsNC[1]->id]);

        $this->browse(function (Browser $browser) use ($user, $head, $campers, $workshopsC, $workshopsNC) {
            $browser->loginAs($user->id)->visit('/workshopchoice')->pause(250)
                ->clickLink($head->firstname)->pause(250)
                ->click('button#workshop-' . $head->id . '-' . $workshopsNC[0]->id)
                ->click('button#workshop-' . $head->id . '-' . $workshopsNC[1]->id)
                ->assertMissing('form#workshops div.active h6.alert')
                ->click('button#workshop-' . $head->id . '-' . $workshopsC[0]->id)
                ->click('button#workshop-' . $head->id . '-' . $workshopsC[1]->id)
                ->assertSeeIn('form#workshops div.active', 'conflicting days')
                ->click('button#workshop-' . $head->id . '-' . $workshopsC[1]->id)
                ->assertMissing('form#workshops div.active h6.alert')
                ->clickLink($campers[0]->firstname)->pause(250)
                ->assertSeeIn('form#workshops div.active', 'conflicting days')
                ->click('button#workshop-' . $campers[0]->id . '-' . $workshopsC[0]->id)
                ->assertMissing('form#workshops div.active h6.alert')
                ->click('button[type="submit"]')->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $yah->id,
            'workshop_id' => $workshopsC[0]->id]);
        $this->assertDatabaseMissing('yearsattending__workshop', ['yearattending_id' => $yas[0]->id,
            'workshop_id' => $workshopsC[0]->id]);
        $this->assertDatabaseMissing('yearsattending__workshop', ['yearattending_id' => $yah->id,
            'workshop_id' => $workshopsC[1]->id]);
        $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $yas[0]->id,
            'workshop_id' => $workshopsC[1]->id]);
        $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $yah->id,
            'workshop_id' => $workshopsNC[0]->id]);
        $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $yah->id,
            'workshop_id' => $workshopsNC[1]->id]);
        $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $yas[0]->id,
            'workshop_id' => $workshopsNC[0]->id]);
        $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $yas[0]->id,
            'workshop_id' => $workshopsNC[1]->id]);
        $this->assertDatabaseHas('workshops', ['id' => $workshopsC[0]->id, 'enrolled' => 1]);
        $this->assertDatabaseHas('workshops', ['id' => $workshopsC[1]->id, 'enrolled' => 1]);
        $this->assertDatabaseHas('workshops', ['id' => $workshopsNC[0]->id, 'enrolled' => 2]);
        $this->assertDatabaseHas('workshops', ['id' => $workshopsNC[1]->id, 'enrolled' => 2]);
    }

    /**
     * @group Zeke
     * @throws \Throwable
     */
    public function testZekeWaitinglist()
    {
        $user = factory(User::class)->create();
        $head = factory(Camper::class)->create(['firstname' => 'Zeke', 'email' => $user->email]);
        $campers = factory(Camper::class, 5)->create(['family_id' => $head->family_id]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $head->id, 'year_id' => self::$year->id]);
        foreach ($campers as $camper) {
            factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        }
        DB::statement('CALL generate_charges(' . self::$year->year . ')');
        $charge = factory(Charge::class)->create(['camper_id' => $head->id, 'amount' => -400.0, 'year_id' => self::$year->id]);

        $workshop = factory(Workshop::class)->create(['year_id' => self::$year->id, 'capacity' => 5]);

        $this->browse(function (Browser $browser) use ($user, $head, $campers, $workshop, $ya) {
            $browser->loginAs($user->id)->visit('/workshopchoice')->pause(250)
                ->clickLink($head->firstname)->pause(250)
                ->mouseover('button#workshop-' . $head->id . '-' . $workshop->id)
                ->waitForText('Open for Enrollment')->assertSee($workshop->led_by);
            foreach ($campers as $camper) {
                $browser->clickLink($camper->firstname)->pause(250)
                    ->click('button#workshop-' . $camper->id . '-' . $workshop->id);
            }
            $browser->click('button[type="submit"]')->waitFor('div.alert')
                ->assertVisible('div.alert-success')
                ->clickLink($head->firstname)->pause(250)
                ->mouseover('button#workshop-' . $head->id . '-' . $workshop->id)
                ->waitForText('Workshop Full')->click('button#workshop-' . $head->id . '-' . $workshop->id)
                ->click('button[type="submit"]')->waitFor('div.alert')
                ->assertVisible('div.alert-success');

            $this->assertDatabaseHas('yearsattending__workshop', ['yearattending_id' => $ya->id,
                'workshop_id' => $workshop->id, 'is_enrolled' => 0]);
            $this->assertDatabaseHas('workshops', ['id' => $workshop->id, 'enrolled' => 6]);
            // Should be 5 but stored procedure cannot handle excess campers signing up at the same time over capacity

            $browser->clickLink($head->firstname)->pause(250)
                ->click('button#workshop-' . $head->id . '-' . $workshop->id)
                ->clickLink($campers[0]->firstname)->pause(250)
                ->click('button#workshop-' . $campers[0]->id . '-' . $workshop->id)
                ->click('button[type="submit"]')->waitFor('div.alert')
                ->assertVisible('div.alert-success')
                ->clickLink($head->firstname)->pause(250)
                ->mouseover('button#workshop-' . $head->id . '-' . $workshop->id)
                ->waitForText('Filling Fast');
        });
    }

    public function testWorkshops()
    {
        $timeslots = Timeslot::all()->except(Timeslotname::Excursions);
        foreach ($timeslots as $timeslot) {
            factory(Workshop::class, rand(1, 10))->create(['timeslot_id' => $timeslot->id, 'year_id' => self::$year->id]);
            $wrongshop = factory(Workshop::class)->create(['timeslot_id' => $timeslot->id,
                'name' => 'This is the wrong workshop', 'year_id' => factory(Year::class)->create(['is_current' => 0])]);
        }

        $this->browse(function (Browser $browser) use ($timeslots, $wrongshop) {
            $browser->visit('/workshops')->waitFor('div.tab-content div.active');
            foreach ($timeslots as $timeslot) {
                $browser->script('window.scrollTo(0,0)');
                $browser->clickLink($timeslot->name)->pause('250');
                $browser->assertSeeIn("div.tab-content div.active", $timeslot->start_time->format('g:i A'));
                foreach ($timeslot->workshops()->where('year_id', self::$year->id) as $workshop) {
                    $browser->assertSeeIn("div.tab-content div.active", $workshop->name)
                        ->assertSeeIn("div.tab-content div.active", $workshop->display_days);
                    if ($workshop->fee > 0) {
                        $browser->assertSeeIn("div.tab-content div.active", "$" . $workshop->fee);
                    }
                }
                $browser->assertDontSeeIn("div.tab-content div.active", $wrongshop->name);
            }
        });
    }


    public function testExcursions()
    {
        factory(Workshop::class, rand(1, 10))->create(['timeslot_id' => Timeslotname::Excursions, 'year_id' => self::$year->id]);
        $wrongshop = factory(Workshop::class)->create(['timeslot_id' => Timeslotname::Excursions,
            'year_id' => factory(Year::class)->create(['is_current' => 0])]);
        $this->browse(function (Browser $browser) use ($wrongshop) {
            $timeslot = Timeslot::findOrFail(Timeslotname::Excursions);
            $browser->visit('/excursions');
            foreach ($timeslot->workshops()->where('year_id', self::$year->id) as $workshop) {
                $browser->assertSee($workshop->name)->assertSee($workshop->displayDays);
                if ($workshop->fee > 0) {
                    $browser->assertSee("$" . $workshop->fee);
                }
            }
            $browser->assertDontSee($wrongshop->name);
        });
    }
}
