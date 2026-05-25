<?php

namespace Tests\Feature;

use App\Models\ResearchNote;
use App\Models\ResearchTag;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_pull_payload(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-device')->plainTextToken;
        $this->createActiveSubscription($user);

        ResearchTag::query()->create([
            'user_id' => $user->id,
            'name' => 'Akide',
            'slug' => 'akide',
        ]);

        UserSetting::query()->create([
            'user_id' => $user->id,
            'preferred_language' => 'tr',
            'preferred_arabic_font' => 'amiri',
        ]);

        $this->withToken($token)
            ->getJson('/api/sync/pull')
            ->assertOk()
            ->assertJsonPath('payload.user_id', $user->id)
            ->assertJsonStructure([
                'payload' => [
                    'schema_version',
                    'exported_at',
                    'user_id',
                    'record_counts',
                    'data',
                ],
            ]);
    }

    public function test_authenticated_user_can_push_payload(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-device')->plainTextToken;
        $this->createActiveSubscription($user);

        $payload = [
            'schema_version' => 1,
            'data' => [
                'research_tags' => [
                    ['id' => 1, 'name' => 'Tefsir', 'slug' => 'tefsir'],
                ],
                'research_notes' => [
                    [
                        'id' => 10,
                        'sura' => 1,
                        'aya' => 1,
                        'word_position' => null,
                        'type' => 'note',
                        'title' => 'Aciklama',
                        'content' => 'Icerik',
                        'tag_ids' => [1],
                    ],
                ],
                'note_share_links' => [],
                'bookmarks' => [],
                'meal_preferences' => [],
                'setting' => [
                    'preferred_language' => 'tr',
                    'preferred_arabic_font' => 'uthmani',
                    'preferred_tafsir_id' => null,
                    'preferred_tafsir_name' => null,
                    'last_read_sura' => 2,
                    'last_read_aya' => 5,
                ],
            ],
        ];

        $this->withToken($token)
            ->postJson('/api/sync/push', ['payload' => $payload])
            ->assertOk()
            ->assertJsonPath('restored_counts.research_notes', 1)
            ->assertJsonPath('restored_counts.research_tags', 1)
            ->assertJsonPath('restored_counts.setting', 1);

        $this->assertDatabaseHas('research_tags', [
            'user_id' => $user->id,
            'name' => 'Tefsir',
        ]);

        $this->assertDatabaseHas('research_notes', [
            'user_id' => $user->id,
            'sura' => 1,
            'aya' => 1,
            'title' => 'Aciklama',
        ]);

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $user->id,
            'preferred_arabic_font' => 'uthmani',
            'last_read_sura' => 2,
            'last_read_aya' => 5,
        ]);
    }

    public function test_guest_cannot_access_sync_endpoints(): void
    {
        $this->getJson('/api/sync/pull')->assertUnauthorized();
        $this->postJson('/api/sync/push', [])->assertUnauthorized();
    }

    public function test_authenticated_user_without_active_subscription_cannot_access_sync_endpoints(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-device')->plainTextToken;

        $this->withToken($token)->getJson('/api/sync/pull')->assertStatus(402);
        $this->withToken($token)->postJson('/api/sync/push', [
            'payload' => ['schema_version' => 1, 'data' => []],
        ])->assertStatus(402);
    }

    private function createActiveSubscription(User $user): void
    {
        UserSubscription::query()->create([
            'user_id' => $user->id,
            'plan_code' => 'yearly',
            'status' => 'active',
            'starts_at' => now()->subMinute(),
            'ends_at' => now()->addMonth(),
            'meta' => [],
        ]);
    }
}
