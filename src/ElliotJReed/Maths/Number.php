<?php

declare(strict_types=1);

namespace ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\InvalidExponent;
use ElliotJReed\Maths\Exception\InvalidPowerModulusDivisor;

final class Number
{
    private string $number;

    public function __construct(int | float | string $number = 0, private readonly int $precision = 64)
    {
        $this->number = (string) $number;
    }

    public function asString(): string
    {
        if (\str_contains($this->number, '.')) {
            $this->number = \rtrim($this->number, '0');
        }

        return \rtrim($this->number, '.') ?: '0';
    }

    public function asFloat(): float
    {
        return (float) $this->number;
    }

    public function asInteger(int $roundingMode = \PHP_ROUND_HALF_UP): int
    {
        return (int) \round((float) $this->number, mode: $roundingMode);
    }

    public function add(int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = (string) $numberAsIntegerOrFloatOrString;

            $this->number = \bcadd($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    public function subtract(int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = (string) $numberAsIntegerOrFloatOrString;

            $this->number = \bcsub($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    public function multiply(int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = (string) $numberAsIntegerOrFloatOrString;

            $this->number = \bcmul($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    public function divide(int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = (string) $numberAsIntegerOrFloatOrString;

            $this->number = \bcdiv($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    public function squareRoot(): self
    {
        $this->number = \bcsqrt($this->number, $this->precision);

        return $this;
    }

    public function modulus(int | float | string $divisorNumber): self
    {
        $this->number = \bcmod($this->number, (string) $divisorNumber, $this->precision);

        return $this;
    }

    public function raiseToPower(int | float | string $exponentNumber): self
    {
        if (\floor((float) $exponentNumber) !== (float) $exponentNumber) {
            throw new InvalidExponent('Exponent must be a whole number. Invalid exponent: ' . $exponentNumber);
        }

        $this->number = \bcpow($this->number, (string) $exponentNumber, $this->precision);

        return $this;
    }

    public function raiseToPowerReduceByModulus(
        int | float | string $exponentNumber,
        int | float | string $divisorNumber
    ): self {
        if (\floor((float) $exponentNumber) !== (float) $exponentNumber) {
            throw new InvalidExponent('Exponent must be a whole number. Invalid exponent: ' . $exponentNumber);
        }

        if (\floor((float) $divisorNumber) !== (float) $divisorNumber) {
            throw new InvalidPowerModulusDivisor('Divisor must be a whole number. Invalid divisor: ' . $divisorNumber);
        }

        $this->number = \bcpowmod($this->number, (string) $exponentNumber, (string) $divisorNumber, $this->precision);

        return $this;
    }

    public function isLessThan(int | float | string $number): bool
    {
        $result = \bccomp($this->number, (string) $number, $this->precision);

        return -1 === $result;
    }

    public function isGreaterThan(int | float | string $number): bool
    {
        $result = \bccomp($this->number, (string) $number, $this->precision);

        return 1 === $result;
    }

    public function isEqualTo(int | float | string $number): bool
    {
        $result = \bccomp($this->number, (string) $number, $this->precision);

        return 0 === $result;
    }

    public function isLessThanOrEqualTo(int | float | string $number): bool
    {
        $result = \bccomp($this->number, (string) $number, $this->precision);

        return -1 === $result || 0 === $result;
    }

    public function isGreaterThanOrEqualTo(int | float | string $number): bool
    {
        $result = \bccomp($this->number, (string) $number, $this->precision);

        return 1 === $result || 0 === $result;
    }
}
