<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageStoreRequest;
use App\Models\Image;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToWriteFile;

class ImageStoreController extends Controller
{
    /**
     * Gets all images data.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $images_data = Image::all();
            return response()->json([
                'images' => $images_data,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred on get the images data!',
            ], 400);
        }
    }

    /**
     * Store image.
     * @param \App\Http\Requests\ImageStoreRequest $request
     * @return JsonResponse
     */
    public function store(ImageStoreRequest $request): JsonResponse
    {
        $file = $request->file('image');
        $image_name = time() . $file->extension();
        $image_format = $file->extension();
        $image_size = $file->getSize();
        $image_resolution = getimagesize($file)[0] . 'x' . getimagesize($file)[1];
        $image_path = 'public/images';

        try {
            $image = Image::create([
                'user_id' => $request->user_id,
                'name' => $image_name,
                'format' => $image_format,
                'size' => $image_size,
                'resolution' => $image_resolution,
                'path' => $image_path
            ]);

            if ($image) Storage::putFileAs('public/images', $file, $image_name);

            return response()->json([
                'message' => 'Image data saved successfully!',
                'image' => $image,
            ], 201);
        } catch (UnableToWriteFile $e) {
            return response()->json([
                'message' => "Can't write image in disk!",
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred on save the image data!',
            ], 400);
        }
    }

    /**
     * Get specified image.
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $image_data = Image::findOrFail($id);
            $image = Storage::get('public/images/' . $image_data->name);

            if (!$image) return response()->json(['message' => 'Image not found!'], 404);

            return response()->json([
                'image_data' => $image_data,
                'image' => $image
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Image data not found!',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred on get the specified image data!',
            ], 400);
        }
    }

    /**
     * Update image.
     * @param \App\Http\Requests\ImageStoreRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(ImageStoreRequest $request, string $id): JsonResponse
    {
        $file = $request->file('image');
        $image_name = time() . $file->extension();
        $image_format = $file->extension();
        $image_size = $file->getSize();
        $image_resolution = getimagesize($file)[0] . 'x' . getimagesize($file)[1];
        $image_path = 'public/images';

        try {
            $image_data = Image::findOrFail($id);
            $image_data->update([
                'user_id' => $request->user_id,
                'name' => $image_name,
                'format' => $image_format,
                'size' => $image_size,
                'resolution' => $image_resolution,
                'path' => $image_path
            ]);

            if ($image_data) {
                Storage::delete('public/images/' . $image_data->name);
                Storage::putFileAs('public/images', $file, $image_name);
            }

            return response()->json([
                'message' => 'Image data updated successfully!',
                'image_data' => $image_data,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Image data not found!',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred on update the image data!',
            ], 400);
        }
    }

    /**
     * Delete image.
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $image = Image::findOrFail($id);
            if ($image->delete()) Storage::delete('public/images/' . $image->name);

            return response()->json([
                'message' => 'Image data deleted successfully!',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Image data not found!',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred on delete the image data!',
            ], 400);
        }
    }
}
