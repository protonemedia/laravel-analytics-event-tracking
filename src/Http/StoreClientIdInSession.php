<?php

namespace ProtoneMedia\AnalyticsEventTracking\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreClientIdInSession
{
    /**
     * Stores the posted Client ID in the session.
     */
    public function __invoke(Request $request, ClientIdSession $clientIdSession): JsonResponse
    {
        $data = $request->validate(['id' => 'required|string|max:255']);

        $clientIdSession->update($data['id']);

        return response()->json();
    }
}
