<?php
/**
 * Created by PhpStorm.
 * User: Milan Rasljic
 * Date: 03/07/25 16:50
 */

namespace App\Enum;

enum SalonRoleEnum
{
    const ROLE_OWNER  = 'Owner';
    const ROLE_ADMIN  = 'Admin';
    const ROLE_WORKER = 'Worker';
}
