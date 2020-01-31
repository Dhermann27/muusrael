<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Facebook\WebDriver\Exception\TimeOutException;
use Laravel\Dusk\Component as BaseComponent;

class HouseholdForm extends BaseComponent
{
    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function selector()
    {
        return 'form#household';
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@adr1' => 'input[name="address1"]',
            '@adr2' => 'input[name="address2"]',
            '@city' => 'input[name="city"]',
            '@state' => 'select[name="province_id"]',
            '@zip' => 'input[name="zipcd"]',
            '@country' => 'input[name="country"]',
            '@iac' => 'select[name="is_address_current"]',
            '@ie' => 'select[name="is_ecomm"]',
            '@is' => 'select[name="is_scholar"]',
            '@submit' => 'button[type="submit"]'
        ];
    }

    /**
     * @throws TimeOutException
     */
    public function createHousehold(Browser $browser, $hh)
    {
        $browser->type('@adr1', $hh->address1)
            ->type('@adr2', $hh->address2)
            ->type('@city', $hh->city)
            ->select('@state', $hh->province_id)
            ->type('@zip', $hh->zipcd)
            ->type('@country', $hh->country)
            ->select('@ie', $hh->is_ecomm)
            ->select('@is', $hh->is_scholar)
            ->click('@submit')->waitFor('div.alert')->assertVisible('div.alert-success');
    }


    /**
     * @throws TimeOutException
     */
    public function changeHousehold(Browser $browser, $from, $to)
    {
        $browser->assertInputValue('@adr1', $from->address1)->type('@adr1', $to->address1)
            ->assertInputValue('@adr2', $from->address2)->type('@adr2', $to->address2)
            ->assertInputValue('@city', $from->city)->type('@city', $to->city)
            ->assertSelected('@state', $from->province_id)->select('@state', $to->province_id)
            ->assertInputValue('@zip', $from->zipcd)->type('@zip', $to->zipcd)
            ->assertInputValue('@country', $from->country)->type('country', $to->country)
            ->assertSelected('@ie', $from->is_ecomm)->select('@ie', $to->is_ecomm)
            ->assertSelected('@is', $from->is_scholar)->select('@is', $to->is_scholar)
            ->click('@submit')->waitFor('div.alert')->assertVisible('div.alert-success');
    }

    public function viewHousehold(Browser $browser, $hh)
    {
        $browser->assertInputValue('@adr1', $hh->address1)->assertDisabled('@adr1')
            ->assertInputValue('@adr2', $hh->address2)->assertDisabled('@adr2')
            ->assertInputValue('@city', $hh->city)->assertDisabled('@city')
            ->assertSelected('@state', $hh->province_id)->assertDisabled('@state')
            ->assertInputValue('@zip', $hh->zipcd)->assertDisabled('@zip')
            ->assertInputValue('@country', $hh->country)->assertDisabled('@country')
            ->assertSelected('@iac', $hh->is_address_current)->assertDisabled('@iac')
            ->assertSelected('@ie', $hh->is_ecomm)->assertDisabled('@ie')
            ->assertSelected('@is', $hh->is_scholar)->assertDisabled('@is')
            ->assertMissing('@submit');
    }
}
