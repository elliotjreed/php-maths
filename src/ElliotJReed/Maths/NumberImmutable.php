<?php

declare(strict_types=1);

namespace ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\InvalidDecimalPlaces;
use ElliotJReed\Maths\Exception\InvalidExponent;
use ElliotJReed\Maths\Exception\InvalidPowerModulusDivisor;

final class NumberImmutable
{
    private string $number;

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $number    (Optional) The "base" number. Default: 0
     * @param int                                        $precision (Optional) The number of digits after the decimal place in the result. Default: 64
     */
    public function __construct(self | int | float | string $number = 0, private readonly int $precision = 64)
    {
        $this->number = $this->castNumberToString($number);
    }

    /**
     * @param int|null $decimalPlaces      (Optional) The number of decimal places to return (note: this will not round the number, for rounding use the roundToDecimalPlaces method)
     * @param string   $thousandsSeparator (Optional) Thousands separator. Default: empty string (i.e. none)
     *
     * @return string the number formatted as a string
     *
     * @throws \ElliotJReed\Maths\Exception\InvalidDecimalPlaces thrown when the decimal places argument is less than zero
     */
    public function asString(?int $decimalPlaces = null, string $thousandsSeparator = ''): string
    {
        if (null !== $decimalPlaces) {
            if ($decimalPlaces < 0) {
                throw new InvalidDecimalPlaces('Decimal places must be a whole number greater than or equal to 0. Invalid decimal places number: ' . $decimalPlaces);
            }

            return \number_format((float) $this->number, $decimalPlaces, '.', $thousandsSeparator);
        }

        if (\str_contains($this->number, '.')) {
            $this->number = \rtrim($this->number, '0');
        }

        return \rtrim($this->number, '.') ?: '0';
    }

    /**
     * @return float the number formatted as a float (decimal)
     */
    public function asFloat(): float
    {
        return (float) $this->number;
    }

    /**
     * @param int $roundingMode (Optional) The rounding method defined by PHP internal maths constants [PHP_ROUND_HALF_UP (1) | PHP_ROUND_HALF_DOWN (2) | PHP_ROUND_HALF_EVEN (3) | PHP_ROUND_HALF_ODD (4)]. Default: PHP_ROUND_HALF_UP (1)
     *
     * @return int the number formatted as an integer (rounded up by default)
     */
    public function asInteger(int $roundingMode = \PHP_ROUND_HALF_UP): int
    {
        return (int) \round((float) $this->number, mode: $roundingMode);
    }

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

        return new self((string) \round((float) $this->number, $decimalPlaces, mode: $roundingMode));
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

        return new self($newNumber);
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

        return new self($newNumber);
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

        return new self($newNumber);
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

        return new self($newNumber);
    }

    /**
     * @return $this Returns a new instance of \ElliotJReed\Maths\Number
     */
    public function squareRoot(): self
    {
        $newNumber = \bcsqrt($this->number, $this->precision);

        return new self($newNumber);
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

        return new self($newNumber);
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

        return new self($newNumber);
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

        return new self($newNumber);
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $number the comparator number
     *
     * @return bool returns true when the comparator number is less than the base number, or false when the comparator number is greater than or equal to the "base" number
     */
    public function isLessThan(self | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return -1 === $result;
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $number the comparator number
     *
     * @return bool returns true when the comparator number is greater than the base number, or false when the comparator number is less than or equal to the "base" number
     */
    public function isGreaterThan(self | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return 1 === $result;
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $number the comparator number
     *
     * @return bool returns true when the comparator number is equal to the base number, or false when the comparator number is less than or greater than the "base" number
     */
    public function isEqualTo(self | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return 0 === $result;
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $number the comparator number
     *
     * @return bool returns true when the comparator number is less than or equal to the base number, or false when the comparator number is greater than the "base" number
     */
    public function isLessThanOrEqualTo(self | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return -1 === $result || 0 === $result;
    }

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $number the comparator number
     *
     * @return bool returns true when the comparator number is greater than or equal to the base number, or false when the comparator number is less than the "base" number
     */
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
