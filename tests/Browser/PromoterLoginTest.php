<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Keyboard;
use Tests\DuskTestCase;

class PromoterLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function logging_in_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password')
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'john@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->pause(1000) //need to wait response time
                ->assertPathIs('/backstage/concerts');
        });
    }

    /** @test */
    public function logging_in_with_invalid_credential(): void
    {
        User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('password')
        ]);



        // Auth::logout(); //to make sure next test has not auth value
        $this->browse(function (Browser $browser) {
            $browser->deleteCookie('laravel_session');
            $browser->visit('/login')
                ->type('email', 'jane@example.com')
                ->type('password', 'wrong-password')
                ->press('LOG IN')
                ->pause(1000) //need to wait response time
                ->assertPathIs('/login')
                ->pause(1000)
                ->assertSee('credential does not match');
        });
    }
}
