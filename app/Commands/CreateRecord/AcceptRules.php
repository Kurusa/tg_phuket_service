<?php

namespace App\Commands\CreateRecord;

use App\Commands\BaseCommand;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class AcceptRules extends BaseCommand
{

    function processCommand()
    {
        $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['rules'], new InlineKeyboardMarkup([
            [[
                'text' => $this->text['accept_rules'],
                'callback_data' => json_encode([
                    'a' => 'accept_rules'
                ])
            ]],[[
                'text' => $this->text['main_menu'],
                'callback_data' => json_encode([
                    'a' => 'main_menu',
                ])
            ]],
        ]));
    }

}