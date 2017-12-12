<?php

namespace FSR\Custom;

use FSR\Cso;

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
}
