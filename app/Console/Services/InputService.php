<?php

namespace App\Console\Services;

class InputService
{
    public function ask(string $label): string
    {
        $value = null;
        while (!$value) {
            $value = $this->ask($label);
        }

        return $value;
    }

    public function askForNumber(string $label)
    {
        $value = null;
        while (!$value || !is_numeric($value)) {
            $value = $this->ask($label);
        }

        return $value;
    }
}
