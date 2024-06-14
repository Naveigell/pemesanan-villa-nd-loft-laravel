<?php

namespace App\Enums\Interfaces;

interface HasLabel
{
    /**
     * Determines the label of the enum
     *
     * @return string
     */
    public function label();
}
