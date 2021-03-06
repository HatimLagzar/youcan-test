<?php

namespace App\Console\IOHelpers;

use Illuminate\Console\Command;

class InputHelper
{
    public function ask(Command $command, string $label): string
    {
        $value = null;
        while (!$value) {
            $value = filter_var($command->ask($label), FILTER_SANITIZE_STRING);
        }

        return $value;
    }

    public function askForNumber(Command $command, string $label)
    {
        $value = null;
        while (!$value || !is_numeric($value)) {
            $value = $command->ask($label);
        }

        return $value;
    }
}
