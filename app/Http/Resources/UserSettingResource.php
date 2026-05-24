<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\UserSetting */
class UserSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'preferred_language' => $this->preferred_language,
            'preferred_arabic_font' => $this->preferred_arabic_font,
            'preferred_tafsir_id' => $this->preferred_tafsir_id,
            'preferred_tafsir_name' => $this->preferred_tafsir_name,
            'last_read_sura' => $this->last_read_sura,
            'last_read_aya' => $this->last_read_aya,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
