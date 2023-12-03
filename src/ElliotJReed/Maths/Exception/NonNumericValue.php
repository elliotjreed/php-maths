<?php

declare(strict_types=1);

namespace ElliotJReed\Maths\Exception;

final class NonNumericValue extends MathsException
{
    protected $message = 'Non-numeric string provided.';
}
