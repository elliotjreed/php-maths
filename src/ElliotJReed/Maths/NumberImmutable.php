<?php

declare(strict_types=1);

namespace ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\DivisionByZero;
use ElliotJReed\Maths\Exception\InvalidDecimalPlaces;
use ElliotJReed\Maths\Exception\InvalidExponent;
use ElliotJReed\Maths\Exception\InvalidPowerModulusDivisor;

final class NumberImmutable extends NumberFormat
{
    /**
     * Rounds "base" number to the specified number of decimal places. Note: this method does not format to the specified number of decimal places, to do so use the `asString` method.
     *
     * @param int     $decimalPlaces the number of decimal places to round to
     * @param 1|2|3|4 $roundingMode  (Optional) The rounding method defined by PHP internal maths constants [PHP_ROUND_HALF_UP (1) | PHP_ROUND_HALF_DOWN (2) | PHP_ROUND_HALF_EVEN (3) | PHP_ROUND_HALF_ODD (4)]. Default: PHP_ROUND_HALF_UP (1)
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     *
     * @throws InvalidDecimalPlaces thrown when the decimal places argument is less than zero
     */
    public function roundToDecimalPlaces(int $decimalPlaces, int $roundingMode = \PHP_ROUND_HALF_UP): self
    {
        if ($decimalPlaces < 0) {
            throw new InvalidDecimalPlaces('Decimal places must be a whole number greater than or equal to 0. Invalid decimal places number: ' . $decimalPlaces);
        }

        return new self((string) \round((float) $this->number, $decimalPlaces, mode: $roundingMode), $this->precision);
    }

    /**
     * Adds a number or multiple numbers to the "base" number.
     *
     * @param Number|NumberImmutable|int|float|string ...$number The number or numbers to add to the "base" number.
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function add(self | Number | int | float | string ...$number): self
    {
        $newNumber = $this->number;
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $newNumber = \bcadd($newNumber, $numberAsString, $this->precision);
        }

        return new self($newNumber, $this->precision);
    }

    /**
     * Subtracts a number or multiple numbers from the "base" number.
     *
     * @param Number|NumberImmutable|int|float|string ...$number The number or numbers to subtract from the "base" number.
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function subtract(self | Number | int | float | string ...$number): self
    {
        $newNumber = $this->number;
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $newNumber = \bcsub($newNumber, $numberAsString, $this->precision);
        }

        return new self($newNumber, $this->precision);
    }

    /**
     * Multiplies the "base" number by a number or multiple numbers.
     *
     * @param Number|NumberImmutable|int|float|string ...$number The number or numbers to multiple by the "base" number.
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function multiply(self | Number | int | float | string ...$number): self
    {
        $newNumber = $this->number;
        foreach ($number as $numberAsIntegerOrFloatOrString) {
            $numberAsString = $this->castNumberToString($numberAsIntegerOrFloatOrString);

            $newNumber = \bcmul($newNumber, $numberAsString, $this->precision);
        }

        return new self($newNumber, $this->precision);
    }

    /**
     * Divides the "base" number by a number or multiple numbers.
     *
     * @param Number|NumberImmutable|int|float|string ...$number The number or numbers to divide by the "base" number.
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     *
     * @throws DivisionByZero thrown when attempting to divide by zero
     */
    public function divide(self | Number | int | float | string ...$number): self
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

        return new self($newNumber, $this->precision);
    }

    /**
     * Calculates the square root of the "base" number.
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function squareRoot(): self
    {
        $newNumber = \bcsqrt($this->number, $this->precision);

        return new self($newNumber, $this->precision);
    }

    /**
     * Calculates the modulus (remainder) when dividing a number by the "base" number.
     *
     * @param Number|NumberImmutable|int|float|string $divisorNumber the divisor number when calculating the modulus (remainder) when dividing by the "base" number
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function modulus(self | Number | int | float | string $divisorNumber): self
    {
        $numberAsString = $this->castNumberToString($divisorNumber);

        $newNumber = \bcmod($this->number, $numberAsString, $this->precision);

        return new self($newNumber, $this->precision);
    }

    /**
     * Raises the "base" number to the power of the specified exponent number.
     *
     * @param Number|NumberImmutable|int|float|string $exponentNumber the exponent ("power to") number to raise the "base" number by
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     *
     * @throws InvalidExponent thrown when the exponent number is not a whole number
     */
    public function raiseToPower(self | Number | int | float | string $exponentNumber): self
    {
        $numberAsString = $this->castNumberToString($exponentNumber);

        if (\floor((float) $numberAsString) !== (float) $numberAsString) {
            throw new InvalidExponent('Exponent must be a whole number. Invalid exponent: ' . $numberAsString);
        }

        $newNumber = \bcpow($this->number, $numberAsString, $this->precision);

        return new self($newNumber, $this->precision);
    }

    /**
     * Raises the "base" number to the power of the specified exponent number and reduces by the modulus (remainder) divisor number.
     *
     * @param Number|NumberImmutable|int|float|string $exponentNumber the exponent ("power to") number to raise the "base" number by
     * @param Number|NumberImmutable|int|float|string $divisorNumber  the divisor number when calculating the modulus (remainder) when dividing by the "base" number
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     *
     * @throws InvalidExponent            thrown when the exponent number is not a whole number
     * @throws InvalidPowerModulusDivisor thrown when the divisor number is not a whole number
     */
    public function raiseToPowerReduceByModulus(
        self | Number | int | float | string $exponentNumber,
        self | Number | int | float | string $divisorNumber
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

    /**
     * Increases the "base" number by the specified percentage.
     *
     * @param Number|NumberImmutable|int|float|string $percent the percentage to increase the "base" number by
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function increaseByPercentage(self | Number | int | float | string $percent): self
    {
        $percentAsString = $this->castNumberToString($percent);

        $increase = \bcmul(
            $this->number,
            \bcdiv($percentAsString, '100', $this->precision),
            $this->precision
        );

        $newNumber = \bcadd($this->number, $increase, $this->precision);

        return new self($newNumber, $this->precision);
    }

    /**
     * Decreases the "base" number by the specified percentage.
     *
     * @param Number|NumberImmutable|int|float|string $percent the percentage to decrease the "base" number by
     *
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function decreaseByPercentage(self | Number | int | float | string $percent): self
    {
        $percentAsString = $this->castNumberToString($percent);

        $increase = \bcmul(
            $this->number,
            \bcmul(\bcdiv($percentAsString, '100', $this->precision), '-1', $this->precision),
            $this->precision
        );

        $newNumber = \bcadd($this->number, $increase, $this->precision);

        return new self($newNumber, $this->precision);
    }
}
