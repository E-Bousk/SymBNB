<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('elision', [$this, 'elision']),
        ];
    }

    public function elision(string $premiereLettre, string $article): string
    {
        $voyelle = ['a', 'e', 'i', 'o', 'u', 'y'];

        $elision = in_array($premiereLettre, $voyelle);

        $str = $elision ? sprintf("%s'", substr($article, 0, -1)) : sprintf("%s ",$article);

        return $str;
    }
}