<?php

namespace Tests\Browser;

use App\Http\Camper;
use App\Http\Family;
use App\Http\User;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;
use function array_push;
use function count;
use function factory;
use function rand;

/**
 * @group RegisterNow
 */
class RegisterNowTest extends DuskTestCase
{
    /**
     * @group Abraham
     * @throws Throwable
     */
    public function testAbraham()
    {
        $user = factory(User::class)->make();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->logout()->visit('/')->click('@register_now')->waitFor('div#modal-register')
                ->assertSee('Get Registered for ' . self::$year->year)->waitFor('input#email_create')
                ->type('input#email_create', $user->email)
                ->type('input#password_create', 'password')
                ->type('input#confirm_create', 'password')
                ->pause(50)->click('button#begin_reg')->waitForLocation('/campers')->assertSee($user->email);
        });
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    /**
     * @group Deb
     * @throws Throwable
     */
    public function testDeb()
    {
        $user = factory(User::class)->make();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->logout()->visit('/')->click('@register_now')->waitFor('div#modal-register')
                ->assertSee('Get Registered for ' . self::$year->year)->waitFor('input#email_create')
                ->type('input#email_create', $user->email)
                ->type('input#password_create', 'password')
                ->type('input#confirm_create', 'password');
            $count = rand(1, 6);
            for ($i = 0; $i < $count; $i++) {
                $browser->click('button[data-dir="up"]');
            }
            $browser->pause(50)->click('button#begin_reg')->waitForLocation('/campers')
                ->assertSee($user->email);
            $this->assertCount($count+1, $browser->elements('form#camperinfo a.nav-link:not(.btn-secondary)'));
        });
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    /**
     * @group Beto
     * @throws Throwable
     */
    public function testBeto()
    {
        $user = factory(User::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Beto', 'email' => $user->email]);

        $this->browse(function (Browser $browser) use ($user, $camper) {
            $browser->logout()->visit('/')->click('@register_now')->waitFor('div#modal-register')
                ->assertSee('Get Registered for ' . self::$year->year)->waitFor('input#email_login')
                ->type('input#email_login', $user->email)
                ->type('input#password_login', 'password')
                ->waitFor('div#login-found')
                ->assertSee($camper->firstname . ' ' . $camper->lastname)
                ->pause(50)->click('button#begin_reg')->waitForLocation('/campers')->assertSee($user->email);
        });
    }

    /**
     * @group Evra
     * @throws Throwable
     */
    public function testEvra()
    {
        $user = factory(User::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Evra',  'email' => $user->email]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $campers[0]->family_id]);

        $this->browse(function (Browser $browser) use ($user, $campers) {
            $browser->logout()->visit('/')->click('@register_now')->waitFor('div#modal-register')
                ->assertSee('Get Registered for ' . self::$year->year)->waitFor('input#email_login')
                ->type('input#email_login', $user->email)
                ->type('input#password_login', 'password')
                ->waitFor('div#login-found')
                ->assertSee($campers[0]->firstname . ' ' . $campers[0]->lastname)
                ->assertSee($campers[1]->firstname . ' ' . $campers[1]->lastname)
                ->pause(50)->click('button#begin_reg')->waitForLocation('/campers')->assertSee($user->email);
        });
    }

    /**
     * @group Trent
     * @throws Throwable
     */
    public function testTrentSome()
    {
        $user = factory(User::class)->create();
        $head = factory(Camper::class)->create(['firstname' => 'Trent', 'email' => $user->email]);
        $campers = factory(Camper::class, 3)->create(['family_id' => $head->family_id]);

        $this->browse(function (Browser $browser) use ($user, $head, $campers) {
            $browser->logout()->visit('/')->click('@register_now')->waitFor('div#modal-register')
                ->assertSee('Get Registered for ' . self::$year->year)->waitFor('input#email_login')
                ->type('input#email_login', $user->email)
                ->type('input#password_login', 'password')
                ->waitFor('div#login-found')
                ->assertSee($head->firstname . ' ' . $head->lastname);
            $yas = array();
            foreach ($campers as $camper) {
                $browser->assertSee($camper->firstname . ' ' . $camper->lastname);
                $camper->coming = rand(0, 1) * 6;
                array_push($yas, $camper->coming);
                $browser->script('$(\'option[value="' . $camper->id . '"]\').prop(\'selected\', ' . $camper->coming . ');');
            }
            $browser->pause(50)->click('button#begin_reg')->waitForLocation('/campers');
//                ->assertSee($user->email);

            foreach ($campers as $camper) {
                $browser->clickLink($camper->firstname)->pause(250)
                    ->assertInputValue('form#camperinfo div.tab-content div.active input[name="email[]"]', $camper->email)
                    ->assertSelected('form#camperinfo div.tab-content div.active select[name="days[]"]', $camper->coming);
            }
        });
    }
}
