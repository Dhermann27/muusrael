<?php

namespace Tests\Browser;

use App\Enums\Pctype;
use App\Enums\Usertype;
use App\Program;
use App\Staffposition;
use App\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use function factory;
use function number_format;
use function rand;

/**
 * @group Admin
 */
class AdminTest extends DuskTestCase
{
    public function testPositions()
    {
        $user = factory(User::class)->create(['usertype' => Usertype::Admin]);
        $program = factory(Program::class)->create();
        $staffposition = factory(Staffposition::class)->create(['program_id' => $program->id]);
        $newprogram = factory(Program::class)->create();
        $newposition = factory(Staffposition::class)->make(['program_id' => $newprogram->id, 'pctype' => rand(1, 4)]);


        $this->browse(function (Browser $browser) use ($user, $program, $staffposition, $newprogram, $newposition) {
            $browser->loginAs($user)->visitRoute('admin.positions.index')
                ->waitFor('div.tab-content div.active')->clickLink($program->name)
                ->pause(250)->assertSee($staffposition->name)
                ->assertSee($staffposition->compensationlevel->name)
                ->assertSee(number_format($staffposition->compensationlevel->max_compensation, 2))
                ->check('delete-' . $staffposition->id)->clickLink($newprogram->name)
                ->pause(250)->assertSee('No positions found')
                ->select('program_id', $newposition->program_id)->type('name', $newposition->name)
                ->select('compensationlevel_id', $newposition->compensationlevel_id)
                ->select('pctype', $newposition->pctype)->click('button[type="submit"]')
                ->waitFor('div.alert')->assertVisible('div.alert-success')->clickLink($program->name)->pause(250)
                ->assertSee('No positions found')->clickLink($newprogram->name)->pause(250)
                ->assertSee($newposition->name)->assertSee($newposition->compensationlevel->name)
                ->assertSee(number_format($newposition->compensationlevel->max_compensation, 2));
        });

        $this->assertDatabaseHas('staffpositions', ['program_id' => $staffposition->program_id,
            'name' => $staffposition->name, 'compensationlevel_id' => $staffposition->compensationlevel_id,
            'end_year' => self::$year->year - 1]);
        $this->assertDatabaseHas('staffpositions', ['program_id' => $newposition->program_id,
            'name' => $newposition->name, 'compensationlevel_id' => $newposition->compensationlevel_id,
            'pctype' => $newposition->pctype, 'start_year' => self::$year->year]);
    }
}
