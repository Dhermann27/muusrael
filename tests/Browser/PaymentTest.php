<?php

namespace Tests\Browser;

use App\Camper;
use App\Charge;
use App\Chargetype;
use App\Enums\Chargetypename;
use App\Enums\Usertype;
use App\User;
use App\Year;
use App\Yearattending;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\CamperForm;
use Tests\DuskTestCase;
use Throwable;
use function count;
use function factory;
use function rand;

/**
 * @group Payment
 */
class PaymentTest extends DuskTestCase
{

    /**
     * @group Abraham
     * @throws Throwable
     */
    public function testAbraham()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)->visitRoute('payment.index')
                ->assertSee('Please fill out your camper information to continue');
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
        DB::statement('CALL generate_charges(' . self::$year->year . ');');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)->visitRoute('payment.index')
                ->assertSee('Deposit for ' . self::$year->year)
                ->assertSeeIn('span#amountNow', 200.0)
                ->assertValue('input#amount', 200.0);
//                ->waitFor('div.paypal-button-env-sandbox')
//                ->waitFor('iframe.component-frame');
//            $browser->driver->switchTo()->frame($browser->driver->findElement(WebDriverBy::className('component-frame'))->getAttribute('name'));
//            $browser->waitFor('[role="button"]')->click('[role="button"]');
//            $window = collect($browser->driver->getWindowHandles())->last();
//            $browser->driver->switchTo()->window($window);
//            $browser->waitFor('input#email')
//                ->type('input#email', env('PAYPAL_LOGIN'))
//                ->type('input#password', env('PAYPAL_PASSWORD'))
//                ->click('button.actionContinue')->waitFor('button#payment-submit-btn:not([disabled])')
//                ->waitFor('div.alert')->assertVisible('div.alert-success');
        });
//        $this->assertDatabaseHas('charges', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
//            'chargetype_id' => Chargetypename::PayPalPayment, 'amount' => 200.0, 'timestamp' => date("Y-m-d")]);
    }

    /**
     * @group Charlie
     * @throws Throwable
     */
    public function testCharlieCheck()
    {

        $user = factory(User::class)->create(['usertype' => Usertype::Admin]);
        factory(Camper::class)->create(['email' => $user->email]);

        $cuser = factory(User::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Charlie', 'email' => $cuser->email]);
        factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ');');

        $charge = factory(Charge::class)->make(['chargetype_id' => Chargetypename::CheckPayment,
            'camper_id' => $camper->id, 'amount' => rand(-20000, -100000) / 100, 'year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $charge) {
            $browser->loginAs($user->id)->visitRoute('payment.index', ['id' => $camper->id])
                ->waitFor('form#muusapayment')
                ->select('chargetype_id', $charge->chargetype_id)->type('amount', $charge->amount)
                ->type('date', $charge->timestamp)->type('memo', $charge->memo)
                ->click('button[type="submit"]')->waitFor('div.alert')
                ->assertVisible('div.alert-success')->logout();
        });

        $this->assertDatabaseHas('charges', ['year_id' => self::$year->id, 'chargetype_id' => Chargetypename::CheckPayment]);

        foreach (static::$browsers as $browser) {
            $browser->driver->manage()->deleteAllCookies();
        }

        $this->browse(function (Browser $browser) use ($cuser, $camper, $charge) {
            $browser->loginAs($cuser->id)->visitRoute('payment.index')
                ->waitFor('form#muusapayment')
                ->assertSee(Chargetype::findOrFail(Chargetypename::CheckPayment)->name)->assertSee($charge->amount)
                ->assertSeeIn('#amountNow', '0.00')->assertSee('Registration')
                ->assertMissing('Register Now');
        });

    }

    /**
     * @group Charlie
     * @throws Throwable
     */
    public function testCharlieRO()
    {
        $user = factory(User::class)->create(['usertype' => Usertype::Pc]);
        factory(Camper::class)->create(['email' => $user->email]);

        $cuser = factory(User::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Charlie', 'email' => $cuser->email]);
        factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ');');

        $charge = factory(Charge::class)->create(['chargetype_id' => Chargetypename::CheckPayment,
            'camper_id' => $camper->id, 'amount' => rand(-20000, -100000) / 100, 'year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $charge) {
            $browser->loginAs($user->id)->visitRoute('payment.index', ['id' => $camper->id])
                ->waitFor('form#muusapayment')->assertSee(Chargetype::findOrFail(Chargetypename::CheckPayment)->name)
                ->assertSee($charge->amount)->assertSeeIn('#amountArrival', '0.00')
                ->assertMissing('button[type="submit"]');
        });


    }

    /**
     * @group Deb
     * @throws Throwable
     */
    public function testDeb()
    {
        $user = factory(User::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Deb', 'email' => $user->email]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $campers[0]->family_id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ');');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)->visitRoute('payment.index')
                ->assertSee('Please choose which campers are attending this year');
        });
    }

    /**
     * @group Evra
     * @throws Throwable
     */
    public function testEvraDonation()
    {
        $user = factory(User::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Evra', 'email' => $user->email]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $campers[0]->family_id]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $yas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ');');

        $this->browse(function (Browser $browser) use ($user) {
            $donation = rand(0, 9999) / 100;
            $browser->loginAs($user->id)->visitRoute('payment.index')
                ->assertSee('Deposit for ' . self::$year->year)
                ->assertSeeIn('span#amountNow', 400.0)
                ->assertValue('input#amount', 400.0)
                ->type('input#donation', $donation)->click('h1')
                ->assertValue('input#amount', 400.0 + $donation);
//                ->waitFor('div.paypal-button-env-sandbox')
//                ->waitFor('iframe.component-frame');
//            $browser->driver->switchTo()->frame($browser->driver->findElement(WebDriverBy::className('component-frame'))->getAttribute('name'));
//            $browser->waitFor('[role="button"]')->click('[role="button"]');
//            $window = collect($browser->driver->getWindowHandles())->last();
//            $browser->driver->switchTo()->window($window);
//            $browser->waitFor('input#email')
//                ->type('input#email', env('PAYPAL_LOGIN'))
//                ->type('input#password', env('PAYPAL_PASSWORD'))
//                ->click('button.actionContinue')->waitFor('button#payment-submit-btn:not([disabled])')
//                ->waitFor('div.alert')->assertVisible('div.alert-success');
        });
//        $this->assertDatabaseHas('charges', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
//            'chargetype_id' => Chargetypename::PayPalPayment, 'amount' => 200.0, 'timestamp' => date("Y-m-d")]);

    }

    /**
     * @group Franklin
     * @throws Throwable
     */
    public function testFranklinMultipleYears()
    {

        $user = factory(User::class)->create(['usertype' => Usertype::Admin]);
        factory(Camper::class)->create(['email' => $user->email]);

        $cuser = factory(User::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Franklin', 'email' => $cuser->email]);
        factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $campers[0]->family_id]);
        factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ');');

        $charge = factory(Charge::class)->create(['chargetype_id' => Chargetypename::CreditCardPayment,
            'camper_id' => $campers[0]->id, 'amount' => rand(-20000, -100000) / 100, 'year_id' => self::$year->id]);

        $years = factory(Year::class, 2)->create(['is_current' => 0]);
        foreach ($years as $year) {
            $charges = array();
            foreach ($campers as $camper) {
                factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => $year->id]);
                array_push($charges, factory(Charge::class)->create([
                    'chargetype_id' => Chargetypename::CreditCardPayment, 'camper_id' => $camper->id,
                    'amount' => rand(-20000, -100000) / 100, 'year_id' => $year->id]));
            }
            $year->charges = $charges;
            DB::statement('CALL generate_charges(' . $year->year . ');');
        }

        $this->browse(function (Browser $browser) use ($user, $campers, $charge, $years) {
            $browser->loginAs($user->id)->visitRoute('payment.index', ['id' => $campers[0]->id])
                ->waitFor('form#muusapayment div.tab-content div.active')
                ->clickLink(self::$year->year)->pause(250)
                ->assertSeeIn('form#muusapayment div.tab-content div.active', $charge->amount);
            foreach ($years as $year) {
                $browser->clickLink($year->year)->pause(250);
                foreach($year->charges as $charge) {
                    $browser->assertSeeIn('form#muusapayment div.tab-content div.active', $charge->amount);
                }
            }
        });

    }

    /**
     * @group Franklin
     * @throws Throwable
     */
    public function testFranklinRO()
    {

        $user = factory(User::class)->create(['usertype' => Usertype::Pc]);

        $cuser = factory(User::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Franklin', 'email' => $cuser->email]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $campers[0]->family_id]);
        $yas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $campers, $yas) {
            $browser->loginAs($user->id)->visitRoute('campers.index', ['id' => $campers[0]->id])
                ->waitFor('form#camperinfo div.tab-content div.active');
            for ($i = 0; $i < count($campers); $i++) {
                $browser->script('window.scrollTo(0,0)');
                $browser->pause(250)->clickLink($campers[$i]->firstname)->pause(250);
                $browser->within(new CamperForm, function (Browser $browser) use ($i, $campers, $yas) {
                    $browser->viewCamper($campers[$i], $yas[$i]);
                });
            }
            $browser->assertMissing('button[type="submit"]');
        });
    }

    /**
     * @group Trent
     * @throws Throwable
     */
    public function testTrentAddThree()
    {
        $user = factory(User::class)->create();
        $head = factory(Camper::class)->create(['firstname' => 'Trent', 'email' => $user->email]);
        factory(Yearattending::class)->create(['camper_id' => $head->id, 'year_id' => self::$year->id]);
        $campers = factory(Camper::class, 3)->create(['family_id' => $head->family_id]);
        foreach ($campers as $camper) {
            factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        }
        DB::statement('CALL generate_charges(' . self::$year->year . ');');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)->visitRoute('payment.index')
                ->assertSee('Deposit for ' . self::$year->year)
                ->assertSeeIn('span#amountNow', 400.0)
                ->assertValue('input#amount', 400.0)
                ->check('addthree');
//                ->waitFor('div.paypal-button-env-sandbox')
//                ->waitFor('iframe.component-frame');
//            $browser->driver->switchTo()->frame($browser->driver->findElement(WebDriverBy::className('component-frame'))->getAttribute('name'));
//            $browser->waitFor('[role="button"]')->click('[role="button"]');
//            $window = collect($browser->driver->getWindowHandles())->last();
//            $browser->driver->switchTo()->window($window);
//            $browser->waitFor('input#email')
//                ->type('input#email', env('PAYPAL_LOGIN'))
//                ->type('input#password', env('PAYPAL_PASSWORD'))
//                ->click('button.actionContinue')->waitFor('button#payment-submit-btn:not([disabled])')
//                ->waitFor('div.alert')->assertVisible('div.alert-success');
        });
//        $this->assertDatabaseHas('charges', ['camper_id' => $camper->id, 'year_id' => self::$year->id,
//            'chargetype_id' => Chargetypename::PayPalPayment, 'amount' => 200.0, 'timestamp' => date("Y-m-d")]);
    }
}
