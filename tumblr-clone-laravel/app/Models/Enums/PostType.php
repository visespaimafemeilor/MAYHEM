<?php

namespace App\Models\Enums;

enum PostType: string
{
    case Text = 'text';
    case Image = 'image';
    case Quote = 'quote';
    case Link = 'link';
}
