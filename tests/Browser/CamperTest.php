<?php

namespace Tests\Browser;

use App\Camper;
use App\Enums\Chargetypename;
use App\Enums\Usertype;
use App\Family;
use App\User;
use App\Year;
use App\Yearattending;
use Carbon\Carbon;
use Facebook\WebDriver\Exception\TimeOutException;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\CamperForm;
use Tests\DuskTestCase;
use Tests\MuusaTrap;
use Throwable;
use function factory;
use function str_replace;

/**
 * @group Campers
 */
class CamperTest extends DuskTestCase
{
    use MuusaTrap;

    /**
     * @group Abraham
     * @throws Throwable
     */
    public function testAbraham()
    {

        $user = factory(User::class)->create();
        $camper = factory(Camper::class)->make(['firstname' => 'Abraham', 'email' => $user->email]);
        $ya = factory(Yearattending::class)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $ya) {
            $browser->loginAs($user->id)->visitRoute('campers.index')
                ->waitFor('form#camperinfo div.tab-content div.active');
            $this->createCamper($browser, $camper, $ya);
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        $this->adh($camper);
        $camper = Camper::latest()->first();
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'program_id' => $ya->program_id, 'days' => $ya->days]);

        $changes = factory(Camper::class)->make(['firstname' => 'Abraham']);
        $cya = factory(Yearattending::class)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $ya, $changes, $cya) {
            $browser->loginAs($user->id)->visitRoute('campers.index')
                ->waitFor('form#camperinfo div.tab-content div.active');
            $this->changeCamper($browser, $camper, $ya, $changes, $cya);
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')->assertVisible('div.alert-success');
        });

        $this->adh($changes);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'program_id' => $cya->program_id, 'days' => $cya->days]);
        $this->assertDatabaseHas('gencharges', ['camper_id' => $camper->id, 'charge' => 200, 'chargetype_id' => Chargetypename::Deposit]);

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

        $changes = factory(Camper::class)->make(['firstname' => 'Beto']);
        $cya = factory(Yearattending::class)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $ya, $changes, $cya) {
            $browser->loginAs($user->id)->visitRoute('campers.index')
                ->waitFor('form#camperinfo div.tab-content div.active');
            $this->changeCamper($browser, $camper, $ya, $changes, $cya);
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });
        $camper = Camper::latest()->first();

        $this->assertDatabaseHas('users', ['email' => $changes->email]);
        $this->adh($changes);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'program_id' => $cya->program_id, 'days' => $cya->days]);
        $this->assertDatabaseHas('gencharges', ['camper_id' => $camper->id, 'charge' => 200, 'chargetype_id' => Chargetypename::Deposit]);

    }

    /**
     * @group Charlie
     * @throws Throwable
     */
    public function testCharlie()
    {

        $user = factory(User::class)->create(['usertype' => Usertype::Admin]);

        $cuser = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Charlie', 'family_id' => $family->id, 'email' => $cuser->email]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);

        $changes = factory(Camper::class)->make(['firstname' => 'Charlie']);
        $cya = factory(Yearattending::class)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $ya, $changes, $cya) {
            $browser->loginAs($user->id)->visitRoute('campers.index', ['id' => $camper->id])
                ->waitFor('form#camperinfo div.tab-content div.active');
            $this->changeCamper($browser, $camper, $ya, $changes, $cya);
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('users', ['email' => $changes->email]);
        $this->adh($changes);
        $this->assertDatabaseHas('yearsattending', ['program_id' => $cya->program_id, 'days' => $cya->days]);
    }

    /**
     * @group Charlie
     * @throws Throwable
     */
    public function testCharlieRO()
    {
        $user = factory(User::class)->create(['usertype' => Usertype::Pc]);

        $cuser = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Charlie', 'family_id' => $family->id, 'email' => $cuser->email]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $ya) {
            $browser->loginAs($user->id)->visitRoute('campers.index', ['id' => $camper->id])
                ->waitFor('form#camperinfo div.tab-content div.active');
            $browser->within(new CamperForm, function ($browser) use ($camper, $ya) {
                $browser->viewCamper($camper, $ya);
            })->assertMissing('button[type="submit"]');
        });


    }

    /**
     * @group Deb
     * @throws Throwable
     */
    public function testDebDistinct()
    {
        $user = factory(User::class)->create();

        $campers = factory(Camper::class, 2)->make(['email' => $user->email]);
        $campers[0]->firstname = "Deb";

        $yas = factory(Yearattending::class, 2)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $campers, $yas) {
            $browser->loginAs($user->id)->visitRoute('campers.index')
                ->waitFor('form#camperinfo div.tab-content div.active');
            $this->createCamper($browser, $campers[0], $yas[0]);
            $browser->script('window.scrollTo(0,0)');
            $browser->click('a#newcamper')->pause(250);
            $this->createCamper($browser, $campers[1], $yas[1]);
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-danger')->assertPresent('span.invalid-feedback');
            $campers[1]->email = 'deb@email.org';
            $browser->script('window.scrollTo(0,0)');
            $browser->pause(250)->clickLink($campers[1]->firstname)->pause(250)
                ->type('form#camperinfo div.tab-content div.active input[name="email[]"]', $campers[1]->email);
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        foreach ($campers as $camper) $this->adh($camper);
        $camper = Camper::orderBy('id', 'desc')->first();
        foreach ($yas as $ya) {
            $this->assertDatabaseHas('yearsattending', ['program_id' => $ya->program_id, 'days' => $ya->days]);
        }

        $changes = factory(Camper::class, 2)->make();
        $changes[0]->firstname = "Deb";
        $cyas = factory(Yearattending::class, 2)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $campers, $yas, $changes, $cyas) {
            $browser->loginAs($user->id)->visitRoute('campers.index')
                ->waitFor('form#camperinfo div.tab-content div.active');
            for ($i = 0; $i < count($campers); $i++) {
                $browser->script('window.scrollTo(0,0)');
                $browser->pause(250)->clickLink($campers[$i]->firstname)->pause(250);
                $this->changeCamper($browser, $campers[$i], $yas[$i], $changes[$i], $cyas[$i]);
            }
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        foreach ($changes as $change) $this->adh($change);
        foreach ($cyas as $ya) {
            $this->assertDatabaseHas('yearsattending', ['program_id' => $ya->program_id, 'days' => $ya->days]);
        }
        $this->assertDatabaseHas('gencharges', ['camper_id' => $camper->id, 'charge' => 400, 'chargetype_id' => Chargetypename::Deposit]);
    }

    /**
     * @group Evra
     * @throws Throwable
     */
    public function testEvraDistinct()
    {
        $user = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Evra', 'family_id' => $family->id, 'email' => $user->email]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $family->id]);
        $yas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id]);

        $changes = factory(Camper::class, 2)->make();
        $changes[0]->firstname = "Evra";
        $changes[1]->email = $changes[0]->email;
        $cyas = factory(Yearattending::class, 2)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $campers, $yas, $changes, $cyas) {
            $browser->loginAs($user->id)->visitRoute('campers.index')
                ->waitFor('form#camperinfo div.tab-content div.active');
            for ($i = 0; $i < count($campers); $i++) {
                $browser->script('window.scrollTo(0,0)');
                $browser->pause(250)->clickLink($campers[$i]->firstname)->pause(250);
                $this->changeCamper($browser, $campers[$i], $yas[$i], $changes[$i], $cyas[$i]);
            }
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-danger')->assertPresent('span.invalid-feedback');
            $changes[1]->email = 'evra@email.org';
            $browser->script('window.scrollTo(0,0)');
            $browser->pause(250)->clickLink($changes[1]->firstname)->pause(250)
                ->type('form#camperinfo div.tab-content div.active input[name="email[]"]', $changes[1]->email);
            $browser->pause(250)->click('button[type="submit"]')->acceptDialog()
                ->waitFor('div.alert')->assertVisible('div.alert-success');
        });

        foreach ($changes as $camper) $this->adh($camper);
        foreach ($cyas as $ya) {
            $this->assertDatabaseHas('yearsattending', ['program_id' => $ya->program_id, 'days' => $ya->days]);
        }
        $camper = Camper::orderBy('id', 'desc')->first();
        $this->assertDatabaseHas('gencharges', ['camper_id' => $camper->id, 'charge' => 400, 'chargetype_id' => Chargetypename::Deposit]);
    }

    /**
     * @group Franklin
     * @throws Throwable
     */
    public function testFranklinDistinct()
    {

        $user = factory(User::class)->create(['usertype' => Usertype::Admin]);

        $cuser = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Franklin', 'family_id' => $family->id, 'email' => $cuser->email]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $family->id]);
        $yas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => self::$year->id]);

        $changes = factory(Camper::class, 2)->make();
        $changes[0]->firstname = "Franklin";
        $changes[1]->email = $changes[0]->email;
        $cyas = factory(Yearattending::class, 2)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $campers, $yas, $changes, $cyas) {
            $browser->loginAs($user->id)->visitRoute('campers.index', ['id' => $campers[0]->id])
                ->waitFor('form#camperinfo div.tab-content div.active');
            for ($i = 0; $i < count($campers); $i++) {
                $browser->script('window.scrollTo(0,0)');
                $browser->pause(250)->clickLink($campers[$i]->firstname)->pause(250);
                $this->changeCamper($browser, $campers[$i], $yas[$i], $changes[$i], $cyas[$i]);
            }
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-danger')->assertPresent('span.invalid-feedback');
            $changes[1]->email = 'franklin@email.org';
            $browser->script('window.scrollTo(0,0)');
            $browser->pause(250)->clickLink($changes[1]->firstname)->pause(250)
                ->type('form#camperinfo div.tab-content div.active input[name="email[]"]', $changes[1]->email);
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        foreach ($changes as $camper) $this->adh($camper);
        foreach ($cyas as $ya) {
            $this->assertDatabaseHas('yearsattending', ['program_id' => $ya->program_id, 'days' => $ya->days]);
        }
    }

    /**
     * @group Franklin
     * @throws Throwable
     */
    public function testFranklinRO()
    {

        $user = factory(User::class)->create(['usertype' => Usertype::Pc]);

        $cuser = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $campers[0] = factory(Camper::class)->create(['firstname' => 'Franklin', 'family_id' => $family->id, 'email' => $cuser->email]);
        $yas[0] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => self::$year->id]);
        $campers[1] = factory(Camper::class)->create(['family_id' => $family->id]);
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
     * @group Geoff
     * @throws Throwable
     */
    public function testGeoffUniqueCamper()
    {
        $birth = Carbon::now();
        $birth->year = self::$year->year - 20;

        $user = factory(User::class)->create();

        $camper = factory(Camper::class)->make(['firstname' => 'Geoff',
            'birthdate' => $birth->addDays(rand(0, 364))->toDateString(), 'email' => $user->email]);
        $ya = factory(Yearattending::class)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $ya) {
            $browser->loginAs($user->id)->visitRoute('campers.index')
                ->waitFor('form#camperinfo div.tab-content div.active');
            $this->createCamper($browser, $camper, $ya);
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        $this->adh($camper);
        $camper = Camper::latest()->first();
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'program_id' => $ya->program_id, 'days' => $ya->days]);

        $snowfamily = factory(Family::class)->create();
        $snowflake = factory(Camper::class)->create(['family_id' => $snowfamily->id]);
        $changes = factory(Camper::class)->make(['firstname' => 'Geoff', 'birthdate' => $birth->addDays(rand(0, 364))->toDateString(), 'email' => $snowflake->email]);
        $cya = factory(Yearattending::class)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $ya, $changes, $cya) {
            $browser->loginAs($user->id)->visitRoute('campers.index')
                ->waitFor('form#camperinfo div.tab-content div.active');
            $this->changeCamper($browser, $camper, $ya, $changes, $cya);
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-danger')->assertPresent('span.invalid-feedback');
            $changes->email = 'geoff@email.org';
            $browser->clear('form#camperinfo div.tab-content div.active input[name="email[]"]')
                ->type('form#camperinfo div.tab-content div.active input[name="email[]"]', $changes->email)
                ->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        $this->adh($changes);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'program_id' => $cya->program_id, 'days' => $cya->days]);
        $this->assertDatabaseHas('gencharges', ['camper_id' => $camper->id, 'charge' => 200, 'chargetype_id' => Chargetypename::Deposit]);

    }

    /**
     * @group Henrietta
     * @throws Throwable
     */
    public function testHenriettaUniqueUser()
    {
        $birth = Carbon::now();
        $birth->year = self::$year->year - 20;

        $user = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Henrietta', 'family_id' => $family->id, 'email' => $user->email, 'birthdate' => $birth->addDays(rand(0, 364))->toDateString()]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);

        $snowfamily = factory(Family::class)->create();
        $snowflake = factory(Camper::class)->create(['family_id' => $snowfamily->id]);
        $changes = factory(Camper::class)->make(['firstname' => 'Henrietta', 'email' => $snowflake->email, 'birthdate' => $birth->addDays(rand(0, 364))->toDateString()]);
        $cya = factory(Yearattending::class)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $ya, $changes, $cya) {
            $browser->loginAs($user->id)->visitRoute('campers.index')
                ->waitFor('form#camperinfo div.tab-content div.active');
            $this->changeCamper($browser, $camper, $ya, $changes, $cya);
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-danger')->assertPresent('span.invalid-feedback');
            $changes->email = 'henrietta@email.org';
            $browser->clear('form#camperinfo div.tab-content div.active input[name="email[]"]')
                ->type('form#camperinfo div.tab-content div.active input[name="email[]"]', $changes->email)
                ->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('users', ['email' => $changes->email]);
        $this->adh($changes);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $camper->id, 'program_id' => $cya->program_id, 'days' => $cya->days]);
        $this->assertDatabaseHas('gencharges', ['camper_id' => $camper->id, 'charge' => 200, 'chargetype_id' => Chargetypename::Deposit]);

    }

    /**
     * @group Ingrid
     * @throws Throwable
     */
    public function testIngridUniqueCamper()
    {
        $birth = Carbon::now();
        $birth->year = self::$year->year - 20;

        $user = factory(User::class)->create(['usertype' => Usertype::Admin]);

        $cuser = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Ingrid', 'family_id' => $family->id, 'email' => $cuser->email, 'birthdate' => $birth->addDays(rand(0, 364))->toDateString()]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);

        $snowfamily = factory(Family::class)->create();
        $snowflake = factory(Camper::class)->create(['family_id' => $snowfamily->id]);
        $changes = factory(Camper::class)->make(['firstname' => 'Ingrid', 'email' => $snowflake->email, 'birthdate' => $birth->addDays(rand(0, 364))->toDateString()]);
        $cya = factory(Yearattending::class)->make(['year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $ya, $changes, $cya) {
            $browser->loginAs($user->id)->visitRoute('campers.index', ['id' => $camper->id])
                ->waitFor('form#camperinfo div.tab-content div.active');
            $this->changeCamper($browser, $camper, $ya, $changes, $cya);
            $browser->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-danger')->assertPresent('span.invalid-feedback');
            $changes->email = 'ingrid@email.org';
            $browser->clear('form#camperinfo div.tab-content div.active input[name="email[]"]');
            $browser->keys('form#camperinfo div.tab-content div.active input[name="email[]"]', $changes->email);
            $browser->click('form#camperinfo button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        $this->assertDatabaseHas('users', ['email' => $changes->email]);
        $this->adh($changes);
        $this->assertDatabaseHas('yearsattending', ['program_id' => $cya->program_id, 'days' => $cya->days]);
    }

    /**
     * @group Ingrid
     * @throws Throwable
     */
    public function testIngridRO()
    {
        $birth = Carbon::now();
        $birth->year = self::$year->year - 20;

        $user = factory(User::class)->create(['usertype' => Usertype::Pc]);

        $cuser = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $camper = factory(Camper::class)->create(['firstname' => 'Ingrid', 'family_id' => $family->id, 'email' => $cuser->email, 'birthdate' => $birth->addDays(rand(0, 364))->toDateString()]);
        $ya = factory(Yearattending::class)->create(['camper_id' => $camper->id, 'year_id' => self::$year->id]);

        $this->browse(function (Browser $browser) use ($user, $camper, $ya) {
            $browser->loginAs($user->id)->visitRoute('campers.index', ['id' => $camper->id])
                ->waitFor('form#camperinfo div.tab-content div.active');
            $browser->within(new CamperForm, function ($browser) use ($camper, $ya) {
                $browser->viewCamper($camper, $ya);
            })->assertMissing('button[type="submit"]');
        });

    }

    /**
     * @group Quentin
     * @throws Throwable
     */
    public function testQuentinLastProgramId()
    {
        $lastyear = factory(Year::class)->create(['is_current' => 0, 'year' => self::$year->year - 1]);
        $user = factory(User::class)->create();
        $family = factory(Family::class)->create();
        $head = factory(Camper::class)->create(['firstname' => 'Quentin', 'family_id' => $family->id, 'email' => $user->email]);
        $campers = factory(Camper::class, 2)->create(['family_id' => $family->id]);
        $lyah = factory(Yearattending::class)->create(['camper_id' => $head->id, 'year_id' => $lastyear->id]);
        $lyas[0] = factory(Yearattending::class)->create(['camper_id' => $campers[0]->id, 'year_id' => $lastyear->id]);
        $lyas[1] = factory(Yearattending::class)->create(['camper_id' => $campers[1]->id, 'year_id' => $lastyear->id]);


        $this->browse(function (Browser $browser) use ($user, $head, $campers, $lyah, $lyas) {
            $browser->loginAs($user->id)->visitRoute('campers.index')
                ->waitFor('form#camperinfo div.tab-content div.active')
                ->clickLink($head->firstname)->pause(250)
                ->select('form#camperinfo div.tab-content div.active select[name="days[]"]', $lyah->days)
                ->assertSelected('form#camperinfo div.tab-content div.active select[name="program_id[]"]', $lyah->program_id)
                ->clickLink($campers[0]->firstname)->pause(250)
                ->select('form#camperinfo div.tab-content div.active select[name="days[]"]', $lyas[0]->days)
                ->assertSelected('form#camperinfo div.tab-content div.active select[name="program_id[]"]', $lyas[0]->program_id)
                ->clickLink($campers[1]->firstname)->pause(250)
                ->select('form#camperinfo div.tab-content div.active select[name="days[]"]', $lyas[1]->days)
                ->assertSelected('form#camperinfo div.tab-content div.active select[name="program_id[]"]', $lyas[1]->program_id)
                ->select('form#camperinfo div.tab-content div.active select[name="program_id[]"]', $lyas[0]->program_id)
                ->click('button[type="submit"]')->acceptDialog()->waitFor('div.alert')
                ->assertVisible('div.alert-success');
        });

        $this->adh($head);
        $this->adh($campers[0]);
        $this->adh($campers[1]);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $head->id, 'program_id' => $lyah->program_id, 'days' => $lyah->days]);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $campers[0]->id, 'program_id' => $lyas[0]->program_id, 'days' => $lyas[0]->days]);
        $this->assertDatabaseHas('yearsattending', ['camper_id' => $campers[1]->id, 'program_id' => $lyas[0]->program_id, 'days' => $lyas[1]->days]);

    }

    /**
     * @throws TimeOutException
     */
    private function createCamper(Browser $browser, $camper, $ya)
    {
        $browser->within(new CamperForm, function ($browser) use ($camper, $ya) {
            $browser->createCamper($camper, $ya);
        })->waitFor('.select2-container--open')
            ->type('span.select2-container input.select2-search__field', $camper->church->name)
            ->waitFor('li.select2-results__option--highlighted')->click('li.select2-results__option--highlighted');
    }

    private function adh($camper)
    {
        $this->assertDatabaseHas('campers', ['pronoun_id' => $camper->pronoun_id,
            'firstname' => $camper->firstname, 'lastname' => $camper->lastname, 'email' => $camper->email,
            'phonenbr' => str_replace('-', '', $camper->phonenbr), 'birthdate' => $camper->birthdate,
            'roommate' => $camper->roommate, 'sponsor' => $camper->sponsor, 'is_handicap' => $camper->is_handicap,
            'foodoption_id' => $camper->foodoption_id, 'church_id' => $camper->church_id]);
    }

    /**
     * @throws TimeOutException
     */
    private function changeCamper(Browser $browser, $camper, $ya, $changes, $cya)
    {
        $browser->within(new CamperForm, function ($browser) use ($camper, $ya, $changes, $cya) {
            $browser->changeCamper([$camper, $ya], [$changes, $cya]);
        })->waitFor('.select2-container--open')
            ->type('span.select2-container input.select2-search__field', $changes->church->name)
            ->waitFor('li.select2-results__option--highlighted')->click('li.select2-results__option--highlighted');
    }


}
