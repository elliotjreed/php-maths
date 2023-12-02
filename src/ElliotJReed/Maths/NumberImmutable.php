<?php

declare(strict_types=1);

namespace ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\InvalidDecimalPlaces;
use ElliotJReed\Maths\Exception\InvalidExponent;
use ElliotJReed\Maths\Exception\InvalidPowerModulusDivisor;

final class NumberImmutable extends NumberFormat
{
    /**
     * @param int $decimalPlaces the number of decimal places to round to
     * @param int $roundingMode  (Optional) The rounding method defined by PHP internal maths constants [PHP_ROUND_HALF_UP (1) | PHP_ROUND_HALF_DOWN (2) | PHP_ROUND_HALF_EVEN (3) | PHP_ROUND_HALF_ODD (4)]. Default: PHP_ROUND_HALF_UP (1)
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     *
     * @throws \ElliotJReed\Maths\Exception\InvalidDecimalPlaces thrown when the decimal places argument is less than zero
     */
    public function roundToDecimalPlaces(int $decimalPlaces, int $roundingMode = \PHP_ROUND_HALF_UP): self
    {
        if ($decimalPlaces < 0) {
            throw new InvalidDecimalPlaces('Decimal places must be a whole number greater than or equal to 0. Invalid decimal places number: ' . $decimalPlaces);
        }

        return new self((string) \round((float) $this->number, $decimalPlaces, mode: $roundingMode), $this->precision);
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to add to the "base" number.
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function add(self | int | float | string ...$number): self
    {
        $newNumber = $this->number;
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $newNumber = \bcadd($newNumber, $numberAsString, $this->precision);
        }

        return new self($newNumber, $this->precision);
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to subtract from the "base" number.
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function subtract(self | int | float | string ...$number): self
    {
        $newNumber = $this->number;
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $newNumber = \bcsub($newNumber, $numberAsString, $this->precision);
        }

        return new self($newNumber, $this->precision);
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to multiple by the "base" number.
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function multiply(self | int | float | string ...$number): self
    {
        $newNumber = $this->number;
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $newNumber = \bcmul($newNumber, $numberAsString, $this->precision);
        }

        return new self($newNumber, $this->precision);
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to divide by the "base" number.
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function divide(self | int | float | string ...$number): self
    {
        $newNumber = $this->number;
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $newNumber = \bcdiv($newNumber, $numberAsString, $this->precision);
        }

        return new self($newNumber, $this->precision);
    }

    /**
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function squareRoot(): self
    {
        $newNumber = \bcsqrt($this->number, $this->precision);

        return new self($newNumber, $this->precision);
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $divisorNumber the divisor number when calculating the modulus (remainder) when dividing by the "base" number
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function modulus(self | int | float | string $divisorNumber): self
    {
        $numberAsString = $this->castNumberToString($divisorNumber);

        $newNumber = \bcmod($this->number, $numberAsString, $this->precision);

        return new self($newNumber, $this->precision);
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $exponentNumber the exponent ("power to") number to raise the "base" number by
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     *
     * @throws \ElliotJReed\Maths\Exception\InvalidExponent thrown when the exponent number is not a whole number
     */
    public function raiseToPower(self | int | float | string $exponentNumber): self
    {
        $numberAsString = $this->castNumberToString($exponentNumber);

        if (\floor((float) $numberAsString) !== (float) $numberAsString) {
            throw new InvalidExponent('Exponent must be a whole number. Invalid exponent: ' . $numberAsString);
        }

        $newNumber = \bcpow($this->number, $numberAsString, $this->precision);

        return new self($newNumber, $this->precision);
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $exponentNumber the exponent ("power to") number to raise the "base" number by
     * @param \ElliotJReed\Maths\Number|int|float|string $divisorNumber  the divisor number when calculating the modulus (remainder) when dividing by the "base" number
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     *
     * @throws \ElliotJReed\Maths\Exception\InvalidExponent            thrown when the exponent number is not a whole number
     * @throws \ElliotJReed\Maths\Exception\InvalidPowerModulusDivisor thrown when the divisor number is not a whole number
     */
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

        $newNumber = \bcpowmod($this->number, $exponentNumberAsString, $divisorNumberAsString, $this->precision);

        return new self($newNumber, $this->precision);
    }
}
