<?php

namespace App\Commands\CreateRecord;

use App\Commands\BaseCommand;
use App\Models\Record;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Category extends BaseCommand
{

    function processCommand()
    {
        $action = $this->update->getCallbackQuery() ? \json_decode($this->update->getCallbackQuery()->getData(), true)['a'] : null;
        if ($action !== 'accept_rules') {
            $id = \json_decode($this->update->getCallbackQuery()->getData(), true)['id'];

            switch ($action) {
                case 'category':
                    Record::create([
                        'user_id' => $this->user->id,
                        'category_id' => $id
                    ]);
                    $this->getBot()->deleteMessage($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId());
                    $this->triggerCommand(SelectMedia::class);

                    /*$buttons = [];
                    $sub_categories = \App\Models\Category::where('subcategory_id', $id)->get();
                    foreach ($sub_categories as $sub_category) {
                        $buttons[] = [[
                            'text' => $sub_category->title,
                            'callback_data' => json_encode([
                                'a' => 'subcategory',
                                'id' => $sub_category->id
                            ])
                        ]];
                    }
                    $buttons[] = [[
                        'text' => $this->text['back'],
                        'callback_data' => json_encode([
                            'a' => 'category_back',
                        ])
                    ]];
                    $this->getBot()->editMessageText($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId(), $this->text['select_subcategory'], 'html', false, new InlineKeyboardMarkup($buttons));*/
                    break;
                case 'category_back':
                    $this->getBot()->editMessageText($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId(), $this->text['select_category'], 'html', false, new InlineKeyboardMarkup($this->getCategories()));
                    break;
                case 'subcategory':
                    Record::create([
                        'user_id' => $this->user->id,
                        'category_id' => $id
                    ]);
                    $this->getBot()->deleteMessage($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId());
                    $this->triggerCommand(SelectMedia::class);
                    break;
            }
        } else {
            $this->getBot()->deleteMessage($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId());

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['select_category'],new ReplyKeyboardMarkup([
                [$this->text['main_menu']],
            ], false, true));
            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, 'Список', new InlineKeyboardMarkup($this->getCategories()));
        }
    }

    private function getCategories(): array
    {
        $buttons = [];
        $categories = \App\Models\Category::where('subcategory_id', null)->get();
        foreach ($categories as $category) {
            $buttons[] = [[
                'text' => $category->title,
                'callback_data' => json_encode([
                    'a' => 'category',
                    'id' => $category->id
                ])
            ]];
        }
        return $buttons;
    }

}