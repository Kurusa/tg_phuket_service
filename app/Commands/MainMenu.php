<?php

namespace App\Commands;

use App\Models\Record;
use App\Models\ServiceOrder;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class MainMenu extends BaseCommand
{

    function processCommand()
    {
        if ($this->update->getCallbackQuery()) {
            $this->getBot()->deleteMessage($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId());
        }
        Record::where('user_id', $this->user->id)->where('status', 'NEW')->delete();
        ServiceOrder::where('user_id', $this->user->id)->where('status', 'NEW')->delete();

        $this->user->status = UserStatusService::DONE;
        $this->user->save();

        $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, 'Что умеет этот бот?
Приветствуем тебя ' . $this->user->first_name . '!
@PhuketService_bot — качественные услуги и сервисы Пхукета

* Доска объявлений
* Авто / Мото
* Ктера / Яхти
* Клининг сервис вилл
* Аренда недвижимости
* Инвестиции на Пхукете
* Юредические услуги / Визы', new ReplyKeyboardMarkup([
            [$this->text['create_record'], $this->text['services']],
        ], false, true));
        $this->getBot()->sendSticker($this->user->chat_id, new \CURLFile(__DIR__ . '/../src/phuket.jpg'));
    }

}