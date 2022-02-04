<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image as InterventionImage;
use Intervention\Image\Constraint;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Models\Image;
use App\Http\Resources\ImageResource;
use App\Http\Requests\ImageUploadApiRequest;

class ImageController extends Controller
{
    public function index()
    {
        return ImageResource::collection(account()->images);
    }

    public function store(ImageUploadApiRequest $request)
    {
        try
        {
            DB::beginTransaction();

            $uuid = uuid();
            $account = account();
            $extension = $request->file->extension();
            $filename = $request->file->getClientOriginalName();
            $folder = "files/{$account->uuid}/images";

            $image = $account->images()->create([
                'uuid' => $uuid,
                'filename' => $filename,
                'extension' => $extension,
                'path' => "{$folder}/{$uuid}.{$extension}",
                'thumb_path' => "{$folder}/thumb-{$uuid}.{$extension}",
            ]);

            Storage::disk('public')->putFileAs($folder, $request->file, "{$uuid}.{$extension}");

            /** @var \Intervention\Image\Image $thumb */
            $thumb = InterventionImage::make(storage_path("app/public/{$image->path}"));

            $thumb->resize(200, 200, function (Constraint $constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $thumb->save(storage_path("app/public/{$image->thumb_path}"));

            DB::commit();

            return response()->json(new ImageResource($image), Response::HTTP_CREATED);
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(string $uuid)
    {
        $image = Image::where('account_uuid', account()->uuid)->where('uuid', $uuid)->first();

        if(is_null($image))
        {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        if(account()->uuid != $image->account_uuid)
        {
            return response()->json([], Response::HTTP_UNAUTHORIZED);
        }

        try
        {
            DB::beginTransaction();

            Storage::disk('public')->delete($image->path);
            Storage::disk('public')->delete($image->thumb_path);

            $image->delete();

            DB::commit();

            return response()->json([], Response::HTTP_NO_CONTENT);
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return response()->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
