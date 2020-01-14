<?php

namespace Tests\Browser;

use App\Building;
use App\Program;
use App\Rate;
use Carbon\Carbon;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * @group Home
 */
class WelcomeTest extends DuskTestCase
{

    public function testWelcome()
    {
        $firstday = Carbon::parse('first Sunday of July ' . self::$year->year); // TODO: Replace with regexp
        $this->browse(function (Browser $browser) use ($firstday) {
            $browser->visit('/')
                ->assertSee('Midwest Unitarian Universalist Summer Assembly')
                ->assertSee('Register for ' . self::$year->year)
                ->assertSee('Sunday ' . $firstday->format('F jS') .
                    ' through Saturday July ' . $firstday->addDays(6)->format('jS') . ' ' . self::$year->year);
        });
    }

    public function testHousing()
    {
        $building = factory(Building::class)->create(['id' => 1000]);

        $this->browse(function (Browser $browser) use ($building) {
            $browser->visit('/housing')
                ->assertSee($building->name);
        });
    }

    public function testPrograms()
    {
        $program = factory(Program::class)->create();

        $this->browse(function (Browser $browser) use ($program) {
            $browser->visit('/programs')
                ->assertSee($program->name);
        });
    }

    public function testScholarship()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/scholarship')
                ->assertSee('financial assistance');
        });
    }

    public function testThemeSpeaker()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/themespeaker')
                ->assertSee('Rev');
        });
    }

    public function testCampCost()
    {
        $program = factory(Program::class)->create();
        $buildings = array();
        $lodgerates = array();
        $lakewoodrates = array();
        $tentrates = array();

        array_push($buildings, Building::find('1000'));
        array_push($buildings, factory(Building::class)->create(['id' => 1007]));
        array_push($buildings, factory(Building::class)->create(['id' => 1017]));

        for ($i = 1; $i < 8; $i++) {
            array_push($lodgerates, factory(Rate::class)->create(
                ['building_id' => $buildings[0]->id, 'program_id' => $program->id, 'min_occupancy' => $i, 'max_occupancy' => $i]));
            array_push($tentrates, factory(Rate::class)->create(
                ['building_id' => $buildings[1]->id, 'program_id' => $program->id, 'min_occupancy' => $i, 'max_occupancy' => $i]));
            array_push($lakewoodrates, factory(Rate::class)->create(
                ['building_id' => $buildings[2]->id, 'program_id' => $program->id, 'min_occupancy' => $i, 'max_occupancy' => $i]));
        }

        $this->browse(function (Browser $browser) use ($lodgerates, $lakewoodrates, $tentrates) {
            $browser->visit('/cost')
                ->assertSee('actual fees may vary');

            $browser->click('@adultup')->assertSee('choose a housing type');

            $browser->select('adults-housing', '1')->assertSee('half the amount shown')
                ->assertSeeIn('span#deposit', 200.00);

            for ($i = 1; $i < 6; $i++) {
                $browser->assertSeeIn('div#adults-fee', $this->moneyFormat($lodgerates[0 + min($i - 1, 3)]->rate * 6 * $i));

                $browser->select('adults-housing', '3')
                    ->assertSeeIn('div#adults-fee', $this->moneyFormat($lakewoodrates[0]->rate * 6 * $i));

                $browser->select('adults-housing', '4')
                    ->assertSeeIn('div#adults-fee', $this->moneyFormat($tentrates[0]->rate * 6 * $i));

                $browser->click('@adultup')->select('adults-housing', '1');
            }

            for ($i = 1; $i < 6; $i++) {
                $browser->click('@kidup')->select('adults-housing', '1')
                    ->assertSeeIn('div#children-fee', $this->moneyFormat($lodgerates[4]->rate * 6 * $i));

                $browser->select('adults-housing', '3')
                    ->assertSeeIn('div#children-fee', $this->moneyFormat($lakewoodrates[2]->rate * 6 * $i));

                $browser->select('adults-housing', '4')
                    ->assertSeeIn('div#children-fee', $this->moneyFormat($tentrates[2]->rate * 6 * $i));

            }

            $browser->click('@yaup')->assertSee('choose a housing type');

            for ($i = 1; $i < 6; $i++) {
                $browser->select('yas-housing', '1')
                    ->assertSeeIn('div#yas-fee', $this->moneyFormat($lakewoodrates[6]->rate * 6 * $i));

                $browser->select('yas-housing', '2')
                    ->assertSeeIn('div#yas-fee', $this->moneyFormat($tentrates[6]->rate * 6 * $i));

                $browser->click('@yaup');
            }

            for ($i = 1; $i < 6; $i++) {
                $browser->click('@jrup')
                    ->assertSeeIn('div#jrsrs-fee', $this->moneyFormat($lakewoodrates[1]->rate * 6 * $i));
            }

            for ($i = 1; $i < 6; $i++) {
                $browser->click('@babyup')
                    ->assertSeeIn('div#babies-fee', $this->moneyFormat($lodgerates[6]->rate * 6 * $i));
            }

            $browser->assertSeeIn('span#deposit', 400.00);

        });
    }

}
