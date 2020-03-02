<?php

namespace App\DataFixtures\Provider;

use Faker\Provider\Base;


class ImageProvider extends Base
{
    protected static $imageId = [
        
        'https://picsum.photos/id/1027/200/300',
        'https://picsum.photos/id/163/200/300',
        'https://picsum.photos/id/212/200/300',
        'https://picsum.photos/id/225/200/300',
        'https://picsum.photos/id/312/200/300',
        'https://picsum.photos/id/337/200/300',
        'https://picsum.photos/id/360/200/300',
        'https://picsum.photos/id/429/200/300',
        'https://picsum.photos/id/425/200/300',
        'https://picsum.photos/id/488/200/300',
        'https://picsum.photos/id/530/200/300',
        'https://picsum.photos/id/627/200/300',
                
    ];
   
    public static function ImageUrl()
    {
        return static::randomElement(static::$imageId);
    }
}
