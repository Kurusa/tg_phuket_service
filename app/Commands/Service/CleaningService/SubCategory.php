<?php

namespace App\Commands\Service\CleaningService;

use App\Commands\BaseCommand;
use App\Models\ServiceOrder;
use App\Models\ServiceList;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class SubCategory extends BaseCommand
{

    function processCommand()
    {
        foreach ($this->text['cleaning_services'] as $key => $text) {
            $buttons[] = [$key];
        }
        $buttons[] = [$this->text['main_menu']];

        $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['select_subcategory'], new ReplyKeyboardMarkup($buttons, false, true));
    }

}