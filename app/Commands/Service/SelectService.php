<?php

namespace App\Commands\Service;

use App\Commands\BaseCommand;
use App\Models\ServiceList;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class SelectService extends BaseCommand
{

    function processCommand()
    {
        $service_list = ServiceList::all();
        if ($this->user->status === UserStatusService::SELECT_SERVICE) {
            $service_list = require_once(__DIR__ . '/../../config/service.php');
            if ($service_list[$this->update->getMessage()->getText()]) {
                $this->triggerCommand($service_list[$this->update->getMessage()->getText()]);
            }
        } else {
            $this->user->status = UserStatusService::SELECT_SERVICE;
            $this->user->save();

            $buttons = [];
            foreach ($service_list as $service) {
                $buttons[] = [$service->title];
            }
            $buttons[] = [$this->text['main_menu']];

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['select_service'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}