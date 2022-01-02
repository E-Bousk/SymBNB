<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{
    public function transform($dateTime)
    {
        if ($dateTime === null) {
            return '';
        }

        return $dateTime->format('d/m/Y');
    }


    public function reverseTransform($frenchDate)
    {
        if ($frenchDate === null) {
            throw new TransformationFailedException("Vous devez fournir une date");
        }

        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        if ($date === false) {
            throw new TransformationFailedException("Le format de la date n'est pas bon");
        }

        return $date;
    }

}
