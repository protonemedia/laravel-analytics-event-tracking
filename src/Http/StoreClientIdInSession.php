<?php

namespace ProtoneMedia\AnalyticsEventTracking\Http;

use Illuminate\Http\Request;

class StoreClientIdInSession
{
    public function __invoke(Request $request, ClientIdSession $clientIsSession)
    {
        $data = $request->validate(['id' => 'required']);

        $clientIsSession->update($data['id']);

        return response()->json();
    }
}
