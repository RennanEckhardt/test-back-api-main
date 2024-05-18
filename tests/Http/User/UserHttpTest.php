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
use Illuminate\Support\Facades\DB; 



class UserHttpTest extends TestCase
{
    use DatabaseTransactions;

    private $faker;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

    public function testShouldCorrectlyReturnUserWithEligibilityData(): void
    {
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
        $response->assertStatus(500);
        $response->assertSeeText('The user id is not valid');
    }

    public function testShouldSoftDeleteUser(): void
    {
        $user = User::factory()->create();
        
        $response = $this->call('DELETE', "/user/{$user->uuid}/user");

        $response->assertStatus(200); 

        $response->assertJsonStructure([
            'id',
            'cpf'
        ]);
        $deletedUser = DB::table('user')->where('uuid', $user->uuid)->first();
        $this->assertNotNull($deletedUser->deleted_at);
    }


    public function testShouldSoftDeleteReturnErrorWhenUserNotFound(): void
    {
        $response = $this->call('DELETE', "/user/non-existent-uuid/user");

        $response->assertStatus(500); 

        $response->assertSeeText('The user id is not valid');
    }
    
}
