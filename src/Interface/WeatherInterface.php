<?php
declare(strict_types=1);
namespace App\Interface;

interface WeatherInterface
{
    public function fetchData() : array;
    public function getData() : array;
}