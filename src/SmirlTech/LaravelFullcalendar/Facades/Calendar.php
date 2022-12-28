<?php namespace SmirlTech\LaravelFullcalendar\Facades;

use Illuminate\Support\Facades\Facade;

class Calendar extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return 'laravel-fullcalendar';
    }
}