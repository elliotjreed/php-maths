<?php

declare(strict_types=1);

namespace ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\InvalidDecimalPlaces;

abstract class NumberFormat
{
    protected string $number;

    /**
     * @param \ElliotJReed\Maths\Number|int|float|string $number    (Optional) The "base" number. Default: 0
     * @param int                                        $precision (Optional) The number of digits after the decimal place in the result. Default: 256
     */
    public function __construct(self | int | float | string $number = 0, protected readonly int $precision = 256)
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

        $number = $this->number;
        if (\str_contains($this->number, '.')) {
            $number = \rtrim($this->number, '0');
        }

        return \rtrim($number, '.') ?: '0';
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

    protected function castNumberToString(self | int | float | string $number): string
    {
        if ($number instanceof self) {
            $numberAsString = $number->asString();
        } else {
            $numberAsString = (string) $number;
        }

        return $numberAsString;
    }
}
