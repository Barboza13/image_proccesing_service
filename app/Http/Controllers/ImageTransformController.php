<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageTransformController extends Controller
{
    public function rezise(string $id, int $width, int $height): JsonResponse
    {
        try {
            $image_data = Image::findOrFail($id);
            $image = Storage::get("{$image_data->path}/{$image_data->name}");
            $manager = ImageManager::imagick();

            if (!$image) return response()->json(['message' => 'Image not found!'], 404);

            $resized_image = $manager->read($image)
                ->resize($width, $height);

            return response()->json([
                'message' => 'Resized image!',
                'image' => $resized_image
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Image data not found!'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred on resize image!'
            ], 400);
        }
    }

    public function crop() {}
    public function rotate() {}
    public function watermark() {}
    public function flip() {}
    public function mirror() {}
    public function compress() {}
    public function changeFormat() {}
    public function applyFilters() {}
}
