<?php

declare(strict_types=1);

namespace ElliotJReed\Maths\Exception;

final class DivisionByZero extends MathsException
{
    protected $message = 'Division by zero.';
}
