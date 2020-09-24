<?php

namespace App\Commands\Service\RealEstate;

use App\Commands\BaseCommand;
use App\Models\ServiceOrder;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class RentPeriod extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::SELECT_REAL_ESTATE_RENT_PERIOD) {
            ServiceOrder::where('user_id', $this->user->id)->where('status', 'NEW')->update([
                'rent_period' => $this->update->getMessage()->getText()
            ]);
            $this->triggerCommand(RoomCount::class);
        } else {
            $this->user->status = UserStatusService::SELECT_REAL_ESTATE_RENT_PERIOD;
            $this->user->save();

            $buttons[] = ['1-6 дней', '7-21 день', '1месяц, +'];
            $buttons[] = [$this->text['main_menu']];

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['select_car_rent_period'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}