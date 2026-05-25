<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerseTranslationsDatasetDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_download_dataset(): void
    {
        $this->getJson('/api/datasets/verse-translations/download')
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_download_dataset(): void
    {
        $path = storage_path('data/verse_translations.dataset.zip');
        $createdByTest = false;

        if (! is_file($path)) {
            if (! is_dir(dirname($path))) {
                mkdir(dirname($path), 0777, true);
            }

            file_put_contents($path, 'test-content');
            $createdByTest = true;
        }

        $user = User::factory()->create();
        $token = $user->createToken('test-device')->plainTextToken;

        $response = $this->withToken($token)
            ->get('/api/datasets/verse-translations/download');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/zip');

        if ($createdByTest) {
            @unlink($path);
        }
    }
}
