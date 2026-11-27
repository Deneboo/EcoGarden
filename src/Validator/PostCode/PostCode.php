<?php

namespace App\Validator\PostCode;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PostCode extends Constraint
{
    public string $message = 'Ce code postal n’existe pas.';
}