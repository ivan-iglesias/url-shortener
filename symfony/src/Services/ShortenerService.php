<?php

namespace App\Services;

class ShortenerService
{
    /**
     * Para añadir nuevas estrategias se añade el nombre de la clase en el array. Dichas estrategias
     * deben de estar situadas en la carpeta ./Strategies. Si la estrategia pasada por argumento al
     * constructor no esta en el array, se usara la primera por defecto.
     */
    private const STRATEGIES = [
        'UrlToNumber',
        'UrlToAlphanumeric'
    ];

    protected $strategy;

    public function __construct(string $strategyName)
    {
        $strategyToUse = $this->SetStrategy(trim(strtolower($strategyName)));

        $this->strategy = new $strategyToUse();
    }

    /**
     * Establezco la estrategia a usar.
     */
    private function SetStrategy(string $strategyName)
    {
        $strategyToUse = '';

        foreach (self::STRATEGIES as $strategy) {
            if ($strategyName === trim(strtolower($strategy))) {
                $strategyToUse = $strategy;
                break;
            }
        }

        if ($strategyToUse === '') {
            $strategyToUse = self::STRATEGIES[0];
        }

        return "App\\Services\\Strategies\\" . $strategyToUse;
    }

    /**
     * Obtengo la url acortada.
     */
    public function GetShortUrl(string $url): string
    {
        return $this->strategy->Process($url);
    }
}
