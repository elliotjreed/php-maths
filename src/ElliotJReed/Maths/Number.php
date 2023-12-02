<?php

declare(strict_types=1);

namespace ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\InvalidExponent;
use ElliotJReed\Maths\Exception\InvalidPowerModulusDivisor;

final class Number
{
    private string $number;

    public function __construct(self | int | float | string $number = 0, private readonly int $precision = 64)
    {
        $this->number = $this->castNumberToString($number);
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

    public function add(self | int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $this->number = \bcadd($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    public function subtract(self | int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $this->number = \bcsub($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    public function multiply(self | int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $this->number = \bcmul($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    public function divide(self | int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $this->number = \bcdiv($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    public function squareRoot(): self
    {
        $this->number = \bcsqrt($this->number, $this->precision);

        return $this;
    }

    public function modulus(self | int | float | string $divisorNumber): self
    {
        $numberAsString = $this->castNumberToString($divisorNumber);

        $this->number = \bcmod($this->number, $numberAsString, $this->precision);

        return $this;
    }

    public function raiseToPower(self | int | float | string $exponentNumber): self
    {
        $numberAsString = $this->castNumberToString($exponentNumber);

        if (\floor((float) $numberAsString) !== (float) $numberAsString) {
            throw new InvalidExponent('Exponent must be a whole number. Invalid exponent: ' . $numberAsString);
        }

        $this->number = \bcpow($this->number, $numberAsString, $this->precision);

        return $this;
    }

    public function raiseToPowerReduceByModulus(
        self | int | float | string $exponentNumber,
        self | int | float | string $divisorNumber
    ): self {
        $exponentNumberAsString = $this->castNumberToString($exponentNumber);
        if (\floor((float) $exponentNumberAsString) !== (float) $exponentNumberAsString) {
            throw new InvalidExponent('Exponent must be a whole number. Invalid exponent: ' . $exponentNumberAsString);
        }

        $divisorNumberAsString = $this->castNumberToString($divisorNumber);
        if (\floor((float) $divisorNumberAsString) !== (float) $divisorNumberAsString) {
            throw new InvalidPowerModulusDivisor('Divisor must be a whole number. Invalid divisor: ' . $divisorNumber);
        }

        $this->number = \bcpowmod($this->number, $exponentNumberAsString, $divisorNumberAsString, $this->precision);

        return $this;
    }

    public function isLessThan(self | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return -1 === $result;
    }

    public function isGreaterThan(self | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return 1 === $result;
    }

    public function isEqualTo(self | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return 0 === $result;
    }

    public function isLessThanOrEqualTo(self | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return -1 === $result || 0 === $result;
    }

    public function isGreaterThanOrEqualTo(self | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return 1 === $result || 0 === $result;
    }

    private function castNumberToString(self | int | float | string $number): string
    {
        if ($number instanceof self) {
            $numberAsString = $number->asString();
        } else {
            $numberAsString = (string) $number;
        }

        return $numberAsString;
    }
}
