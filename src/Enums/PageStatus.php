<?php

declare(strict_types=1);

namespace YezzMedia\Content\Enums;

enum PageStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
}
