<?php

namespace App\Utils;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use QrCode as Code;

class QrCode
{

    /**
     * Create a QR code image from the given URL.
     *
     * @param string $url The URL to be encoded in the QR code
     * @return string The QR code image string
     */
    public static function createQrCodeImage($url)
    {
        // Generate the QR code image string
        return Code::size(512)
            ->format('png')
            ->errorCorrection('M')
            ->generate($url);
    }
}
