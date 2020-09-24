<?php

namespace App\Commands\CreateRecord;

use App\Commands\BaseCommand;
use App\Models\Record;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;
use TelegramBot\Api\Types\ReplyKeyboardRemove;

class Preview extends BaseCommand
{

    function processCommand()
    {
        $this->user->status = UserStatusService::PREVIEW;
        $this->user->save();

        $record = Record::where('user_id', $this->user->id)->where('status', 'NEW')->first();

        $message = $record->category['title'] . "\n" . "\n";
        $message .= '<b>' . $record->title . '</b>' . "\n" . "\n";
        $message .= $record->about . "\n" . "\n";
        // $message .= $record->price . "\n" . "\n";

        $button = new InlineKeyboardMarkup([
            [[
                'text' => '💬 Контакт: ' . $record->button_text,
                'url' => 't.me/' . $this->user->user_name
            ]], [
                [
                    'text' => $this->text['edit'],
                    'callback_data' => json_encode([
                        'a' => 'edit',
                        'id' => $record->id
                    ])
                ], [
                    'text' => $this->text['publish'],
                    'callback_data' => json_encode([
                        'a' => 'publish',
                        'id' => $record->id
                    ])
                ],
            ],
        ]);

        if ($record->is_video) {
            $this->getBot()->sendVideo($this->user->chat_id, $record->media, null, $message, null, $button, false, false, 'html');
        } else {
            $media = new \TelegramBot\Api\Types\InputMedia\ArrayOfInputMedia();
            $files = explode(',', $record->media);
            foreach ($files as $file) {
                if ($file) {
                    $media->addItem(new InputMediaPhoto($file));
                }
            }
            $this->getBot()->sendMediaGroup($this->user->chat_id, $media);
            $this->getBot()->sendMessage($this->user->chat_id, $message, 'html', false, null, $button);
        }
    }

}