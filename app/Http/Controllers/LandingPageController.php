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
            'pages' => ['main' => $request->pages, 'thanks' => []],
            'draft' => ['main' => $request->pages, 'thanks' => []],
            'unpublished_changes' => false,
        ]);

        return new LandingPageResource($landingPage->refresh());
    }

    public function destroy($uuid)
    {
        //
    }
}
