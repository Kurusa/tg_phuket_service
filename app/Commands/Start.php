<?php

namespace App\Commands;

class Start extends BaseCommand
{

    function processCommand()
    {
        $this->triggerCommand(MainMenu::class);
    }

}