<?php

namespace App\Enums\Enums;

enum PostType: string
{
    case ARTICLE = 'text';
    case VIDEO = 'video';
    case PODCAST = 'audio';
    case IMAGE = 'image';
    case MULTIMEDIA = 'multimedia';
}
