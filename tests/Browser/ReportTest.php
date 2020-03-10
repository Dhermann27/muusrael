<?php

namespace Tests\Browser;

use App\Camper;
use App\Charge;
use App\Enums\Chargetypename;
use App\Enums\Timeslotname;
use App\Enums\Usertype;
use App\User;
use App\Workshop;
use App\Yearattending;
use App\YearattendingWorkshop;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use function array_push;
use function factory;
use function number_format;
use function rand;

/**
 * @group Reports
 */
class ReportTest extends DuskTestCase
{
    public function testCampers()
    {
        $user = factory(User::class)->create(['usertype' => Usertype::Pc]);
        $campers = factory(Camper::class, rand(1, 50))->create();
        foreach ($campers as $camper) {
            factory(Yearattending::class)->create(['year_id' => self::$year->id, 'camper_id' => $camper->id]);
        }
        $this->browse(function (Browser $browser) use ($user, $campers) {
            $browser->loginAs($user)->visitRoute('reports.campers')->waitForText('Show')
                ->clickLink(self::$year->year)->pause(250);
            foreach ($campers as $camper) {
                $browser->assertSee($camper->firstname)->assertSee($camper->lastname)->assertSee($camper->email)
                    ->assertSee(Carbon::parse($camper->birthdate)->diff(self::$year->checkin)->format('%y'));
            }
        });
    }

    public function testDeposits()
    {
        $faker = Factory::create();
        $user = factory(User::class)->create(['usertype' => Usertype::Admin]);
        $charges = array();
        for ($i = 0; $i < rand(1, 50); $i++) {
            array_push($charges, factory(Charge::class)->create(['chargetype_id' => Chargetypename::PayPalPayment,
                'deposited_date' => null, 'year_id' => self::$year->id, 'created_at' => $faker->dateTimeThisMonth,
                'amount' => $faker->randomNumber(4) * -1]));
        }
        $donation = factory(Charge::class)->create(['chargetype_id' => Chargetypename::Donation,
            'year_id' => self::$year->id, 'created_at' => $charges[0]->created_at]);
        $addthree = factory(Charge::class)->create(['chargetype_id' => Chargetypename::PayPalServiceCharge,
            'year_id' => self::$year->id, 'created_at' => $charges[0]->created_at]);
        $this->browse(function (Browser $browser) use ($user, $charges, $donation, $addthree) {
            $browser->loginAs($user)->visitRoute('reports.deposits')
                ->waitFor('div.tab-content div.active')->assertSee('Undeposited');
            $browser->assertSee(number_format($charges[0]->amount + $donation->amount + $addthree->amount, 2));
            foreach ($charges as $charge) {
                $browser->assertSee(number_format($charge->amount, 2))->assertSee($charge->timestamp)
                    ->assertSee($charge->memo);
            }
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success')->assertMissing('Undeposited');
        });

    }

    public function testWorkshops()
    {
        $faker = Factory::create();
        $user = factory(User::class)->create(['usertype' => Usertype::Pc]);
        $campers = array();
        $workshop = factory(Workshop::class)->create(['year_id' => self::$year->id, 'capacity' => rand(1, 98),
            'timeslot_id' => Timeslotname::Sunrise]);
        for ($i = 0; $i < rand($workshop->capacity+1, 99); $i++) {
            $camper = factory(Camper::class)->create();
            $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
            $yw = ['yearattending_id' => $ya->id, 'workshop_id' => $workshop->id, 'created_at' => $faker->dateTimeThisMonth];
            if($i == 0) $yw["is_leader"] = 1;
            factory(YearattendingWorkshop::class)->create($yw);
        }
        DB::statement('CALL workshops()');

        $this->browse(function (Browser $browser) use ($user, $campers, $workshop) {
            $browser->loginAs($user)->visitRoute('reports.workshops')->waitForText($workshop->name)
                ->assertSee('Leader');
            foreach ($campers as $camper) {
                $browser->assertSee($camper->firstname)->assertSee($camper->lastname);
            }
            $browser->assertPresent('tr.table-danger');
        });
    }
}
