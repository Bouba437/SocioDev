<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BootstrapExtension extends AbstractExtension {
    public function getFilters()
    {
        return [
            new TwigFilter("badge", [$this, 'badgeFilter'], ['is_safe' => ['html']]),
            new TwigFilter('booleanBadge', [$this, 'booleanBadgeFilter'], ['is_safe' => ['html']])
        ];
    }

    public function badgeFilter($content, array $options = []) : string {
        $defaultOptions = [
            'color' => 'primary',
            'rounded' => false,
        ];

        $options = array_merge($defaultOptions, $options);

        $color = $options['color'];
        $pill = $options['rounded'] ? " rounded-pill" : "";

        $template = '<span class="badge bg-%s %s">%s</span>';

        return sprintf($template, $color, $pill, $content);

        // return '<span class="badge bg-'.$color.' ' . $pill . '">' . $content . '</span>';
    }

    public function booleanBadgeFilter(bool $content, array $options = []) : string {
        $defaultOptions = [
            'trueText' => 'oui',
            'falseText' => 'non',
        ];

        $options = array_merge($defaultOptions, $options);

        if($content) {
            return $this->badgeFilter($options['trueText']);
        } else {
            return $this->badgeFilter($options['falseText'], ['color' => 'danger']);
        }
    }
}