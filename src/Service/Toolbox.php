<?php

namespace App\Service;

class Toolbox {
    public function getExcerpt(): string{
        return substr($this->content, 0, 200) . ' ... ';
    }
}