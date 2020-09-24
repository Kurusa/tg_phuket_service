<?php

namespace App\Commands\CreateRecord;

use App\Commands\BaseCommand;
use App\Models\Record;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class SelectMedia extends BaseCommand
{

    function processCommand()
    {
        exit();
        $record = Record::where('user_id', $this->user->id)->where('status', 'NEW')->first();
        if ($this->user->status === UserStatusService::SELECT_MEDIA || $this->user->status === UserStatusService::SELECT_MEDIA_EDIT) {
            if ($this->update->getMessage()->getText() == $this->text['add_photo']) {
                if ($this->user->status === UserStatusService::SELECT_MEDIA_EDIT) {
                    $this->user->status = UserStatusService::SELECT_PHOTO_EDIT;
                    $record->media = '';
                    $record->save();
                } else {
                    $this->user->status = UserStatusService::SELECT_PHOTO;
                }
                $this->user->save();
                $text = $this->text['add_photo_process'];
            } else {
                if ($this->user->status === UserStatusService::SELECT_MEDIA_EDIT) {
                    $this->user->status = UserStatusService::SELECT_VIDEO_EDIT;
                    $record->media = '';
                    $record->save();
                } else {
                    $this->user->status = UserStatusService::SELECT_VIDEO;
                }
                $this->user->save();
                $text = $this->text['add_video_process'];
            }
            $this->user->save();
            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $text, new ReplyKeyboardMarkup([
                [$this->text['done']],
            ], false, true));
        } elseif ($this->user->status === UserStatusService::SELECT_PHOTO || $this->user->status === UserStatusService::SELECT_PHOTO_EDIT) {
            if ($this->update->getMessage()->getText() == $this->text['done']) {
                if ($record->media) {
                    if ($this->user->status === UserStatusService::SELECT_PHOTO_EDIT) {
                        $this->triggerCommand(EditButtons::class);
                    } else {
                        $this->triggerCommand(Title::class);
                    }
                }
            } else {
                $file_id = $this->update->getMessage()->getPhoto()[0]->getFileId();
                $record->media = $record->media . ',' . $file_id;
                $record->save();
            }
        } elseif ($this->user->status === UserStatusService::SELECT_VIDEO || $this->user->status === UserStatusService::SELECT_VIDEO_EDIT) {
            if ($this->update->getMessage()->getText() == $this->text['done']) {
                if ($record->media) {
                    if ($this->user->status === UserStatusService::SELECT_VIDEO_EDIT) {
                        $this->triggerCommand(EditButtons::class);
                    } else {
                        $this->triggerCommand(Title::class);
                    }
                }
            } else {
                $video_id = $this->update->getMessage()->getVideo()->getFileId();
                $record->media = $video_id;
                $record->is_video = 1;
                $record->save();
            }
        } else {
            if ($this->update->getCallbackQuery()) {
                $action = \json_decode($this->update->getCallbackQuery()->getData(), true)['a'];
                if ($action == 'edit_photo') {
                    $this->user->status = UserStatusService::SELECT_MEDIA_EDIT;
                } else {
                    $this->user->status = UserStatusService::SELECT_MEDIA;
                }
            }
            $this->user->save();

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['select_media'], new ReplyKeyboardMarkup([
                [$this->text['add_photo']], [$this->text['add_video']],
                [$this->text['main_menu']]
            ], false, true));
        }
    }

}