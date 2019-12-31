<?php

namespace App\Services;

interface ShortenerInterface
{
    /**
     * Procesa una url para acortarla.
     *
     * @return bool
     */
    public function Process(string $urllong): string;
}
