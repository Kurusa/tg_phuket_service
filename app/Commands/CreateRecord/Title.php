<?php

namespace App\Commands\CreateRecord;

use App\Commands\BaseCommand;
use App\Models\Record;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Title extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::TITLE || $this->user->status === UserStatusService::TITLE_EDIT) {
            if (strlen($this->update->getMessage()->getText()) <= 100) {
                Record::where('user_id', $this->user->id)->where('status', 'NEW')->update([
                    'title' => $this->update->getMessage()->getText()
                ]);
                if ($this->user->status === UserStatusService::TITLE_EDIT) {
                    $this->triggerCommand(EditButtons::class);
                } else {
                    $this->triggerCommand(About::class);
                }
            } else {
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['too_long']);
            }
        } else {
            if ($this->update->getCallbackQuery()) {
                $action = \json_decode($this->update->getCallbackQuery()->getData(), true)['a'];
                if ($action == 'edit_title') {
                    $this->user->status = UserStatusService::TITLE_EDIT;
                }
            } else {
                $this->user->status = UserStatusService::TITLE;
            }
            $this->user->save();

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['title'], new ReplyKeyboardMarkup([
                [$this->text['main_menu']],
            ], false, true));
        }
    }

}