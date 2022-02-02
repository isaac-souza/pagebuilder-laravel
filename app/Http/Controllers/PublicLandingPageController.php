<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\LandingPage;
use App\Http\Resources\LandingPageResource;

class PublicLandingPageController extends Controller
{
    public function show($slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->first();

        if(is_null($landingPage))
        {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        return new LandingPageResource($landingPage->refresh());
    }
}
