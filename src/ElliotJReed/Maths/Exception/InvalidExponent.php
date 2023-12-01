<?php

declare(strict_types=1);

namespace ElliotJReed\Maths\Exception;

final class InvalidExponent extends MathsException
{
    protected $message = 'Exponent must be a whole number.';
}
