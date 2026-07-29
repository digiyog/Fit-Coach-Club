<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

trait UploadImage
{
    /**
	 * Upload image.
	 */
    private function uploadImage($file, $path = null, $compression = null, $name = null, $thumbSize = 100)
    {
		$image_uploaded = false;

    	// File system
		$file_system = config('filesystems.default');
		//------------

		// Generate image name and get extension
		$extension        = $file->getClientOriginalExtension();
		$file_name        = $this->generateImageName($extension, $name);
		$destination_path = $path.$file_name;
		//--------------------------------------

		// Upload image
		try {
			if (! is_null($compression)) {

                if( $extension != 'svg'){
                    // Compress image
                    $image = Image::make($file)->encode($extension, $compression);
                    //---------------

                    // Upload image
                    $image_uploaded = Storage::disk($file_system)->put($destination_path, (string) $image);
                    //-------------

					$imageThumb = $this->createThumb($file, $thumbSize, $path.'thumb', $file_name);
                } else {

                    // Upload image
                    $image = Storage::disk($file_system)->putFileAs(rtrim($path, '/'), $file, $file_name);
                    //-------------

                    // Upload image
                    $imageThumb = Storage::disk($file_system)->putFileAs(rtrim($path, 'thumb'), $file, $file_name);
                    //-------------
                }

                if (! is_null($image)) {
					$image_uploaded = true;
				}

			} else {
				if( $extension != 'svg'){
					// Upload image
					$image = Storage::disk($file_system)->putFileAs(rtrim($path, '/'), $file, $file_name);
					//-------------

					$imageThumb = Storage::disk($file_system)->putFileAs(rtrim($path, 'thumb'), $file, $file_name);
				} else {
					// Upload image
					$image = Storage::disk($file_system)->putFileAs(rtrim($path, '/'), $file, $file_name);
					//-------------

					$imageThumb = Storage::disk($file_system)->putFileAs(rtrim($path, 'thumb'), $file, $file_name);
				}

				if (! is_null($image)) {
					$image_uploaded = true;
				}
			}
		} catch (\Exception $e) {
			//
		}
		//-------------

		// Set data
		if ($image_uploaded) {
			$data = [
				'_status'  => true,
				'_message' => __('messages.image_uploaded'),
				'_data'    => $file_name
			];
		} else {
			$data = [
				'_status'  => false,
				'_message' => __('messages.image_uploading_failed'),
				'_data'    => null
			];
		}
		//---------

		return $data;
    }

	/**
	 * Generate image name.
	 *
	 * @param  string  $extension
	 * @return string
	 */
	private function generateImageName($extension, $name)
	{
	    $random = rand(1000, 9999); // 4-digit random number

	    if (!is_null($name)) {
	        $name = str_replace(' ', '_', $name);
	        return $name . '-' . $random . '-' . time() . '.' . $extension;
	    } else {
	        return Str::uuid() . '-' . $random . '-' . time() . '.' . $extension;
	    }
	}


	public function createThumb($image, $thumbSize = 100, $thumbPath, $imageName)
	{
		// File system
		$file_system = config('filesystems.default');
		//------------

		// Create thumb directory if not exists
		if(!Storage::disk($file_system)->exists($thumbPath))
		{
			Storage::makeDirectory($thumbPath);
		}

		$image = Image::make($image->path());
		$image = $image->resize($thumbSize, $thumbSize, function($constraint){
			$constraint->aspectRatio();
		})->save(storage_path('app/public/'.$thumbPath . '/' . $imageName));

		return $image;
	}
}
