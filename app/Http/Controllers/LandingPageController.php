<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\LandingPage;
use App\Http\Resources\LandingPageResource;
use App\Http\Requests\LandingPageRequest;

class LandingPageController extends Controller
{
    public function index()
    {
        return LandingPageResource::collection(account()->landingPages()->latest()->get());
    }

    public function store(LandingPageRequest $request)
    {
        $landingPage = account()->landingPages()->create(array_merge(
            $request->validated(),
            [
                'type' => 'sales',
                'pages' => [
                    'main' => [],
                    'thanks' => [],
                ],
                'draft' => [
                    'main' => [],
                    'thanks' => [],
                ],
            ]
        ));

        return new LandingPageResource($landingPage);
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
        ]);

        return new LandingPageResource($landingPage->refresh());
    }

    public function destroy($uuid)
    {
        $landingPage = LandingPage::find($uuid);

        abort(404);

        if(is_null($landingPage))
        {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        if($landingPage->account_uuid != account()->uuid)
        {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        $landingPage->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
