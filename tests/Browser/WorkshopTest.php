<?php

namespace Tests\Browser;

use App\Building;
use App\Enums\Timeslotname;
use App\Program;
use App\Rate;
use App\Timeslot;
use App\Workshop;
use App\Year;
use Carbon\Carbon;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use function array_push;
use function factory;
use function rand;

/**
 * @group Workshops
 */
class WorkshopTest extends DuskTestCase
{

    public function testWorkshops()
    {
        $timeslots = Timeslot::all()->except(Timeslotname::Excursions);
        foreach ($timeslots as $timeslot) {
            factory(Workshop::class, rand(1, 10))->create(['timeslot_id' => $timeslot->id, 'year_id' => self::$year->id]);
            $wrongshop = factory(Workshop::class)->create(['timeslot_id' => $timeslot->id, 'year_id' => factory(Year::class)->create()]);
        }

        $this->browse(function (Browser $browser) use ($timeslots, $wrongshop) {
            $browser->visit('/workshops')->waitFor('div.tab-content div.active');
            foreach ($timeslots as $timeslot) {
                $browser->script('window.scrollTo(0,0)');
                $browser->clickLink($timeslot->name)->pause('250');
                $browser->assertSeeIn("div.tab-content div.active", $timeslot->start_time->format('g:i A'));
                foreach ($timeslot->workshops()->where('year_id', self::$year->id) as $workshop) {
                    $browser->assertSeeIn("div.tab-content div.active", $workshop->name)
                        ->assertSeeIn("div.tab-content div.active", $workshop->displayDays);
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
        $wrongshop = factory(Workshop::class)->create(['timeslot_id' => Timeslotname::Excursions, 'year_id' => factory(Year::class)->create()]);
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
