<?php

declare(strict_types=1);

namespace ElliotJReed\Maths\Exception;

final class InvalidPowerModulusDivisor extends MathsException
{
    protected $message = 'Divisor must be a whole number.';
}
