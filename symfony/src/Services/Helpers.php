<?php

namespace App\Services;

class Helpers
{
    /**
     * MUY BASICO, NO VALIDO PARA PRODUCCION.
     * Detecta si se trata de un mobil u ordenador.
     */
    public static function isMobile(string $userAgent): bool
    {
        $iphone = strpos($userAgent,"iPhone");
        $android = strpos($userAgent,"Android");
        $webos = strpos($userAgent,"webOS");
        $blkberry = strpos($userAgent,"BlackBerry");
        $ipod = strpos($userAgent,"iPod");
        $ipod = strpos($userAgent,"iPad");

        return ($iphone || $android || $webos || $ipod || $blkberry)
            ? true
            : false;
    }

    /**
     * Elimino Http(s):// del nombre de la URL.
     */
    public static function removeHttpText(string $url): string
    {
        if (strpos($url, "://") !== false) {
            return explode("://", $url)[1];
        }

        return $url;
    }
}
