<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;

trait ImageUploadTrait
{
    /**
     * Process an image (resize, compress to webp) and store it.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param string $prefix
     * @return string The generated file path
     */
    protected function processAndStoreImage($file, $path, $prefix = '')
    {
        // Generate filename
        $name = $prefix ? $prefix . '_' : '';
        $name .= time() . '_' . Str::random(5) . '.webp';
        
        $fullPath = rtrim($path, '/') . '/' . $name;
        
        // Process image
        $image = Image::decode($file)
            ->scaleDown(width: 1080)
            ->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: 80));
            
        // Save to S3 disk directly as requested by user
        $success = Storage::disk('s3')->put($fullPath, (string) $image, 'public');
        
        if (!$success) {
            throw new \Exception("Error al subir la imagen al bucket S3. Verifica tus credenciales (AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, etc) en el archivo .env.");
        }
        
        return $fullPath;
    }
}
