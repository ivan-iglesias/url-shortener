<?php

namespace App\Services\Strategies;

use App\Services\ShortenerInterface;
use Carbon\Carbon;

class UrlToNumber implements ShortenerInterface
{
    /**
     * Obtengo un string numerico (en el ejemplo no uso la url original).
     */
    public function Process(string $urllong): string
    {
        $code = Carbon::now()->format('mdhis');

        $code .= mt_rand(0, 5000);

        return $code;
    }
}
