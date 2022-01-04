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

    public function elision(string $name, string $article): string
    {
        // Définit les lettres devant lesquelles faire l'élision
        $letter = ['h', 'a', 'e', 'i', 'o', 'u'];

        // Récupère la première lettre du nom à traiter
        $firstLetter = strtolower(substr($name, 0, 1));

        // Si la première lettre est un « Y », et que la deuxième lettre n'est pas une voyelle
        // (ex: « Yves », contrairement à « Yohan » )
        // alors on ajoute le « Y » dans la liste des lettres devant lesquelles on doit faire l'élision
        // (ex: « d'Yves », contrairement à « de Yohan » )
        if ($firstLetter === 'y' && !in_array(strtolower(substr($name, 1, 1)), $letter)) {
            $letter[] = "y";
        }

        // Verifie si la première lettre du nom fait partie de la liste des lettres devant lesquelles on doit faire l'élision
        $elision = in_array($firstLetter, $letter);

        // Si la première lettre du nom fait partie de la liste des lettres devant lesquelles on doit faire l'élision :
        // supprime la denière lettre de l'article et la remplace par un apostrophe (ex: « de » devient « d' »)
        // sinon, on ajoute juste un espace après cet article (ex: « de » devient « de  »)
        $str = $elision ? sprintf("%s'", substr($article, 0, -1)) : sprintf("%s ", $article);

        // renvoie le résultat
        return $str;
    }
}