<?php

namespace Tests\Feature;

use App\Models\HeadOfFamily;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeadOfFamilyTest extends TestCase
{
    use RefreshDatabase;

    public function test_read_head_of_families_data(): void
    {
        User::factory()->count(10)->create()->each(function ($user) {
            HeadOfFamily::factory()->create([
                'user_id' => $user->id,
            ]);
        });

        $response = $this->getJson('/api/head-of-families?limit=5');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ])
            ->assertJsonCount(5, 'data');
    }
}
