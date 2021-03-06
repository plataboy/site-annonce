<?php

namespace App\Twig;

use Symfony\Component\Validator\Constraints\Length;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('toEuro', [$this, 'toEuro']),
            new TwigFilter('subCaractere', [$this, 'subCaractere'])
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('toEuri', [$this, 'toEuri']),
        ];
    }

    public function subCaractere($string, int  $i, int $LengthString)
    {
        if (strlen($string) > $LengthString) {
            return   substr($string,  $i,  $LengthString) . '...';
        } else
            return   substr($string, $i, $LengthString);
    }

    public function toEuro($value)
    {
        return number_format($value, 0, ',', ' ') . ' €';
    }
}
