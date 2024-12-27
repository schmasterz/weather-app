<?php
declare(strict_types=1);
namespace App\Constant;

final class WeatherApiConstants
{
    public function __construct()
    {

    }
    public const string CURRENT_WEATHER_ENDPOINT = 'data/2.5/weather';
    public const string DAILY_WEATHER_ENDPOINT = 'data/3.0/onecall/day_summary';
    public const int CURRENT_WEATHER_EXPIRATION_TIME = 3600;

}