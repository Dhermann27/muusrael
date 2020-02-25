<?php

namespace Tests\Browser;

use App\Camper;
use App\Contactbox;
use App\Family;
use App\User;
use Faker\Factory;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\MuusaTrap;
use function rand;

class ContactTest extends DuskTestCase
{
    use MuusaTrap;

    // Can only send 2 emails per 10 seconds
    public function testIndex()
    {
        $faker = Factory::create();
        $fakedName = $faker->name;
        $fakedEmail = $faker->safeEmail;
        $fakedGraph = $faker->paragraph;
        $box = factory(Contactbox::class)->create();
        $this->browse(function (Browser $browser) use ($box, $fakedName, $fakedEmail, $fakedGraph) {
            $browser->visitRoute('contact.index')
                ->assertSee('Contact Us')
                ->assertSeeIn('select#mailbox', $box->name)
                ->type('name', $fakedName)
                ->type('email', $fakedEmail)
                ->select('mailbox', $box->id)
                ->type('message', $fakedGraph)
                ->click('button[type="submit"]')->waitFor('div.alert')->assertVisible('div.alert-success');

        });

        $lastEmail = $this->fetchInbox()[0];
        $this->assertEquals($box->emails, $lastEmail['to_email']);
        $body = $this->fetchBody($lastEmail['inbox_id'], $lastEmail['id']);
        $this->assertStringContainsString($fakedName . " <" . $fakedEmail . ">", $body);
        $this->assertStringContainsString($fakedGraph, $body);
    }

    public function testSent()
    {
        if (rand(0, 1) == 0) {
            $this->multisend();
        } else {
            $this->campersend();
        }
    }

    private function multisend()
    {
        $faker = Factory::create();
        $fakedName = $faker->name;
        $fakedEmail = $faker->safeEmail;
        $fakedGraph = $faker->paragraph;
        $box = factory(Contactbox::class)->create(['emails' => $faker->safeEmail . ',' . $faker->safeEmail]);
        $this->browse(function (Browser $browser) use ($box, $fakedName, $fakedEmail, $fakedGraph) {
            $browser->visitRoute('contact.index')
                ->assertSee('Contact Us')
                ->assertSeeIn('select#mailbox', $box->name)
                ->type('name', $fakedName)
                ->type('email', $fakedEmail)
                ->select('mailbox', $box->id)
                ->type('message', $fakedGraph)
                ->click('button[type="submit"]')->waitFor('div.alert')->assertVisible('div.alert-success');

        });

        $lastEmail = $this->fetchInbox()[0];
        $emails = explode(', ', $lastEmail['to_email']);
        foreach ($emails as $email) {
            $this->assertContains($email, $box->emails);
        }
        $body = $this->fetchBody($lastEmail['inbox_id'], $lastEmail['id']);
        $this->assertStringContainsString($fakedName . " <" . $fakedEmail . ">", $body);
        $this->assertStringContainsString($fakedGraph, $body);
    }


    public function testGodsent()
    {
        $faker = Factory::create();
        $fakedName = $faker->name;
        $fakedEmail = $faker->safeEmail;
        $fakedGraph = "Howdy ho there neighbor. Have you heard the Good News about the scriptures, in the words of Christ?";
        $box = factory(Contactbox::class)->create();
        $this->browse(function (Browser $browser) use ($box, $fakedName, $fakedEmail, $fakedGraph) {
            $browser->visitRoute('contact.index')
                ->assertSee('Contact Us')
                ->assertSeeIn('select#mailbox', $box->name)
                ->type('name', $fakedName)
                ->type('email', $fakedEmail)
                ->select('mailbox', $box->id)
                ->type('message', $fakedGraph)
                ->click('button[type="submit"]')->waitFor('div.alert')->assertVisible('div.alert-danger');

        });

        $lastEmail = $this->fetchInbox()[0];
        $this->assertNotEquals($box->emails, $lastEmail['to_email']);
        $body = $this->fetchBody($lastEmail['inbox_id'], $lastEmail['id']);
        $this->assertStringNotContainsString($fakedName . " <" . $fakedEmail . ">", $body);
    }

    private function campersend()
    {
        $faker = Factory::create();
        $user = factory(User::class)->create();
        $camper = factory(Camper::class)->create(['email' => $user->email]);

        $fakedGraph = $faker->paragraph;
        $box = factory(Contactbox::class)->create();
        $this->browse(function (Browser $browser) use ($box, $user, $camper, $fakedGraph) {
            $browser->loginAs($user->id)->visitRoute('contact.index')
                ->assertSee('Contact Us')
                ->assertSee($camper->firstname . ' ' . $camper->lastname)
                ->assertSee($camper->email)
                ->assertSeeIn('select#mailbox', $box->name)
                ->select('mailbox', $box->id)
                ->type('message', $fakedGraph)
                ->click('button[type="submit"]')->waitFor('div.alert')
                ->assertVisible('div.alert-success')->logout();

        });

        $lastEmail = $this->fetchInbox()[0];
        $this->assertEquals($box->emails, $lastEmail['to_email']);
        $body = $this->fetchBody($lastEmail['inbox_id'], $lastEmail['id']);
        $this->assertStringContainsString($camper->firstname . " " . $camper->lastname . " <" . $camper->email . ">", $body);
        $this->assertStringContainsString($fakedGraph, $body);
    }
}
