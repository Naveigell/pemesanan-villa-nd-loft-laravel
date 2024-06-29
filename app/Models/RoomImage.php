<?php

namespace App\Models;

use App\Traits\CanSaveFile;
use App\Traits\HasOriginalAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    use HasFactory, CanSaveFile, HasOriginalAttributes;

    protected $fillable = [
        'room_id',
        'image',
    ];

    public function getImageUrlAttribute()
    {
        if (file_exists(storage_path("app/{$this->fullPath()}/{$this->image}"))) {
            return asset("storage/rooms/{$this->image}");
        }

        return config('default_values.missing_image');
    }

    /**
     * Return the full path where the file will be saved
     *
     * @return string
     */
    public function fullPath()
    {
        return $this->options['root_folder'] . '/rooms';
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function deleteImage()
    {
        @unlink(storage_path("app/{$this->fullPath()}/{$this->originalAttributes['image']}"));
    }
}
