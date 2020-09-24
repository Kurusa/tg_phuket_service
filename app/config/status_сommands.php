<?php

use App\Services\Status\UserStatusService;

return [
    UserStatusService::SELECT_MEDIA => \App\Commands\CreateRecord\SelectMedia::class,
    UserStatusService::SELECT_MEDIA_EDIT => \App\Commands\CreateRecord\SelectMedia::class,
    UserStatusService::SELECT_PHOTO => \App\Commands\CreateRecord\SelectMedia::class,
    UserStatusService::SELECT_PHOTO_EDIT => \App\Commands\CreateRecord\SelectMedia::class,
    UserStatusService::SELECT_VIDEO => \App\Commands\CreateRecord\SelectMedia::class,
    UserStatusService::SELECT_VIDEO_EDIT => \App\Commands\CreateRecord\SelectMedia::class,
    UserStatusService::TITLE => \App\Commands\CreateRecord\Title::class,
    UserStatusService::TITLE_EDIT => \App\Commands\CreateRecord\Title::class,
    UserStatusService::ABOUT => \App\Commands\CreateRecord\About::class,
    UserStatusService::ABOUT_EDIT => \App\Commands\CreateRecord\About::class,
    UserStatusService::BUTTON_TEXT => \App\Commands\CreateRecord\ButtonText::class,
    UserStatusService::BUTTON_TEXT_EDIT => \App\Commands\CreateRecord\ButtonText::class,

    UserStatusService::SELECT_SERVICE => \App\Commands\Service\SelectService::class,
    
    UserStatusService::SELECT_AUTO_SERVICE_SUBCATEGORY => \App\Commands\Service\Auto\SubCategory::class,
    UserStatusService::SELECT_AUTO_SERVICE_CLASS => \App\Commands\Service\Auto\CarClass::class,
    UserStatusService::SELECT_AUTO_SERVICE_RENT_PERIOD => \App\Commands\Service\Auto\RentPeriod::class,
    UserStatusService::SELECT_AUTO_SERVICE_RENT_START => \App\Commands\Service\Auto\RentStart::class,
    
    UserStatusService::SELECT_REAL_ESTATE_SUBCATEGORY => \App\Commands\Service\RealEstate\SubCategory::class,
    UserStatusService::SELECT_REAL_ESTATE_CLASS => \App\Commands\Service\RealEstate\EstateClass::class,
    UserStatusService::SELECT_REAL_ESTATE_RENT_PERIOD => \App\Commands\Service\RealEstate\RentPeriod::class,
    UserStatusService::SELECT_REAL_ESTATE_ROOM_COUNT => \App\Commands\Service\RealEstate\RoomCount::class,
    UserStatusService::SELECT_REAL_ESTATE_RENT_START => \App\Commands\Service\RealEstate\RentStart::class,
];