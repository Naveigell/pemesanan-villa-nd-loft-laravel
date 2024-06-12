<?php

namespace App\Traits;

/**
 * @method getAttributes()
 */
trait HasOriginalAttributes
{
    public $originalAttributes = [];

    public function saveOriginalAttributes()
    {
        $this->originalAttributes = $this->getAttributes();
    }
}
