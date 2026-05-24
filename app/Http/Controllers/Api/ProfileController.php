<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Http\Requests\Api\Profile\UpdateProfileSettingsRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSettingResource;
use App\Models\UserMealPreference;
use App\Models\UserSetting;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        $user = auth()->user()->loadMissing('roles');

        return response()->json([
            'user' => new UserResource($user),
        ]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => new UserResource($user->load('roles')),
        ]);
    }

    public function settings(): JsonResponse
    {
        $user = auth()->user();

        $setting = UserSetting::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['preferred_language' => 'tr', 'preferred_arabic_font' => 'amiri']
        );

        $selectedMealKeys = UserMealPreference::query()
            ->where('user_id', $user->id)
            ->where('language', $setting->preferred_language)
            ->orderBy('meal_key')
            ->pluck('meal_key')
            ->values();

        return response()->json([
            'settings' => new UserSettingResource($setting),
            'selected_meal_keys' => $selectedMealKeys,
        ]);
    }

    public function updateSettings(UpdateProfileSettingsRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $setting = UserSetting::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'preferred_language' => $validated['preferred_language'],
                'preferred_arabic_font' => $validated['preferred_arabic_font'] ?? 'amiri',
                'preferred_tafsir_id' => $validated['preferred_tafsir_id'] ?? null,
                'preferred_tafsir_name' => $validated['preferred_tafsir_name'] ?? null,
                'last_read_sura' => $validated['last_read_sura'] ?? null,
                'last_read_aya' => $validated['last_read_aya'] ?? null,
            ]
        );

        if (array_key_exists('selected_meal_keys', $validated)) {
            UserMealPreference::query()
                ->where('user_id', $user->id)
                ->where('language', $validated['preferred_language'])
                ->delete();

            foreach (array_values(array_unique($validated['selected_meal_keys'])) as $mealKey) {
                UserMealPreference::query()->create([
                    'user_id' => $user->id,
                    'language' => $validated['preferred_language'],
                    'meal_key' => $mealKey,
                ]);
            }
        }

        $selectedMealKeys = UserMealPreference::query()
            ->where('user_id', $user->id)
            ->where('language', $setting->preferred_language)
            ->orderBy('meal_key')
            ->pluck('meal_key')
            ->values();

        return response()->json([
            'message' => 'Profile settings updated successfully.',
            'settings' => new UserSettingResource($setting),
            'selected_meal_keys' => $selectedMealKeys,
        ]);
    }
}
