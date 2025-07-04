<?php
/**
 * Created by PhpStorm.
 * User: Filipe Batista
 * Date: 03/07/25 16:50
 */

namespace App\Enum;

enum SalonRoleEnum :string
{
    case ROLE_OWNER  = 'Owner';
    case ROLE_ADMIN  = 'Admin';
    case ROLE_WORKER = 'Worker';
}
