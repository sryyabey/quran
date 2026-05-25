<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BackupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function __construct(private readonly BackupService $backupService)
    {
    }

    public function pull(Request $request): JsonResponse
    {
        $payload = $this->backupService->exportPayload($request->user());

        return response()->json([
            'payload' => $payload,
        ]);
    }

    public function push(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'payload' => ['required', 'array'],
            'payload.schema_version' => ['nullable', 'integer'],
            'payload.data' => ['required', 'array'],
        ]);

        $restoredCounts = $this->backupService->importPayload(
            $request->user(),
            $validated['payload']
        );

        return response()->json([
            'message' => 'Sync payload applied successfully.',
            'restored_counts' => $restoredCounts,
        ]);
    }
}
