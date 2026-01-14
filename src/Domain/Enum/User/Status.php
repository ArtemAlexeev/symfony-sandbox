<?php

namespace App\Domain\Enum\User;

enum Status: string
{
    case READY_TO_CHAT = 'ready_to_chat';
    case READY_TO_MEET = 'ready_to_meet';
    case LONG_RELATIONS_ONLY = 'long_relations_only';
    case FRIENDSHIP_ONLY = 'friendship_only';
    case OPEN_TO_SHORT_DATE = 'open_to_short_date';
}
