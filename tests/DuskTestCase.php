<?php

namespace Tests;

use App\Year;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;

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
            $kernel = $app->make(\App\Console\Kernel::class);
            $kernel->bootstrap();
            echo "Database migrate:refresh --seed\n";
            $kernel->call('migrate:refresh --seed');
            self::$hasSetupRun = true;
            self::$year = Year::where('is_current', 1)->first();
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
            ChromeOptions::CAPABILITY, $options
        )
        );
    }
}
