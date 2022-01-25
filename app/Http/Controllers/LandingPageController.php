<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\LandingPage;
use App\Http\Resources\LandingPageResource;

class LandingPageController extends Controller
{
    public function index()
    {
        return LandingPageResource::collection(account()->landingPages);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($uuid)
    {
        $landingPage = LandingPage::find($uuid);

        if(is_null($landingPage))
        {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        return new LandingPageResource($landingPage);
    }

    public function update(Request $request, $uuid)
    {
        $landingPage = LandingPage::find($uuid);

        if(is_null($landingPage))
        {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        $landingPage->update([
            'pages' => ['mainPage' => $request->pages],
            'unpublished_changes' => false,
        ]);

        return response()->json([], Response::HTTP_OK);
    }

    public function destroy($uuid)
    {
        //
    }
}
