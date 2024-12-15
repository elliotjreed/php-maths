<?php

declare(strict_types=1);

namespace ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\InvalidDecimalPlaces;
use ElliotJReed\Maths\Exception\NonNumericValue;

abstract class NumberFormat
{
    protected string $number;

    /**
     * @param Number|NumberImmutable|int|float|string $number    (Optional) The "base" number. Default: 0
     * @param int                                     $precision (Optional) The number of digits after the decimal place in the result. Default: 24
     *
     * @throws NonNumericValue thrown when number argument is not numeric
     */
    public function __construct(
        NumberImmutable | Number | int | float | string $number = 0,
        protected readonly int $precision = 24
    ) {
        $this->number = $this->castNumberToString($number);
    }

    /**
     * Returns the number instance as a string with optional decimal places and thousands separator formatting.
     *
     * @param int|null $decimalPlaces      (Optional) The number of decimal places to return (note: this will not round the number, for rounding use the roundToDecimalPlaces method)
     * @param string   $thousandsSeparator (Optional) Thousands separator. Default: empty string (i.e. none)
     *
     * @return string the number formatted as a string
     *
     * @throws InvalidDecimalPlaces thrown when the decimal places argument is less than zero
     */
    public function asString(?int $decimalPlaces = null, string $thousandsSeparator = ''): string
    {
        if (null !== $decimalPlaces) {
            if ($decimalPlaces < 0) {
                throw new InvalidDecimalPlaces('Decimal places must be a whole number greater than or equal to 0. Invalid decimal places number: ' . $decimalPlaces);
            }

            return \number_format((float) $this->number, $decimalPlaces, '.', $thousandsSeparator);
        }

        $number = $this->number;
        if (\str_contains($this->number, '.')) {
            $number = \rtrim($this->number, '0');
        }

        return \rtrim($number, '.') ?: '0';
    }

    /**
     * Returns the number as a float.
     *
     * @return float the number formatted as a float (decimal)
     */
    public function asFloat(): float
    {
        return (float) $this->number;
    }

    /**
     * Returns the number as a rounded integer with optional rounding mode.
     *
     * @param 1|2|3|4 $roundingMode (Optional) The rounding method defined by PHP internal maths constants [PHP_ROUND_HALF_UP (1) | PHP_ROUND_HALF_DOWN (2) | PHP_ROUND_HALF_EVEN (3) | PHP_ROUND_HALF_ODD (4)]. Default: PHP_ROUND_HALF_UP (1)
     *
     * @return int the number formatted as an integer (rounded up by default)
     */
    public function asInteger(int $roundingMode = \PHP_ROUND_HALF_UP): int
    {
        return (int) \round((float) $this->number, mode: $roundingMode);
    }

    /**
     * Determines whether the "base" number is less than the specified comparator number.
     *
     * @param Number|NumberImmutable|int|float|string $number the comparator number
     *
     * @return bool returns true when the "base" number is less than the comparator number, or false when the "base" number is greater than or equal to the comparator number
     */
    public function isLessThan(self | Number | NumberImmutable | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return -1 === $result;
    }

    /**
     * Determines whether the "base" number is greater than the specified comparator number.
     *
     * @param Number|NumberImmutable|int|float|string $number the comparator number
     *
     * @return bool returns true when the "base" number is greater than the comparator number, or false when the "base" number is less than or equal to the comparator number
     */
    public function isGreaterThan(self | Number | NumberImmutable | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return 1 === $result;
    }

    /**
     * Determines whether the "base" number is equal to the specified comparator number.
     *
     * @param Number|NumberImmutable|int|float|string $number the comparator number
     *
     * @return bool returns true when the "base" number is equal to the comparator number, or false when the "base" number is less than or greater than the comparator number
     */
    public function isEqualTo(self | Number | NumberImmutable | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return 0 === $result;
    }

    /**
     * Determines whether the "base" number is less than or equal to the specified comparator number.
     *
     * @param Number|NumberImmutable|int|float|string $number the comparator number
     *
     * @return bool returns true when the "base" number is less than or equal to the comparator number, or false when the "base" number is greater than the comparator number
     */
    public function isLessThanOrEqualTo(self | Number | NumberImmutable | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return -1 === $result || 0 === $result;
    }

    /**
     * Determines whether the "base" number is greater than or equal to the specified comparator number.
     *
     * @param Number|NumberImmutable|int|float|string $number the comparator number
     *
     * @return bool returns true when the "base" number is greater than or equal to the comparator number, or false when the "base" number is less than the comparator number
     */
    public function isGreaterThanOrEqualTo(self | Number | NumberImmutable | int | float | string $number): bool
    {
        $numberAsString = $this->castNumberToString($number);

        $result = \bccomp($this->number, $numberAsString, $this->precision);

        return 1 === $result || 0 === $result;
    }

    /**
     * Determines whether the "base" number is zero.
     *
     * @return bool returns true when the "base" number is equal to zero, or false when the "base" number is less than or greater than zero
     */
    public function isZero(): bool
    {
        $result = \bccomp($this->number, '0', $this->precision);

        return 0 === $result;
    }

    protected function castNumberToString(self | Number | NumberImmutable | int | float | string $number): string
    {
        if ($number instanceof self) {
            return $number->number;
        }

        if (\is_int($number)) {
            return (string) $number;
        }

        if (\is_string($number)) {
            if (!\is_numeric($number)) {
                throw new NonNumericValue('Non-numeric string provided. Value provided: ' . $number);
            }

            return $this->formatNumericString($number);
        }

        $numberAsString = (string) $number;

        if (\str_contains(\strtolower($numberAsString), 'e')) {
            $numberAsString = \number_format($number, $this->precision, '.', '');
        }

        if (\str_contains($numberAsString, '.')) {
            $numberAsString = \rtrim($numberAsString, '0');

            $precision = \strlen(\substr($numberAsString, \strpos($numberAsString, '.') + 1));

            $numberAsString = \number_format($number, $precision, '.', '');
        }

        return $this->formatNumericString($numberAsString);
    }

    private function formatNumericString(string $number): string
    {
        if (\str_contains(\strtolower($number), 'e')) {
            $number = \sprintf('%.20f', $number);
        }

        if (\str_contains($number, '.')) {
            $number = \rtrim($number, '0');

            $number = \ltrim($number, '0') ?: '0';
            if (\str_starts_with($number, '.')) {
                $number = '0' . $number;
            }
        }

        return \rtrim($number, '.');
    }
}
