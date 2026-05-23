<?php

namespace App\Http\Controllers\Share;

use App\Http\Controllers\Controller;
use App\Models\NoteShareLink;
use Illuminate\Http\Request;

class NoteShareController extends Controller
{
    public function show(Request $request, string $token)
    {
        $share = NoteShareLink::query()
            ->with('user:id,name,email')
            ->where('token', $token)
            ->firstOrFail();

        if ($share->isRevoked() || $share->isExpired()) {
            abort(410, 'Bu paylaşım bağlantısı artık aktif değil.');
        }

        if ($share->visibility === 'private') {
            $viewer = $request->user();
            if (! $viewer || $viewer->id !== $share->user_id) {
                abort(403, 'Bu paylaşım bağlantısı özel olarak işaretlenmiş.');
            }
        }

        $share->increment('access_count');
        $share->forceFill(['last_accessed_at' => now()])->save();

        return view('share.notes', [
            'share' => $share,
            'payload' => $share->payload ?? [],
        ]);
    }
}
