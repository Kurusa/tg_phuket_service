<?php

namespace App\Commands\Service\Auto;

use App\Commands\BaseCommand;
use App\Models\ServiceOrder;
use App\Models\ServiceList;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class SubCategory extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::SELECT_AUTO_SERVICE_SUBCATEGORY) {
            $service_id = ServiceList::where('title', 'Авто/Мото')->get();
            ServiceOrder::create([
                'user_id' => $this->user->id,
                'service_id' => $service_id[0]->id,
                'subcategory' => $this->update->getMessage()->getText()
            ]);
            $this->triggerCommand(CarClass::class);
        } else {
            $this->user->status = UserStatusService::SELECT_AUTO_SERVICE_SUBCATEGORY;
            $this->user->save();

            $buttons[] = ['Авто', 'Мото'];
            $buttons[] = [$this->text['main_menu']];

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['select_subcategory'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}