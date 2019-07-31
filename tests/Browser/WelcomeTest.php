<?php

namespace Tests\Browser;

use Carbon\Carbon;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Year;

class WelcomeTest extends DuskTestCase
{

    public function testWelcome()
    {
        $firstday = Carbon::parse('first Sunday of July ' . self::$year->year); // TODO: Replace with regexp
        $this->browse(function (Browser $browser) use ($firstday) {
            $browser->visit('/')
                ->assertSee('Midwest Unitarian Universalist Summer Assembly')
                ->assertSeeLink('Register for ' . self::$year->year)
                ->assertSee('Sunday ' . $firstday->format('F jS') .
                    ' through Saturday July ' . $firstday->addDays(6)->format('jS') . ' ' . self::$year->year);
        });
    }
}
