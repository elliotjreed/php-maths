<?php

declare(strict_types=1);

namespace ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\InvalidDecimalPlaces;
use ElliotJReed\Maths\Exception\InvalidExponent;
use ElliotJReed\Maths\Exception\InvalidPowerModulusDivisor;

final class Number extends NumberFormat
{
    /**
     * @param int $decimalPlaces the number of decimal places to round to
     * @param int $roundingMode  (Optional) The rounding method defined by PHP internal maths constants [PHP_ROUND_HALF_UP (1) | PHP_ROUND_HALF_DOWN (2) | PHP_ROUND_HALF_EVEN (3) | PHP_ROUND_HALF_ODD (4)]. Default: PHP_ROUND_HALF_UP (1)
     *
     * @return $this
     *
     * @throws \ElliotJReed\Maths\Exception\InvalidDecimalPlaces thrown when the decimal places argument is less than zero
     */
    public function roundToDecimalPlaces(int $decimalPlaces, int $roundingMode = \PHP_ROUND_HALF_UP): self
    {
        if ($decimalPlaces < 0) {
            throw new InvalidDecimalPlaces('Decimal places must be a whole number greater than or equal to 0. Invalid decimal places number: ' . $decimalPlaces);
        }

        $this->number = (string) \round((float) $this->number, $decimalPlaces, mode: $roundingMode);

        return $this;
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to add to the "base" number.
     *
     * @return $this
     */
    public function add(self | int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $this->number = \bcadd($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to subtract from the "base" number.
     *
     * @return $this
     */
    public function subtract(self | int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $this->number = \bcsub($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to multiple by the "base" number.
     *
     * @return $this
     */
    public function multiply(self | int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $this->number = \bcmul($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to divide by the "base" number.
     *
     * @return $this
     */
    public function divide(self | int | float | string ...$number): self
    {
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $this->number = \bcdiv($this->number, $numberAsString, $this->precision);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function squareRoot(): self
    {
        $this->number = \bcsqrt($this->number, $this->precision);

        return $this;
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $divisorNumber the divisor number when calculating the modulus (remainder) when dividing by the "base" number
     *
     * @return $this
     */
    public function modulus(self | int | float | string $divisorNumber): self
    {
        $numberAsString = $this->castNumberToString($divisorNumber);

        $this->number = \bcmod($this->number, $numberAsString, $this->precision);

        return $this;
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $exponentNumber the exponent ("power to") number to raise the "base" number by
     *
     * @return $this
     *
     * @throws \ElliotJReed\Maths\Exception\InvalidExponent thrown when the exponent number is not a whole number
     */
    public function raiseToPower(self | int | float | string $exponentNumber): self
    {
        $numberAsString = $this->castNumberToString($exponentNumber);

        if (\floor((float) $numberAsString) !== (float) $numberAsString) {
            throw new InvalidExponent('Exponent must be a whole number. Invalid exponent: ' . $numberAsString);
        }

        $this->number = \bcpow($this->number, $numberAsString, $this->precision);

        return $this;
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $exponentNumber the exponent ("power to") number to raise the "base" number by
     * @param \ElliotJReed\Maths\Number|int|float|string $divisorNumber  the divisor number when calculating the modulus (remainder) when dividing by the "base" number
     *
     * @return $this
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

        $this->number = \bcpowmod($this->number, $exponentNumberAsString, $divisorNumberAsString, $this->precision);

        return $this;
    }
}
