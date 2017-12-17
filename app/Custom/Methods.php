<?php

namespace FSR\Custom;

use FSR\Cso;
use Illuminate\Support\Facades\Config;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * A bundle of my own custom functions that I think will be useful to be written as methods,
 * since probably I'll be needing them in the future. Or maybe not, who knows :)
 */
class Methods
{

/**
 * Contstruct a URL for the uploaded file
 *
 * @param string $filename  - get it from the model, like this: File::first()->filename
 * @param bool $absolute    - if specified, returns an absolute path. If not, relative
 * @return string
 */
    public static function getFileUrl(string $filename, bool $absolute = false)
    {
        $path = '';

        if ($absolute) {
            $path .=config('app.url');
        }

        return $path .= '/storage' . config('app.upload_path') . '/' . $filename;
    }

    /**
     * Checks if a user is approved by the administrator
     *
     * @param Cso or Donor $user
     * @return bool
     */
    public static function isUserApproved($user)
    {
        if ($user) {
            return $user->first()->approved;
        } else {
            return false;
        }
    }


    /**
     * Crops the image to make it square
     *
     * @param string temp path to the file that was just uploaded
     * @return void
     */
    public static function fitImage($img)
    {
        $width = $img->width();
        $height = $img->height();

        //check if file is a valid image
        if ($width && $height) {
            //reduce the bigger side to be same as the smaller one
            if ($width > $height) {
                $newSize = $height;
            } else {
                $newSize = $width;
            }
        }
        if ($newSize > Config::get('constants.max_profile_image_size')) {
            $newSize = Config::get('constants.max_profile_image_size');
        }
        $img->fit($newSize);
    }

    /**
     * Converts an inputed date to a value suitable for inserting in database
     *
     * @param string $date
     * @return string
     */
    public static function convert_date_input_to_db($date)
    {
        return str_replace('T', ' ', $date);
    }
}
