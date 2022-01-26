<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\LandingPage;
use App\Http\Resources\LandingPageResource;

class LandingPageDraftController extends Controller
{
    public function update(Request $request, $uuid)
    {
        $landingPage = LandingPage::find($uuid);

        if(is_null($landingPage))
        {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        $landingPage->update([
            'draft' => ['main' => $request->pages],
        ]);

        return new LandingPageResource($landingPage->refresh());
    }
}
