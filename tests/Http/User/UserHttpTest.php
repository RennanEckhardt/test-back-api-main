<?php

namespace Tests\Http\User;

use App\Http\Controllers\User\UserController; 
use App\Models\User;
use Tests\TestCase;
use Faker\Factory as Faker;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder; 
use Illuminate\Support\Facades\Artisan; 
use Laravel\Lumen\Testing\DatabaseTransactions; 


class UserHttpTest extends TestCase
{
    use DatabaseTransactions;

    private $faker;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        //Artisan::call('db:seed', ['--class' => DatabaseSeeder::class]); 
    }

    ////feat:Dividir os metodos
    public function testShouldCorrectlyReturnUserWithEligibilityData(): void
    {
        ////feat:privar eligbleuser tirar redundancia
        $eligibleUser = User::factory()->create([
            'created_at' => Carbon::now()->subMonths(7), 
        ]);
        $response = $this->call('GET', "/user/{$eligibleUser->uuid}/user");

        $response->assertStatus(200);
        
        $response->assertJson([
            'uuid' => $eligibleUser->uuid,
            'name' => $eligibleUser->name,
            'email' => $eligibleUser->email,
            'cpf' => $eligibleUser->cpf,
            'eligible_for_loan' => true, 
        ]);

        $ineligibleUser = User::factory()->create([
            'created_at' => Carbon::now()->subDays(5), 
        ]);

        $response = $this->call('GET', "/user/{$ineligibleUser->uuid}/user"); 

        $response->assertJson([
            'uuid' => $ineligibleUser->uuid,
            'eligible_for_loan' => false,
        ]);

        $response = $this->call('GET', '/user/{145754}/user');
        $response->assertStatus(400);
        $response->assertJson([
            'bad_request' => 'Usuário não encontrado'
        ]);
    }
}
