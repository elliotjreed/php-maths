<?php

declare(strict_types=1);

namespace ElliotJReed\Maths\Exception;

final class InvalidDecimalPlaces extends MathsException
{
    protected $message = 'Decimal places must be a whole number greater than or equal to 0.';
}
