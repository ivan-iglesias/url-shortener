<?php

namespace App\Services\Strategies;

use App\Services\ShortenerInterface;

class UrlToAlphanumeric implements ShortenerInterface
{
    /**
     * Obtengo un string alfanumerico (en el ejemplo no uso la url original).
     */
    public function Process(string $urllong): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz';

        return substr(str_shuffle($chars), 0, 5) . mt_rand(0, 5000);
    }
}
