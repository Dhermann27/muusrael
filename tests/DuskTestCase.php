<?php

namespace Tests;

use App\Console\Kernel;
use App\Year;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;
use NumberFormatter;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;
    protected static $hasSetupRun = false;
    protected static $year;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        if (!self::$hasSetupRun) {
            $app = require __DIR__ . '/../bootstrap/app.php';
            $kernel = $app->make(Kernel::class);
            $kernel->bootstrap();
            echo "Database migrate:refresh --seed\n";
            $kernel->call('migrate:refresh --seed');
            self::$hasSetupRun = true;
            self::$year = factory(Year::class)->create(['is_current' => 1]);
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        foreach (static::$browsers as $browser) {
            $browser->driver->manage()->deleteAllCookies();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--window-size=1920,1080',
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options)
        );
    }

    protected function moneyFormat($float)
    {
        $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $fmt->setAttribute(NumberFormatter::GROUPING_USED, 0);
        return $fmt->formatCurrency($float, "USD");
    }
}
