<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

/**
 * @property array $attributes
 */
trait CanSaveFile
{
    protected $options = [
        "root_folder" => "public",
    ];

    /**
     * Save file to storage and set the attribute value.
     *
     * @param string $attribute
     * @param mixed $value
     * @param string $path
     * @param string|null $extension
     * @return void
     */
    public function saveFile($attribute, $value, $path, $extension = null)
    {
        // If the value is empty, do nothing
        if (!$value) {
            return;
        }

        // If the value is an instance of UploadedFile, save it to storage with a unique filename
        if ($value instanceof UploadedFile) {
            $extension = $extension ?? $value->extension();

            $filename = Uuid::uuid4()->toString() . '-' . uniqid() . ".{$extension}";

            Storage::putFileAs(trim($path, '/'), $value, $filename);

            // Set the attribute value to the filename
            $this->attributes[$attribute] = $filename;
        } else {
            // If the value is not an instance of UploadedFile, set the attribute value to the given value
            $this->attributes[$attribute] = $value;
        }
    }

    /**
     * Return the full path where the file will be saved
     *
     * @return string
     */
    public function fullPath() {
        return $this->options['root_folder'];
    }
}
