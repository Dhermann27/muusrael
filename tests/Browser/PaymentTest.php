<?php

namespace Tests\Browser;

use App\Camper;
use App\Family;
use App\User;
use App\Yearattending;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;
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
            $browser->loginAs($user->id)->visit('/payment')
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
        $family = factory(Family::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Beto', 'family_id' => $family->id, 'email' => $user->email]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ');');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)->visit('/payment')
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
     * @group Deb
     * @throws Throwable
     */
    public function testDeb()
    {
        $user = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Deb', 'family_id' => $family->id, 'email' => $user->email]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $family->id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ');');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)->visit('/payment')
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
        $family = factory(Family::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Evra', 'family_id' => $family->id, 'email' => $user->email]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $family->id]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $yas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id]);
        DB::statement('CALL generate_charges(' . self::$year->year . ');');

        $this->browse(function (Browser $browser) use ($user) {
            $donation = rand(0, 9999) / 100;
            $browser->loginAs($user->id)->visit('/payment')
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
     * @group Trent
     * @throws Throwable
     */
    public function testTrentAddThree()
    {
        $user = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $head = factory(Camper::class)->create(['firstname' => 'Trent', 'family_id' => $family->id, 'email' => $user->email]);
        factory(Yearattending::class)->create(['camper_id' => $head->id, 'year_id' => self::$year->id]);
        $campers = factory(Camper::class, 3)->create(['family_id' => $family->id]);
        foreach ($campers as $camper) {
            factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);
        }
        DB::statement('CALL generate_charges(' . self::$year->year . ');');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)->visit('/payment')
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
