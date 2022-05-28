<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TextExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('excerpt', [$this, 'excerpt']),
            new TwigFilter('excerpts', [$this, 'excerpts']),
            new TwigFilter('ago', [$this, 'ago'], ['is_safe' => ["html"]])
        ];
    }



    public function excerpt(string $value, int $maxLength = 110): string
    {
        if (mb_strlen($value) > $maxLength) {

            $excerpt = mb_substr($value, 0, $maxLength);
            $lastespace = mb_strrpos($excerpt, ' ');
            return mb_substr($excerpt, 0, $lastespace) . "...";
        }
    }
    public function excerpts(string $value, int $maxLength = 500): string
    {
        if (mb_strlen($value) > $maxLength) {

            $excerpt = mb_substr($value, 0, $maxLength);
            $lastespace = mb_strrpos($excerpt, ' ');
            return mb_substr($excerpt, 0, $lastespace) . "...";
        }
        return $value;
    }
    public function ago(DateTime $date, string $format = "d/m/Y h:i")
    {
        return     '<span class="timeago" datetime="' . $date->format(DateTime::ISO8601) . '">' . $date->format($format) . '</span>';
    }
}
