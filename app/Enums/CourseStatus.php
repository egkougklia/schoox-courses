<?php

namespace App\Enums;

enum CourseStatus: string
{
    case PUBLISHED = 'published';
    case DRAFT = 'draft';
    case ARCHIVED = 'archived';
}
