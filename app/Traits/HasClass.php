<?php

namespace App\Traits;

trait HasClass
{
    /**
     * Get the name of the called class
     *
     * @return string
     */
    public function getClassShortName()
    {
        $reflection = new \ReflectionClass($this);

        return $reflection->getShortName();
    }
}
