<?php
return [
    'category' => \App\Commands\CreateRecord\Category::class,
    'category_back' => \App\Commands\CreateRecord\Category::class,
    'subcategory' => \App\Commands\CreateRecord\Category::class,
    'publish' => \App\Commands\CreateRecord\Publish::class,
    'edit' => \App\Commands\CreateRecord\EditButtons::class,
    'preview_back' => \App\Commands\CreateRecord\Preview::class,

    'edit_photo' => \App\Commands\CreateRecord\SelectMedia::class,
    'edit_title' => \App\Commands\CreateRecord\Title::class,
    'edit_about' => \App\Commands\CreateRecord\About::class,
    'edit_button_text' => \App\Commands\CreateRecord\ButtonText::class,

    'main_menu' => \App\Commands\MainMenu::class,
    'accept_rules' => \App\Commands\CreateRecord\Category::class,
];