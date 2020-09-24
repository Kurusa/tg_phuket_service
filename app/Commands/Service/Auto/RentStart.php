<?php

namespace App\Commands\Service\Auto;

use App\Commands\BaseCommand;
use App\Commands\Service\ServiceDone;
use App\Models\ServiceOrder;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardRemove;

class RentStart extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::SELECT_AUTO_SERVICE_RENT_START) {
            ServiceOrder::where('user_id', $this->user->id)->where('status', 'NEW')->update([
                'rent_start' => $this->update->getMessage()->getText()
            ]);
            $this->triggerCommand(ServiceDone::class);
        } else {
            $this->user->status = UserStatusService::SELECT_AUTO_SERVICE_RENT_START;
            $this->user->save();

            $buttons[] = [$this->text['main_menu']];
            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['select_car_rent_start'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}