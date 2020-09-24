<?php

namespace App\Commands\Service;

use App\Commands\BaseCommand;
use App\Models\ServiceList;
use App\Models\ServiceOrder;
use App\Models\ServiceAdmin;
use App\Services\Status\UserStatusService;
use DirectoryIterator;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class ServiceDone extends BaseCommand
{

    function processCommand()
    {
        $message = '';
        $service = ServiceOrder::where('user_id', $this->user->id)->where('status', 'NEW')->get();
        $service_data = ServiceList::where('id', $service[0]->service_id)->get();
        if (!$service[0]) {
            if ($this->update->getMessage()->getText() == 'Катера/Лотки') {
                $service = ServiceOrder::create([
                    'user_id' => $this->user->id,
                    'service_id' => 2
                ]);
            }
        }

        if ($service[0]->service_id === 1) {
            $message = $service[0]->subcategory . "\n";
            $message .= 'Класс авто: ' . $service[0]->class . "\n";
            $message .= 'Период аренды: ' . $service[0]->rent_period . "\n";
            $message .= 'Дата начала аренды: ' . $service[0]->rent_start . "\n";
        } elseif ($service[0]->service_id === 3) {
            $message = $service[0]->subcategory . "\n";
            $message .= 'Класс недвижжемости: ' . $service[0]->class . "\n";
            $message .= 'Период аренды: ' . $service[0]->rent_period . "\n";
            $message .= 'Количество комнат: ' . $service[0]->room_count . "\n";
            $message .= 'Дата начала аренды: ' . $service[0]->rent_start . "\n";
        }

        // send message to admins
        $message .= 'Заказ на ' . $service_data[0]->title . ' от пользователя <a href="tg://user?id=' . $this->user->chat_id . '">' . $this->user->first_name . '</a>';
        $service_admin_list = ServiceAdmin::where('service_id', $service[0]->service_id)->get();
        foreach ($service_admin_list as $service_admin) {
            $this->getBot()->sendMessage($service_admin->admin_chat_id, $message, 'html');
        }

        $media = new \TelegramBot\Api\Types\InputMedia\ArrayOfInputMedia();
        $path = __DIR__ . '/../../src/service/' . $service_data[0]->alias;
        foreach (new DirectoryIterator($path) as $fileInfo) {
            if (!$fileInfo->isDot()) {
                $media->addItem(new InputMediaPhoto('https://olx.kurusa.uno/app/src/service/' . $service_data[0]->alias . '/' . $fileInfo->getFilename()));
            }
        }
        $this->getBot()->sendMediaGroup($this->user->chat_id, $media);

        $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['service_done'], new ReplyKeyboardMarkup([
            [$this->text['create_record'], $this->text['services']],
        ], false, true));

        ServiceOrder::where('user_id', $this->user->id)->where('status', 'NEW')->update([
            'status' => 'DONE'
        ]);
        $this->user->status = UserStatusService::DONE;
        $this->user->save();
    }

}