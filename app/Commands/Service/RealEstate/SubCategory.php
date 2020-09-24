<?php

namespace App\Commands\Service\RealEstate;

use App\Commands\BaseCommand;
use App\Models\ServiceOrder;
use App\Models\ServiceList;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class SubCategory extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::SELECT_REAL_ESTATE_SUBCATEGORY) {
            $service_id = ServiceList::where('title', 'Аренда недвижимости')->get();
            ServiceOrder::create([
                'user_id' => $this->user->id,
                'service_id' => $service_id[0]->id,
                'subcategory' => $this->update->getMessage()->getText()
            ]);
            $this->triggerCommand(EstateClass::class);
        } else {
            $this->user->status = UserStatusService::SELECT_REAL_ESTATE_SUBCATEGORY;
            $this->user->save();

            $buttons[] = ['Аренда Виллы', 'Аренда Кондо'];
            $buttons[] = [$this->text['main_menu']];

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['select_subcategory'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}