<?php

namespace App\Commands\CreateRecord;

use App\Commands\BaseCommand;
use App\Models\Record;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardRemove;

class ButtonText extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status === UserStatusService::BUTTON_TEXT || $this->user->status === UserStatusService::BUTTON_TEXT_EDIT) {
            Record::where('user_id', $this->user->id)->where('status', 'NEW')->update([
                'button_text' => $this->update->getMessage()->getText(),
            ]);
            if ($this->user->status === UserStatusService::BUTTON_TEXT_EDIT) {
                $this->triggerCommand(EditButtons::class);
            } else {
                $this->triggerCommand(Preview::class);
            }
        } else {
            if ($this->update->getCallbackQuery()) {
                $action = \json_decode($this->update->getCallbackQuery()->getData(), true)['a'];
                if ($action == 'edit_button_text') {
                    $this->user->status = UserStatusService::BUTTON_TEXT_EDIT;
                }
            } else {
                $this->user->status = UserStatusService::BUTTON_TEXT;
            }
            $this->user->save();

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['button_text_question'], new ReplyKeyboardMarkup([
                [$this->text['main_menu']],
            ], false, true));
        }
    }

}