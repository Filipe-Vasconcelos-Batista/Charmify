<?php

/**
 * Created by Muzzapp
 * User: Filipe Batista
 * Date: 05/07/25 07:14
 */

namespace App\Enum\User;

enum UserEnum :string
{
    case Email = 'email';
    case AgreeTerms = 'agreeTerms';
    case FirstName = 'firstName';
    case LastName = 'lastName';
    case PlainPassword = 'plainPassword';
    case Phone = 'phoneNumber';

}
