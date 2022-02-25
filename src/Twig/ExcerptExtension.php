<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ExcerptExtension extends AbstractExtension {
    public function getFilters()
    {
        return new TwigFilter("excerpt", [$this, 'getExcerpt'], ['is_safe' => ['html']]);
    }

    public function getExcerpt(): string{
        return substr($this->content, 0, 200) . ' ... ';
    }
}