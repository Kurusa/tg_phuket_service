<?php

namespace App\Commands\CreateRecord;

use App\Commands\BaseCommand;
use App\Commands\MainMenu;
use App\Models\Record;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;
use TelegramBot\Api\Types\ReplyKeyboardRemove;

class Publish extends BaseCommand
{

    function processCommand()
    {
        $record = Record::where('user_id', $this->user->id)->where('status', 'NEW')->first();
        $message = $record->category['title'] . "\n" . "\n";
        $message .= '<b>' . $record->title . '</b>' . "\n" . "\n";
        $message .= $record->about . "\n" . "\n";
       // $message .= $record->price . "\n" . "\n";
        $button = new InlineKeyboardMarkup([
            [[
                'text' => '💬 Контакт: ' . $record->button_text,
                'url' => 't.me/' . $this->user->user_name
            ]],
        ]);

        if ($record->is_video) {
            $this->getBot()->sendVideo(env('GROUP_ID'), $record->media, null, $message, null, $button, false, false, 'html');
        } else {
            $media = new \TelegramBot\Api\Types\InputMedia\ArrayOfInputMedia();
            $files = explode(',', $record->media);
            foreach ($files as $file) {
                if ($file) {
                    $media->addItem(new InputMediaPhoto($file));
                }
            }
            $this->getBot()->sendMediaGroup(env('GROUP_ID'), $media);
            $this->getBot()->sendMessage(env('GROUP_ID'), $message, 'html', false, null, $button);
        }

        $this->getBot()->deleteMessage($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId());
        Record::where('user_id', $this->user->id)->where('status', 'NEW')->update([
            'status' => 'DONE'
        ]);
        $this->getBot()->sendMessage($this->user->chat_id, $this->text['publish_done']);
        $this->triggerCommand(MainMenu::class);
    }

}