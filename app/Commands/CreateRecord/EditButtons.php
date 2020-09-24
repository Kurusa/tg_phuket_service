<?php

namespace App\Commands\CreateRecord;

use App\Commands\BaseCommand;
use App\Models\Record;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;

class EditButtons extends BaseCommand
{

    function processCommand()
    {
        $this->user->status = UserStatusService::EDIT;
        $this->user->save();

        $record = Record::where('user_id', $this->user->id)->where('status', 'NEW')->first();

        $message = $record->category['title'] . "\n" . "\n";
        $message .= '<b>' . $record->title . '</b>' . "\n" . "\n";
        $message .= $record->about . "\n" . "\n";
       // $message .= $record->price . "\n" . "\n";
        $message .= 'Что бы вы хотели изменить?';

        $button = new InlineKeyboardMarkup([
            [
                [
                    'text' => 'фото/видео',
                    'callback_data' => json_encode([
                        'a' => 'edit_photo',
                        'id' => $record->id
                    ])
                ],
            ], [
                [
                    'text' => 'название',
                    'callback_data' => json_encode([
                        'a' => 'edit_title',
                        'id' => $record->id
                    ])
                ],
            ], [
                [
                    'text' => 'описание',
                    'callback_data' => json_encode([
                        'a' => 'edit_about',
                        'id' => $record->id
                    ])
                ],
            ],/* [
                [
                    'text' => 'цену',
                    'callback_data' => json_encode([
                        'a' => 'edit_price',
                        'id' => $record->id
                    ])
                ],
            ],*/ /*[
                [
                    'text' => 'призыв к действию',
                    'callback_data' => json_encode([
                        'a' => 'edit_button_text',
                        'id' => $record->id
                    ])
                ],
            ],*/[
                [
                    'text' => $this->text['back'],
                    'callback_data' => json_encode([
                        'a' => 'preview_back',
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