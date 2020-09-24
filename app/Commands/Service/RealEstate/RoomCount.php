<?php

namespace App\Commands\Service\RealEstate;

use App\Commands\BaseCommand;
use App\Commands\Service\Auto\RentStart;
use App\Models\ServiceOrder;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class RoomCount extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::SELECT_REAL_ESTATE_ROOM_COUNT) {
            ServiceOrder::where('user_id', $this->user->id)->where('status', 'NEW')->update([
                'room_count' => $this->update->getMessage()->getText()
            ]);
            $this->triggerCommand(RentStart::class);
        } else {
            $this->user->status = UserStatusService::SELECT_REAL_ESTATE_ROOM_COUNT;
            $this->user->save();

            $buttons[] = ['1 спальня', '2 спальня', '3+ спальня'];
            $buttons[] = [$this->text['main_menu']];

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['select_estate_class'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}