<?php

declare(strict_types=1);

namespace ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\DivisionByZero;
use ElliotJReed\Maths\Exception\InvalidDecimalPlaces;
use ElliotJReed\Maths\Exception\InvalidExponent;
use ElliotJReed\Maths\Exception\InvalidPowerModulusDivisor;

final class Number extends NumberFormat
{
    /**
     * Rounds "base" number to the specified number of decimal places. Note: this method does not format to the specified number of decimal places, to do so use the `asString` method.     * @param int $decimalPlaces the number of decimal places to round to
     * @param int $roundingMode (Optional) The rounding method defined by PHP internal maths constants [PHP_ROUND_HALF_UP (1) | PHP_ROUND_HALF_DOWN (2) | PHP_ROUND_HALF_EVEN (3) | PHP_ROUND_HALF_ODD (4)]. Default: PHP_ROUND_HALF_UP (1)
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
     * Adds a number or multiple numbers to the "base" number.
     *
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to add to the "base" number.
     *
     * @return $this
     */
    public function add(self | int | float | string ...$number): self
    {
        $newNumber = $this->number;
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $newNumber = \bcadd($newNumber, $numberAsString, $this->precision);
        }

        $this->number = $newNumber;

        return $this;
    }

    /**
     * Subtracts a number or multiple numbers from the "base" number.
     *
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to subtract from the "base" number.
     *
     * @return $this
     */
    public function subtract(self | int | float | string ...$number): self
    {
        $newNumber = $this->number;
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $newNumber = \bcsub($newNumber, $numberAsString, $this->precision);
        }

        $this->number = $newNumber;

        return $this;
    }

    /**
     * Multiplies the "base" number by a number or multiple numbers.
     *
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to multiple by the "base" number.
     *
     * @return $this
     */
    public function multiply(self | int | float | string ...$number): self
    {
        $newNumber = $this->number;
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $newNumber = \bcmul($newNumber, $numberAsString, $this->precision);
        }

        $this->number = $newNumber;

        return $this;
    }

    /**
     * Divides the "base" number by a number or multiple numbers.
     *
     * @param \ElliotJReed\Maths\Number|int|float|string ...$number The number or numbers to divide by the "base" number.
     *
     * @return $this
     *
     * @throws \ElliotJReed\Maths\Exception\DivisionByZero thrown when attempting to divide by zero
     */
    public function divide(self | int | float | string ...$number): self
    {
        if (0 === \bccomp($this->number, '0', $this->precision)) {
            throw new DivisionByZero();
        }

        $newNumber = $this->number;
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            if (0 === \bccomp($numberAsString, '0', $this->precision)) {
                throw new DivisionByZero();
            }

            $newNumber = \bcdiv($newNumber, $numberAsString, $this->precision);
        }

        $this->number = $newNumber;

        return $this;
    }

    /**
     * Calculates the square root of the "base" number.
     *
     * @return $this
     */
    public function squareRoot(): self
    {
        $this->number = \bcsqrt($this->number, $this->precision);

        return $this;
    }

    /**
     * Calculates the modulus (remainder) when dividing a number by the "base" number.
     *
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
     * Raises the "base" number to the power of the specified exponent number.
     *
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
     * Raises the "base" number to the power of the specified exponent number and reduces by the modulus (remainder) divisor number.
     *
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

    /**
     * Increases the "base" number by the specified percentage.
     *
     * @param \ElliotJReed\Maths\Number|int|float|string $percent the percentage to increase the "base" number by
     *
     * @return $this
     */
    public function increaseByPercentage(self | int | float | string $percent): self
    {
        $percentAsString = $this->castNumberToString($percent);

        $increase = \bcmul(
            $this->number,
            \bcdiv($percentAsString, '100', $this->precision),
            $this->precision
        );

        $this->number = \bcadd($this->number, $increase, $this->precision);

        return $this;
    }

    /**
     * Decreases the "base" number by the specified percentage.
     *
     * @param \ElliotJReed\Maths\Number|int|float|string $percent the percentage to decrease the "base" number by
     *
     * @return $this
     */
    public function decreaseByPercentage(self | int | float | string $percent): self
    {
        $percentAsString = $this->castNumberToString($percent);

        $increase = \bcmul(
            $this->number,
            \bcmul(\bcdiv($percentAsString, '100', $this->precision), '-1', $this->precision),
            $this->precision
        );

        $this->number = \bcadd($this->number, $increase, $this->precision);

        return $this;
    }
}
