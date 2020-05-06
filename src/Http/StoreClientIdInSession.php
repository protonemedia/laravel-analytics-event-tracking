<?php

namespace ProtoneMedia\AnalyticsEventTracking\Http;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class StoreClientIdInSession
{
    use ValidatesRequests;

    public function __invoke(Request $request, ClientIdSession $clientIsSession)
    {
        $this->validate($request, ['id' => 'required']);

        $clientIsSession->update($request->input('id'));

        return response()->json();
    }
}
