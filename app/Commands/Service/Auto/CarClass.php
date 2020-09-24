<?php

namespace App\Commands\Service\Auto;

use App\Commands\BaseCommand;
use App\Models\ServiceOrder;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class CarClass extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::SELECT_AUTO_SERVICE_CLASS) {
            ServiceOrder::where('user_id', $this->user->id)->where('status', 'NEW')->update([
                'class' => $this->update->getMessage()->getText()
            ]);
            $this->triggerCommand(RentPeriod::class);
        } else {
            $this->user->status = UserStatusService::SELECT_AUTO_SERVICE_CLASS;
            $this->user->save();

            $buttons[] = ['Эконом', 'Стандарт', 'Премиум'];
            $buttons[] = [$this->text['main_menu']];

            $service = ServiceOrder::where('user_id', $this->user->id)->where('status', 'NEW')->get();
            if ($service[0]->subcategory == 'Авто') {
                $text = $this->text['select_car_class'];
            } else {
                $text = $this->text['select_moto_class'];
            }
            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $text, new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}