<?php

namespace Tests\Browser;

use App\Camper;
use App\Family;
use App\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use function factory;
use function rand;

/**
 * @group RegisterNow
 */
class RegisterNowTest extends DuskTestCase
{
    public function testAbraham()
    {
        $user = factory(User::class)->make();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/')->click('@register_now')->waitFor('div#modal-register')
                ->assertSee('Get Registered for ' . self::$year->year)->waitFor('input#email_create')
                ->type('input#email_create', $user->email)
                ->type('input#password_create', 'password')
                ->type('input#confirm_create', 'password')
                ->pause(50)->click('button#begin_reg')->waitForLocation('/campers')->assertSee($user->email)->logout();
        });
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    public function testDeb()
    {
        $user = factory(User::class)->make();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/')->click('@register_now')->waitFor('div#modal-register')
                ->assertSee('Get Registered for ' . self::$year->year)->waitFor('input#email_create')
                ->type('input#email_create', $user->email)
                ->type('input#password_create', 'password')
                ->type('input#confirm_create', 'password')
                ->click('button[data-dir="up"]')
                ->click('button[data-dir="up"]')
                ->click('button[data-dir="up"]')
                ->pause(50)->click('button#begin_reg')->waitForLocation('/campers')
                ->assertSee($user->email)->assertSee('New Camper')->logout();
        });
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    public function testBeto()
    {
        $user = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Beto', 'family_id' => $family->id, 'email' => $user->email]);

        $this->browse(function (Browser $browser) use ($user, $camper) {
            $browser->visit('/')->click('@register_now')->waitFor('div#modal-register')
                ->assertSee('Get Registered for ' . self::$year->year)->waitFor('input#email_login')
                ->type('input#email_login', $user->email)
                ->type('input#password_login', 'password')
                ->waitFor('div#login-found')
                ->assertSee($camper->firstname . ' ' . $camper->lastname)
                ->pause(50)->click('button#begin_reg')->waitForLocation('/campers')->assertSee($user->email)->logout();
        });
    }

    public function testEvra()
    {
        $user = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Evra', 'family_id' => $family->id, 'email' => $user->email]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $family->id]);

        $this->browse(function (Browser $browser) use ($user, $campers) {
            $browser->visit('/')->click('@register_now')->waitFor('div#modal-register')
                ->assertSee('Get Registered for ' . self::$year->year)->waitFor('input#email_login')
                ->type('input#email_login', $user->email)
                ->type('input#password_login', 'password')
                ->waitFor('div#login-found')
                ->assertSee($campers[0]->firstname . ' ' . $campers[0]->lastname)
                ->assertSee($campers[1]->firstname . ' ' . $campers[1]->lastname)
                ->pause(50)->click('button#begin_reg')->waitForLocation('/campers')->assertSee($user->email)->logout();
        });
    }

    public function testTrentSome()
    {
        $user = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $head = factory(Camper::class)->create(['firstname' => 'Trent', 'family_id' => $family->id, 'email' => $user->email]);
        $campers = factory(Camper::class, 3)->create(['family_id' => $family->id]);

        $this->browse(function (Browser $browser) use ($user, $head, $campers) {
            $browser->visit('/')->click('@register_now')->waitFor('div#modal-register')
                ->assertSee('Get Registered for ' . self::$year->year)->waitFor('input#email_login')
                ->type('input#email_login', $user->email)
                ->type('input#password_login', 'password')
                ->waitFor('div#login-found')
                ->assertSee($head->firstname . ' ' . $head->lastname);
            foreach ($campers as $camper) {
                $browser->assertSee($camper->firstname . ' ' . $camper->lastname);
                $browser->script('$(\'option[value="' . $camper->id . '"]\').prop(\'selected\', ' . rand(0, 1) . ');');
            }
            $browser->pause(50)->click('button#begin_reg')->waitForLocation('/campers')->assertSee($user->email)->logout();
            // TODO: check if each is attending
        });
    }
}
