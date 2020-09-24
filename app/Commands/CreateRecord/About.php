<?php

namespace App\Commands\CreateRecord;

use App\Commands\BaseCommand;
use App\Models\Record;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardRemove;

class About extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::ABOUT || $this->user->status === UserStatusService::ABOUT_EDIT) {
            Record::where('user_id', $this->user->id)->where('status', 'NEW')->update([
                'about' => $this->update->getMessage()->getText()
            ]);
            if ($this->user->status === UserStatusService::ABOUT_EDIT) {
                $this->triggerCommand(EditButtons::class);
            } else {
                $this->triggerCommand(ButtonText::class);
            }
        } else {
            if ($this->update->getCallbackQuery()) {
                $action = \json_decode($this->update->getCallbackQuery()->getData(), true)['a'];
                if ($action == 'edit_about') {
                    $this->user->status = UserStatusService::ABOUT_EDIT;
                }
            } else {
                $this->user->status = UserStatusService::ABOUT;
            }
            $this->user->save();

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['about'], new ReplyKeyboardMarkup([
                [$this->text['main_menu']],
            ], false, true));
        }
    }

}