<?php

declare(strict_types=1);

namespace Tests\ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\DivisionByZero;
use ElliotJReed\Maths\Exception\InvalidDecimalPlaces;
use ElliotJReed\Maths\Exception\InvalidExponent;
use ElliotJReed\Maths\Exception\InvalidPowerModulusDivisor;
use ElliotJReed\Maths\Exception\NonNumericValue;
use ElliotJReed\Maths\NumberImmutable;
use PHPUnit\Framework\TestCase;

final class NumberImmutableTest extends TestCase
{
    public function testItThrowsExceptionWhenNonNumericValueProvided(): void
    {
        $this->expectException(NonNumericValue::class);
        $this->expectExceptionMessage('Non-numeric string provided. Value provided: DEFINITELY NOT NUMERIC');

        new NumberImmutable('DEFINITELY NOT NUMERIC');
    }

    public function testItReturnsNumberWhenBaseNumberIsInScientificNotation(): void
    {
        $number = new NumberImmutable('8.431e-05');

        $this->assertSame('0.00008431', $number->asString());
        $this->assertSame(0.00008431, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberWhenNegativeBaseNumberIsInScientificNotation(): void
    {
        $number = new NumberImmutable('-8.431e-05');

        $this->assertSame('-0.00008431', $number->asString());
        $this->assertSame(-0.00008431, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberAsStringToDefinedDecimalPlaces(): void
    {
        $number = new NumberImmutable(10000.29533);

        $this->assertSame('10000.30', $number->asString(2));
        $this->assertSame('10000.29533', $number->asString());
        $this->assertSame(10000.29533, $number->asFloat());
    }

    public function testItReturnsNumberAsWholeNumberStringToZeroDefinedDecimalPlaces(): void
    {
        $number = new NumberImmutable(10000.29533);

        $this->assertSame('10000', $number->asString(0));
        $this->assertSame('10000.29533', $number->asString());
        $this->assertSame(10000.29533, $number->asFloat());
    }

    public function testItReturnsNumberAsStringToDefinedThousandsSeparator(): void
    {
        $number = new NumberImmutable(10000.29533);

        $this->assertSame('10,000.30', $number->asString(2, ','));
        $this->assertSame('10000.29533', $number->asString());
        $this->assertSame(10000.29533, $number->asFloat());
    }

    public function testItThrowsExceptionWhenDecimalPlacesArgumentIsLessThanZeroWhenReturningNumberAsAString(): void
    {
        $number = new NumberImmutable('1.005');

        $this->expectException(InvalidDecimalPlaces::class);
        $this->expectExceptionMessage(
            'Decimal places must be a whole number greater than or equal to 0. Invalid decimal places number: -2'
        );

        $number->asString(-2);
    }

    public function testItMultipliesFloatingPointNumberAsFloat(): void
    {
        $number = new NumberImmutable(0.295);
        $newNumber = $number->multiply(100);

        $this->assertSame('29.5', $newNumber->asString());
        $this->assertSame(29.5, $newNumber->asFloat());
        $this->assertSame(30, $newNumber->asInteger());
        $this->assertSame(29, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('0.295', $number->asString());
        $this->assertSame(0.295, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesFloatingPointNumberAsString(): void
    {
        $number = new NumberImmutable('0.295');
        $newNumber = $number->multiply('100');

        $this->assertSame('29.5', $newNumber->asString());
        $this->assertSame(29.5, $newNumber->asFloat());
        $this->assertSame(30, $newNumber->asInteger());
        $this->assertSame(29, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('0.295', $number->asString());
        $this->assertSame(0.295, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesFloatingPointNumberAsNumberObject(): void
    {
        $number = new NumberImmutable('0.295');
        $newNumber = $number->multiply(new NumberImmutable(100));

        $this->assertSame('29.5', $newNumber->asString());
        $this->assertSame(29.5, $newNumber->asFloat());
        $this->assertSame(30, $newNumber->asInteger());
        $this->assertSame(29, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('0.295', $number->asString());
        $this->assertSame(0.295, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new NumberImmutable(0.333);
        $newNumber = $number->multiply(1.861, 102.5);

        $this->assertSame('63.5205825', $newNumber->asString());
        $this->assertSame(63.5205825, $newNumber->asFloat());
        $this->assertSame(64, $newNumber->asInteger());
        $this->assertSame(64, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('0.333', $number->asString());
        $this->assertSame(0.333, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new NumberImmutable(0.333);
        $newNumber = $number->multiply(1.861)->multiply(102.5);

        $this->assertSame('63.5205825', $newNumber->asString());
        $this->assertSame(63.5205825, $newNumber->asFloat());
        $this->assertSame(64, $newNumber->asInteger());
        $this->assertSame(64, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('0.333', $number->asString());
        $this->assertSame(0.333, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable(22);
        $newNumber = $number->multiply(2.5, 1.1);

        $this->assertSame('60.5', $newNumber->asString());
        $this->assertSame(60.5, $newNumber->asFloat());
        $this->assertSame(61, $newNumber->asInteger());
        $this->assertSame(60, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22', $number->asString());
        $this->assertSame(22.0, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable(22);
        $newNumber = $number->multiply(2.5)->multiply(1.1);

        $this->assertSame('60.5', $newNumber->asString());
        $this->assertSame(60.5, $newNumber->asFloat());
        $this->assertSame(61, $newNumber->asInteger());
        $this->assertSame(60, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22', $number->asString());
        $this->assertSame(22.0, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsString(): void
    {
        $number = new NumberImmutable('0.333');
        $newNumber = $number->multiply('1.861', '102.5');

        $this->assertSame('63.5205825', $newNumber->asString());
        $this->assertSame(63.5205825, $newNumber->asFloat());
        $this->assertSame(64, $newNumber->asInteger());
        $this->assertSame(64, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('0.333', $number->asString());
        $this->assertSame(0.333, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new NumberImmutable('0.333');
        $newNumber = $number->multiply('1.861')->multiply('102.5');

        $this->assertSame('63.5205825', $newNumber->asString());
        $this->assertSame(63.5205825, $newNumber->asFloat());
        $this->assertSame(64, $newNumber->asInteger());
        $this->assertSame(64, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('0.333', $number->asString());
        $this->assertSame(0.333, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable('22');
        $newNumber = $number->multiply('2.5', '1.1');

        $this->assertSame('60.5', $newNumber->asString());
        $this->assertSame(60.5, $newNumber->asFloat());
        $this->assertSame(61, $newNumber->asInteger());
        $this->assertSame(60, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22', $number->asString());
        $this->assertSame(22.0, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable('22');
        $newNumber = $number->multiply('2.5')->multiply('1.1');

        $this->assertSame('60.5', $newNumber->asString());
        $this->assertSame(60.5, $newNumber->asFloat());
        $this->assertSame(61, $newNumber->asInteger());
        $this->assertSame(60, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22', $number->asString());
        $this->assertSame(22.0, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsFloatingPointNumberAsFloat(): void
    {
        $number = new NumberImmutable(1.295);
        $newNumber = $number->add(1.333);

        $this->assertSame('2.628', $newNumber->asString());
        $this->assertSame(2.628, $newNumber->asFloat());
        $this->assertSame(3, $newNumber->asInteger());
        $this->assertSame(3, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('1.295', $number->asString());
        $this->assertSame(1.295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsFloatingPointNumberAsString(): void
    {
        $number = new NumberImmutable('1.295');
        $newNumber = $number->add('1.333');

        $this->assertSame('2.628', $newNumber->asString());
        $this->assertSame(2.628, $newNumber->asFloat());
        $this->assertSame(3, $newNumber->asInteger());
        $this->assertSame(3, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('1.295', $number->asString());
        $this->assertSame(1.295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsFloatingPointNumberAsNumberObject(): void
    {
        $number = new NumberImmutable('1.295');
        $newNumber = $number->add(new NumberImmutable(1.333));

        $this->assertSame('2.628', $newNumber->asString());
        $this->assertSame(2.628, $newNumber->asFloat());
        $this->assertSame(3, $newNumber->asInteger());
        $this->assertSame(3, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('1.295', $number->asString());
        $this->assertSame(1.295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new NumberImmutable(0.333);
        $newNumber = $number->add(1.861, 102.5);

        $this->assertSame('104.694', $newNumber->asString());
        $this->assertSame(104.694, $newNumber->asFloat());
        $this->assertSame(105, $newNumber->asInteger());
        $this->assertSame(105, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('0.333', $number->asString());
        $this->assertSame(0.333, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new NumberImmutable(0.333);
        $newNumber = $number->add(1.861)->add(102.5);

        $this->assertSame('104.694', $newNumber->asString());
        $this->assertSame(104.694, $newNumber->asFloat());
        $this->assertSame(105, $newNumber->asInteger());
        $this->assertSame(105, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('0.333', $number->asString());
        $this->assertSame(0.333, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable(22.2);
        $newNumber = $number->add(2.13, 1.17);

        $this->assertSame('25.5', $newNumber->asString());
        $this->assertSame(25.5, $newNumber->asFloat());
        $this->assertSame(26, $newNumber->asInteger());
        $this->assertSame(25, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22.2', $number->asString());
        $this->assertSame(22.2, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable(22.2);
        $newNumber = $number->add(2.13)->add(1.17);

        $this->assertSame('25.5', $newNumber->asString());
        $this->assertSame(25.5, $newNumber->asFloat());
        $this->assertSame(26, $newNumber->asInteger());
        $this->assertSame(25, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22.2', $number->asString());
        $this->assertSame(22.2, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumberImmutable(): void
    {
        $number = new NumberImmutable(22.2);
        $newNumber = $number->add(2.63, 1.17);

        $this->assertSame('26', $newNumber->asString());
        $this->assertSame(26.0, $newNumber->asFloat());
        $this->assertSame(26, $newNumber->asInteger());
        $this->assertSame(26, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22.2', $number->asString());
        $this->assertSame(22.2, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumberWhenChained(): void
    {
        $number = new NumberImmutable(22.2);
        $newNumber = $number->add(2.63)->add(1.17);

        $this->assertSame('26', $newNumber->asString());
        $this->assertSame(26.0, $newNumber->asFloat());
        $this->assertSame(26, $newNumber->asInteger());
        $this->assertSame(26, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22.2', $number->asString());
        $this->assertSame(22.2, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsString(): void
    {
        $number = new NumberImmutable('0.333');
        $newNumber = $number->add('1.861', '102.5');

        $this->assertSame('104.694', $newNumber->asString());
        $this->assertSame(104.694, $newNumber->asFloat());
        $this->assertSame(105, $newNumber->asInteger());
        $this->assertSame(105, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('0.333', $number->asString());
        $this->assertSame(0.333, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new NumberImmutable('0.333');
        $newNumber = $number->add('1.861')->add('102.5');

        $this->assertSame('104.694', $newNumber->asString());
        $this->assertSame(104.694, $newNumber->asFloat());
        $this->assertSame(105, $newNumber->asInteger());
        $this->assertSame(105, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('0.333', $number->asString());
        $this->assertSame(0.333, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable('22.2');
        $newNumber = $number->add('2.13', '1.17');

        $this->assertSame('25.5', $newNumber->asString());
        $this->assertSame(25.5, $newNumber->asFloat());
        $this->assertSame(26, $newNumber->asInteger());
        $this->assertSame(25, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22.2', $number->asString());
        $this->assertSame(22.2, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable('22.2');
        $newNumber = $number->add('2.13')->add('1.17');

        $this->assertSame('25.5', $newNumber->asString());
        $this->assertSame(25.5, $newNumber->asFloat());
        $this->assertSame(26, $newNumber->asInteger());
        $this->assertSame(25, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22.2', $number->asString());
        $this->assertSame(22.2, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsFloatingPointNumberAsFloat(): void
    {
        $number = new NumberImmutable(1.295);
        $newNumber = $number->subtract(2.333);

        $this->assertSame('-1.038', $newNumber->asString());
        $this->assertSame(-1.038, $newNumber->asFloat());
        $this->assertSame(-1, $newNumber->asInteger());
        $this->assertSame(-1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('1.295', $number->asString());
        $this->assertSame(1.295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsFloatingPointNumberAsString(): void
    {
        $number = new NumberImmutable('1.295');
        $newNumber = $number->subtract('2.333');

        $this->assertSame('-1.038', $newNumber->asString());
        $this->assertSame(-1.038, $newNumber->asFloat());
        $this->assertSame(-1, $newNumber->asInteger());
        $this->assertSame(-1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('1.295', $number->asString());
        $this->assertSame(1.295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsFloatingPointNumberAsNumberObject(): void
    {
        $number = new NumberImmutable('1.295');
        $newNumber = $number->subtract(new NumberImmutable(2.333));

        $this->assertSame('-1.038', $newNumber->asString());
        $this->assertSame(-1.038, $newNumber->asFloat());
        $this->assertSame(-1, $newNumber->asInteger());
        $this->assertSame(-1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('1.295', $number->asString());
        $this->assertSame(1.295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new NumberImmutable(3.333);
        $newNumber = $number->subtract(1.861, 0.5);

        $this->assertSame('0.972', $newNumber->asString());
        $this->assertSame(0.972, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('3.333', $number->asString());
        $this->assertSame(3.333, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new NumberImmutable(3.333);
        $newNumber = $number->subtract(1.861)->subtract(0.5);

        $this->assertSame('0.972', $newNumber->asString());
        $this->assertSame(0.972, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('3.333', $number->asString());
        $this->assertSame(3.333, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable(3.5);
        $newNumber = $number->subtract(1.5, 0.5);

        $this->assertSame('1.5', $newNumber->asString());
        $this->assertSame(1.5, $newNumber->asFloat());
        $this->assertSame(2, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable(3.5);
        $newNumber = $number->subtract(1.5)->subtract(0.5);

        $this->assertSame('1.5', $newNumber->asString());
        $this->assertSame(1.5, $newNumber->asFloat());
        $this->assertSame(2, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumberImmutable(): void
    {
        $number = new NumberImmutable(22.63);
        $newNumber = $number->subtract(2.12, 1.51);

        $this->assertSame('19', $newNumber->asString());
        $this->assertSame(19.0, $newNumber->asFloat());
        $this->assertSame(19, $newNumber->asInteger());
        $this->assertSame(19, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22.63', $number->asString());
        $this->assertSame(22.63, $number->asFloat());
        $this->assertSame(23, $number->asInteger());
        $this->assertSame(23, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumberWhenChained(): void
    {
        $number = new NumberImmutable(22.63);
        $newNumber = $number->subtract(2.12)->subtract(1.51);

        $this->assertSame('19', $newNumber->asString());
        $this->assertSame(19.0, $newNumber->asFloat());
        $this->assertSame(19, $newNumber->asInteger());
        $this->assertSame(19, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('22.63', $number->asString());
        $this->assertSame(22.63, $number->asFloat());
        $this->assertSame(23, $number->asInteger());
        $this->assertSame(23, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsString(): void
    {
        $number = new NumberImmutable('3.333');
        $newNumber = $number->subtract('1.861', '0.5');

        $this->assertSame('0.972', $newNumber->asString());
        $this->assertSame(0.972, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('3.333', $number->asString());
        $this->assertSame(3.333, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new NumberImmutable('3.333');
        $newNumber = $number->subtract('1.861')->subtract('0.5');

        $this->assertSame('0.972', $newNumber->asString());
        $this->assertSame(0.972, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('3.333', $number->asString());
        $this->assertSame(3.333, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable('3.5');
        $newNumber = $number->subtract('1.5', '0.5');

        $this->assertSame('1.5', $newNumber->asString());
        $this->assertSame(1.5, $newNumber->asFloat());
        $this->assertSame(2, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable('3.5');
        $newNumber = $number->subtract('1.5')->subtract('0.5');

        $this->assertSame('1.5', $newNumber->asString());
        $this->assertSame(1.5, $newNumber->asFloat());
        $this->assertSame(2, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesFloatingPointNumberAsFloat(): void
    {
        $number = new NumberImmutable(10.295);
        $newNumber = $number->divide(10);

        $this->assertSame('1.0295', $newNumber->asString());
        $this->assertSame(1.0295, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('10.295', $number->asString());
        $this->assertSame(10.295, $number->asFloat());
        $this->assertSame(10, $number->asInteger());
        $this->assertSame(10, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesFloatingPointNumberAsString(): void
    {
        $number = new NumberImmutable('10.295');
        $newNumber = $number->divide('10');

        $this->assertSame('1.0295', $newNumber->asString());
        $this->assertSame(1.0295, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('10.295', $number->asString());
        $this->assertSame(10.295, $number->asFloat());
        $this->assertSame(10, $number->asInteger());
        $this->assertSame(10, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesFloatingPointNumberAsNumberObject(): void
    {
        $number = new NumberImmutable('10.295');
        $newNumber = $number->divide(new NumberImmutable(10));

        $this->assertSame('1.0295', $newNumber->asString());
        $this->assertSame(1.0295, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('10.295', $number->asString());
        $this->assertSame(10.295, $number->asFloat());
        $this->assertSame(10, $number->asInteger());
        $this->assertSame(10, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new NumberImmutable(70.5);
        $newNumber = $number->divide(1.25, 4.8);

        $this->assertSame('11.75', $newNumber->asString());
        $this->assertSame(11.75, $newNumber->asFloat());
        $this->assertSame(12, $newNumber->asInteger());
        $this->assertSame(12, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('70.5', $number->asString());
        $this->assertSame(70.5, $number->asFloat());
        $this->assertSame(71, $number->asInteger());
        $this->assertSame(70, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new NumberImmutable(70.5);
        $newNumber = $number->divide(1.25)->divide(4.8);

        $this->assertSame('11.75', $newNumber->asString());
        $this->assertSame(11.75, $newNumber->asFloat());
        $this->assertSame(12, $newNumber->asInteger());
        $this->assertSame(12, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('70.5', $number->asString());
        $this->assertSame(70.5, $number->asFloat());
        $this->assertSame(71, $number->asInteger());
        $this->assertSame(70, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable(148.8375);
        $newNumber = $number->divide(3.5, 12.15);

        $this->assertSame('3.5', $newNumber->asString());
        $this->assertSame(3.5, $newNumber->asFloat());
        $this->assertSame(4, $newNumber->asInteger());
        $this->assertSame(3, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('148.8375', $number->asString());
        $this->assertSame(148.8375, $number->asFloat());
        $this->assertSame(149, $number->asInteger());
        $this->assertSame(149, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable(148.8375);
        $newNumber = $number->divide(3.5)->divide(12.15);

        $this->assertSame('3.5', $newNumber->asString());
        $this->assertSame(3.5, $newNumber->asFloat());
        $this->assertSame(4, $newNumber->asInteger());
        $this->assertSame(3, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('148.8375', $number->asString());
        $this->assertSame(148.8375, $number->asFloat());
        $this->assertSame(149, $number->asInteger());
        $this->assertSame(149, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsString(): void
    {
        $number = new NumberImmutable('70.5');
        $newNumber = $number->divide('1.25', '4.8');

        $this->assertSame('11.75', $newNumber->asString());
        $this->assertSame(11.75, $newNumber->asFloat());
        $this->assertSame(12, $newNumber->asInteger());
        $this->assertSame(12, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('70.5', $number->asString());
        $this->assertSame(70.5, $number->asFloat());
        $this->assertSame(71, $number->asInteger());
        $this->assertSame(70, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new NumberImmutable('70.5');
        $newNumber = $number->divide('1.25')->divide('4.8');

        $this->assertSame('11.75', $newNumber->asString());
        $this->assertSame(11.75, $newNumber->asFloat());
        $this->assertSame(12, $newNumber->asInteger());
        $this->assertSame(12, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('70.5', $number->asString());
        $this->assertSame(70.5, $number->asFloat());
        $this->assertSame(71, $number->asInteger());
        $this->assertSame(70, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable('148.8375');
        $newNumber = $number->divide('3.5', '12.15');

        $this->assertSame('3.5', $newNumber->asString());
        $this->assertSame(3.5, $newNumber->asFloat());
        $this->assertSame(4, $newNumber->asInteger());
        $this->assertSame(3, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('148.8375', $number->asString());
        $this->assertSame(148.8375, $number->asFloat());
        $this->assertSame(149, $number->asInteger());
        $this->assertSame(149, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable('148.8375');
        $newNumber = $number->divide('3.5')->divide('12.15');

        $this->assertSame('3.5', $newNumber->asString());
        $this->assertSame(3.5, $newNumber->asFloat());
        $this->assertSame(4, $newNumber->asInteger());
        $this->assertSame(3, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('148.8375', $number->asString());
        $this->assertSame(148.8375, $number->asFloat());
        $this->assertSame(149, $number->asInteger());
        $this->assertSame(149, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItThrowsExceptionWhenDividingBaseNumberByZeroWhenNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(100);

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide(0);
    }

    public function testItThrowsExceptionWhenDividingBaseNumberByZeroWhenNumberIsAFloat(): void
    {
        $number = new NumberImmutable(100.123);

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide(0.0);
    }

    public function testItThrowsExceptionWhenDividingBaseNumberByZeroWhenNumberIsAString(): void
    {
        $number = new NumberImmutable('100.123');

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide('0.0');
    }

    public function testItThrowsExceptionWhenDividingBaseNumberWhenBaseNumberIsAnIntegerAndIsZero(): void
    {
        $number = new NumberImmutable(0);

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide(100);
    }

    public function testItThrowsExceptionWhenDividingBaseNumberWhenNumberIsAFloatAndIsZero(): void
    {
        $number = new NumberImmutable(0);

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide(123.456);
    }

    public function testItThrowsExceptionWhenDividingBaseNumberWhenNumberIsAStringAndIsZero(): void
    {
        $number = new NumberImmutable('0');

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide('33.99');
    }

    public function testItReturnsTheSquareRootWhenBaseNumberIsAFloat(): void
    {
        $number = new NumberImmutable(30.25);
        $newNumber = $number->squareRoot();

        $this->assertSame('5.5', $newNumber->asString());
        $this->assertSame(5.5, $newNumber->asFloat());
        $this->assertSame(6, $newNumber->asInteger());
        $this->assertSame(5, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('30.25', $number->asString());
        $this->assertSame(30.25, $number->asFloat());
        $this->assertSame(30, $number->asInteger());
        $this->assertSame(30, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsTheSquareRootWhenBaseNumberIsAString(): void
    {
        $number = new NumberImmutable('30.25');
        $newNumber = $number->squareRoot();

        $this->assertSame('5.5', $newNumber->asString());
        $this->assertSame(5.5, $newNumber->asFloat());
        $this->assertSame(6, $newNumber->asInteger());
        $this->assertSame(5, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('30.25', $number->asString());
        $this->assertSame(30.25, $number->asFloat());
        $this->assertSame(30, $number->asInteger());
        $this->assertSame(30, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsTheSquareRootWhenBaseNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(25);
        $newNumber = $number->squareRoot();

        $this->assertSame('5', $newNumber->asString());
        $this->assertSame(5.0, $newNumber->asFloat());
        $this->assertSame(5, $newNumber->asInteger());
        $this->assertSame(5, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('25', $number->asString());
        $this->assertSame(25.0, $number->asFloat());
        $this->assertSame(25, $number->asInteger());
        $this->assertSame(25, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenBaseNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(5);
        $newNumber = $number->modulus(3);

        $this->assertSame('2', $newNumber->asString());
        $this->assertSame(2.0, $newNumber->asFloat());
        $this->assertSame(2, $newNumber->asInteger());
        $this->assertSame(2, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('5', $number->asString());
        $this->assertSame(5.0, $number->asFloat());
        $this->assertSame(5, $number->asInteger());
        $this->assertSame(5, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenBaseNumberIsAFloat(): void
    {
        $number = new NumberImmutable(5.5);
        $newNumber = $number->modulus(2.5);

        $this->assertSame('0.5', $newNumber->asString());
        $this->assertSame(0.5, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(0, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('5.5', $number->asString());
        $this->assertSame(5.5, $number->asFloat());
        $this->assertSame(6, $number->asInteger());
        $this->assertSame(5, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenBaseNumberIsAString(): void
    {
        $number = new NumberImmutable('5.5');
        $newNumber = $number->modulus('2.5');

        $this->assertSame('0.5', $newNumber->asString());
        $this->assertSame(0.5, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(0, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('5.5', $number->asString());
        $this->assertSame(5.5, $number->asFloat());
        $this->assertSame(6, $number->asInteger());
        $this->assertSame(5, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenModulusIsANumberObject(): void
    {
        $number = new NumberImmutable('5.5');
        $newNumber = $number->modulus(new NumberImmutable(2.5));

        $this->assertSame('0.5', $newNumber->asString());
        $this->assertSame(0.5, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(0, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('5.5', $number->asString());
        $this->assertSame(5.5, $number->asFloat());
        $this->assertSame(6, $number->asInteger());
        $this->assertSame(5, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberRaisedToPowerExponentWhenBaseNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(5);
        $newNumber = $number->raiseToPower(3);

        $this->assertSame('125', $newNumber->asString());
        $this->assertSame(125.0, $newNumber->asFloat());
        $this->assertSame(125, $newNumber->asInteger());
        $this->assertSame(125, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('5', $number->asString());
        $this->assertSame(5.0, $number->asFloat());
        $this->assertSame(5, $number->asInteger());
        $this->assertSame(5, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberRaisedToPowerExponentWhenBaseNumberIsAFloat(): void
    {
        $number = new NumberImmutable(2.75);
        $newNumber = $number->raiseToPower(2);

        $this->assertSame('7.5625', $newNumber->asString());
        $this->assertSame(7.5625, $newNumber->asFloat());
        $this->assertSame(8, $newNumber->asInteger());
        $this->assertSame(8, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('2.75', $number->asString());
        $this->assertSame(2.75, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberRaisedToPowerExponentWhenExponentIsANumberObject(): void
    {
        $number = new NumberImmutable(2.75);
        $newNumber = $number->raiseToPower(new NumberImmutable(2));

        $this->assertSame('7.5625', $newNumber->asString());
        $this->assertSame(7.5625, $newNumber->asFloat());
        $this->assertSame(8, $newNumber->asInteger());
        $this->assertSame(8, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('2.75', $number->asString());
        $this->assertSame(2.75, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItThrowsExceptionWhenExponentIsNotAWholeNumberWhenRaisingToPower(): void
    {
        $number = new NumberImmutable(25);

        $this->expectException(InvalidExponent::class);
        $this->expectExceptionMessage('Exponent must be a whole number. Invalid exponent: 1.5');

        $number->raiseToPower(1.5);
    }

    public function testItThrowsExceptionWhenExponentIsNotAWholeNumberWhenRaisingToPowerAndExponentIsANumberObject(): void
    {
        $number = new NumberImmutable(25);

        $this->expectException(InvalidExponent::class);
        $this->expectExceptionMessage('Exponent must be a whole number. Invalid exponent: 1.5');

        $number->raiseToPower(new NumberImmutable(1.5));
    }

    public function testItReturnsNumberrRaisedToPowerExponentAndReducedByModulusWhenBaseNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(5371);
        $newNumber = $number->raiseToPowerReduceByModulus(2, 7);

        $this->assertSame('4', $newNumber->asString());
        $this->assertSame(4.0, $newNumber->asFloat());
        $this->assertSame(4, $newNumber->asInteger());
        $this->assertSame(4, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('5371', $number->asString());
        $this->assertSame(5371.0, $number->asFloat());
        $this->assertSame(5371, $number->asInteger());
        $this->assertSame(5371, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberRaisedToPowerExponentAndReducedByModulusWhenBaseNumberIsAString(): void
    {
        $number = new NumberImmutable('5371');
        $newNumber = $number->raiseToPowerReduceByModulus(2, 7);

        $this->assertSame('4', $newNumber->asString());
        $this->assertSame(4.0, $newNumber->asFloat());
        $this->assertSame(4, $newNumber->asInteger());
        $this->assertSame(4, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('5371', $number->asString());
        $this->assertSame(5371.0, $number->asFloat());
        $this->assertSame(5371, $number->asInteger());
        $this->assertSame(5371, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberRaisedToPowerExponentAndReducedByModulusWhenExponentAndDivisorsAreNumberObjects(): void
    {
        $number = new NumberImmutable('5371');
        $newNumber = $number->raiseToPowerReduceByModulus(new NumberImmutable(2), new NumberImmutable(7));

        $this->assertSame('4', $newNumber->asString());
        $this->assertSame(4.0, $newNumber->asFloat());
        $this->assertSame(4, $newNumber->asInteger());
        $this->assertSame(4, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('5371', $number->asString());
        $this->assertSame(5371.0, $number->asFloat());
        $this->assertSame(5371, $number->asInteger());
        $this->assertSame(5371, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItThrowsExceptionWhenExponentIsNotAWholeNumberWhenRaisingToPowerAndReducingByModulus(): void
    {
        $number = new NumberImmutable('5371');

        $this->expectException(InvalidExponent::class);
        $this->expectExceptionMessage('Exponent must be a whole number. Invalid exponent: 2.2');

        $number->raiseToPowerReduceByModulus(2.2, 7);
    }

    public function testItThrowsExceptionWhenDivisorIsNotAWholeNumberWhenRaisingToPowerAndReducingByModulus(): void
    {
        $number = new NumberImmutable('5371');

        $this->expectException(InvalidPowerModulusDivisor::class);
        $this->expectExceptionMessage('Divisor must be a whole number. Invalid divisor: 7.5');

        $number->raiseToPowerReduceByModulus(2, 7.5);
    }

    public function testItReturnsTrueWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsLessThanBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('100.01');

        $this->assertTrue($number->isLessThan('100.02'));
    }

    public function testItThrowsExceptionWhenExponentIsNotAWholeNumberWhenRaisingToPowerAndReducingByModulusAndExponentAndDivisorsAreNumberObjects(): void
    {
        $number = new NumberImmutable('5371');

        $this->expectException(InvalidExponent::class);
        $this->expectExceptionMessage('Exponent must be a whole number. Invalid exponent: 2.2');

        $number->raiseToPowerReduceByModulus(new NumberImmutable(2.2), new NumberImmutable(7));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsLessThanBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('1.003');

        $this->assertFalse($number->isLessThan('1.002'));
    }

    public function testItReturnsFalseWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsLessThanBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('1.003');

        $this->assertFalse($number->isLessThan('1.003'));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsLessThanBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('1.003');

        $this->assertFalse($number->isLessThan(new NumberImmutable(1.002)));
    }

    public function testItReturnsFalseWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsLessThanBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('1.003');

        $this->assertFalse($number->isLessThan(new NumberImmutable(1.003)));
    }

    public function testItReturnsTrueWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('100.02');

        $this->assertTrue($number->isGreaterThan('100.01'));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('1.002');

        $this->assertFalse($number->isGreaterThan('1.003'));
    }

    public function testItReturnsFalseWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsGreaterThanBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('1.002');

        $this->assertFalse($number->isGreaterThan('1.002'));
    }

    public function testItReturnsTrueWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('100.02');

        $this->assertTrue($number->isGreaterThan(new NumberImmutable('100.01')));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('1.002');

        $this->assertFalse($number->isGreaterThan(new NumberImmutable('1.003')));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsEqualToTheBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('100.02');

        $this->assertFalse($number->isEqualTo('100.01'));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsEqualToTheBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('1.002');

        $this->assertFalse($number->isEqualTo('1.003'));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberEqualToTheThanBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('1.002');

        $this->assertTrue($number->isEqualTo('1.002'));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('100.02');

        $this->assertFalse($number->isEqualTo(new NumberImmutable('100.01')));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('1.002');

        $this->assertFalse($number->isEqualTo(new NumberImmutable('1.003')));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberEqualToTheThanBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('1.002');

        $this->assertTrue($number->isEqualTo(new NumberImmutable('1.002')));
    }

    public function testItReturnsTrueWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('100.01');

        $this->assertTrue($number->isLessThanOrEqualTo('100.02'));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('1.003');

        $this->assertFalse($number->isLessThanOrEqualTo('1.002'));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('1.003');

        $this->assertTrue($number->isLessThanOrEqualTo('1.003'));
    }

    public function testItReturnsTrueWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('100.01');

        $this->assertTrue($number->isLessThanOrEqualTo(new NumberImmutable('100.02')));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('1.003');

        $this->assertFalse($number->isLessThanOrEqualTo(new NumberImmutable('1.002')));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('1.003');

        $this->assertTrue($number->isLessThanOrEqualTo(new NumberImmutable('1.003')));
    }

    public function testItReturnsTrueWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('100.02');

        $this->assertTrue($number->isGreaterThanOrEqualTo('100.01'));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('1.002');

        $this->assertFalse($number->isGreaterThanOrEqualTo('1.003'));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumberImmutable(): void
    {
        $number = new NumberImmutable('1.002');

        $this->assertTrue($number->isGreaterThanOrEqualTo('1.002'));
    }

    public function testItReturnsTrueWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('100.02');

        $this->assertTrue($number->isGreaterThanOrEqualTo(new NumberImmutable('100.01')));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('1.002');

        $this->assertFalse($number->isGreaterThanOrEqualTo(new NumberImmutable('1.003')));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new NumberImmutable('1.002');

        $this->assertTrue($number->isGreaterThanOrEqualTo(new NumberImmutable('1.002')));
    }

    public function testItReturnsTrueWhenTheBaseNumberIsZeroWhenTheBaseNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(0);

        $this->assertTrue($number->isZero());
    }

    public function testItReturnsTrueWhenTheBaseNumberIsZeroWhenTheBaseNumberIsAFloat(): void
    {
        $number = new NumberImmutable(0.0);

        $this->assertTrue($number->isZero());
    }

    public function testItReturnsTrueWhenTheBaseNumberIsZeroWhenTheBaseNumberIsAString(): void
    {
        $number = new NumberImmutable('0.0');

        $this->assertTrue($number->isZero());
    }

    public function testItReturnsItFalseWhenTheBaseNumberIsNotZeroWhenTheBaseNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(-123);

        $this->assertFalse($number->isZero());
    }

    public function testItReturnsItFalseWhenTheBaseNumberIsNotZeroWhenTheBaseNumberIsAFloat(): void
    {
        $number = new NumberImmutable(0.000000000000000000000001);

        $this->assertFalse($number->isZero());
    }

    public function testItReturnsItFalseWhenTheBaseNumberIsNotZeroWhenTheBaseNumberIsANegativeFloat(): void
    {
        $number = new NumberImmutable(-0.000000000000000000000001);

        $this->assertFalse($number->isZero());
    }

    public function testItReturnsItFalseWhenTheBaseNumberIsNotZeroWhenTheBaseNumberIsAString(): void
    {
        $number = new NumberImmutable('-0.01');

        $this->assertFalse($number->isZero());
    }

    public function testItReturnsNumberSetToDefinedDecimalPlacesWhenRoundingUpByDefault(): void
    {
        $number = new NumberImmutable('1.005');
        $newNumber = $number->roundToDecimalPlaces(2);

        $this->assertSame('1.01', $newNumber->asString());
        $this->assertSame(1.01, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('1.005', $number->asString());
        $this->assertSame(1.005, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberSetToDefinedDecimalPlacesWhenRoundingDown(): void
    {
        $number = new NumberImmutable('1.005');
        $newNumber = $number->roundToDecimalPlaces(2, \PHP_ROUND_HALF_DOWN);

        $this->assertSame('1', $newNumber->asString());
        $this->assertSame(1.0, $newNumber->asFloat());
        $this->assertSame(1, $newNumber->asInteger());
        $this->assertSame(1, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('1.005', $number->asString());
        $this->assertSame(1.005, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItThrowsExceptionWhenDecimalPlacesArgumentIsLessThanZeroWhenRoundingNumberImmutable(): void
    {
        $number = new NumberImmutable('1.005');

        $this->expectException(InvalidDecimalPlaces::class);
        $this->expectExceptionMessage(
            'Decimal places must be a whole number greater than or equal to 0. Invalid decimal places number: -2'
        );

        $number->roundToDecimalPlaces(-2);
    }

    public function testItIncreasesBaseNumberByPercentageWhenNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(100);
        $newNumber = $number->increaseByPercentage(10);

        $this->assertSame('110', $newNumber->asString());
        $this->assertSame(110.0, $newNumber->asFloat());
        $this->assertSame(110, $newNumber->asInteger());
        $this->assertSame(110, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('100', $number->asString());
        $this->assertSame(100.0, $number->asFloat());
        $this->assertSame(100, $number->asInteger());
        $this->assertSame(100, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItIncreasesBaseNumberByPercentageWhenNumberIsAFloat(): void
    {
        $number = new NumberImmutable(100.50);
        $newNumber = $number->increaseByPercentage(10.125);

        $this->assertSame('110.675625', $newNumber->asString());
        $this->assertSame(110.675625, $newNumber->asFloat());
        $this->assertSame(111, $newNumber->asInteger());
        $this->assertSame(111, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('100.5', $number->asString());
        $this->assertSame(100.5, $number->asFloat());
        $this->assertSame(101, $number->asInteger());
        $this->assertSame(100, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItIncreasesBaseNumberByPercentageWhenNumberIsAString(): void
    {
        $number = new NumberImmutable('100.50');
        $newNumber = $number->increaseByPercentage('10.125');

        $this->assertSame('110.675625', $newNumber->asString());
        $this->assertSame(110.675625, $newNumber->asFloat());
        $this->assertSame(111, $newNumber->asInteger());
        $this->assertSame(111, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('100.5', $number->asString());
        $this->assertSame(100.5, $number->asFloat());
        $this->assertSame(101, $number->asInteger());
        $this->assertSame(100, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItIncreasesBaseNumberByNegativePercentageWhenNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(100);
        $newNumber = $number->increaseByPercentage(-10);

        $this->assertSame('90', $newNumber->asString());
        $this->assertSame(90.0, $newNumber->asFloat());
        $this->assertSame(90, $newNumber->asInteger());
        $this->assertSame(90, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('100', $number->asString());
        $this->assertSame(100.0, $number->asFloat());
        $this->assertSame(100, $number->asInteger());
        $this->assertSame(100, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItIncreasesBaseNumberByNegativePercentageWhenNumberIsAFloat(): void
    {
        $number = new NumberImmutable(-100.25);
        $newNumber = $number->increaseByPercentage(-10.55);

        $this->assertSame('-89.673625', $newNumber->asString());
        $this->assertSame(-89.673625, $newNumber->asFloat());
        $this->assertSame(-90, $newNumber->asInteger());
        $this->assertSame(-90, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('-100.25', $number->asString());
        $this->assertSame(-100.25, $number->asFloat());
        $this->assertSame(-100, $number->asInteger());
        $this->assertSame(-100, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItIncreasesBaseNumberByNegativePercentageWhenNumberIsAString(): void
    {
        $number = new NumberImmutable('100.33');
        $newNumber = $number->increaseByPercentage('-10.66');

        $this->assertSame('89.634822', $newNumber->asString());
        $this->assertSame(89.634822, $newNumber->asFloat());
        $this->assertSame(90, $newNumber->asInteger());
        $this->assertSame(90, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('100.33', $number->asString());
        $this->assertSame(100.33, $number->asFloat());
        $this->assertSame(100, $number->asInteger());
        $this->assertSame(100, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByPercentageWhenNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(100);
        $newNumber = $number->decreaseByPercentage(10);

        $this->assertSame('90', $newNumber->asString());
        $this->assertSame(90.0, $newNumber->asFloat());
        $this->assertSame(90, $newNumber->asInteger());
        $this->assertSame(90, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('100', $number->asString());
        $this->assertSame(100.0, $number->asFloat());
        $this->assertSame(100, $number->asInteger());
        $this->assertSame(100, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByPercentageWhenNumberIsAFloat(): void
    {
        $number = new NumberImmutable(10.99);
        $newNumber = $number->decreaseByPercentage(5.123);

        $this->assertSame('10.4269823', $newNumber->asString());
        $this->assertSame(10.4269823, $newNumber->asFloat());
        $this->assertSame(10, $newNumber->asInteger());
        $this->assertSame(10, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('10.99', $number->asString());
        $this->assertSame(10.99, $number->asFloat());
        $this->assertSame(11, $number->asInteger());
        $this->assertSame(11, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByPercentageWhenNumberIsAString(): void
    {
        $number = new NumberImmutable('10.99');
        $newNumber = $number->decreaseByPercentage('5.123');

        $this->assertSame('10.4269823', $newNumber->asString());
        $this->assertSame(10.4269823, $newNumber->asFloat());
        $this->assertSame(10, $newNumber->asInteger());
        $this->assertSame(10, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('10.99', $number->asString());
        $this->assertSame(10.99, $number->asFloat());
        $this->assertSame(11, $number->asInteger());
        $this->assertSame(11, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByNegativePercentageWhenNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(100);
        $newNumber = $number->decreaseByPercentage(-10);

        $this->assertSame('110', $newNumber->asString());
        $this->assertSame(110.0, $newNumber->asFloat());
        $this->assertSame(110, $newNumber->asInteger());
        $this->assertSame(110, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('100', $number->asString());
        $this->assertSame(100.0, $number->asFloat());
        $this->assertSame(100, $number->asInteger());
        $this->assertSame(100, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByNegativePercentageWhenNumberIsAFloat(): void
    {
        $number = new NumberImmutable(25.5);
        $newNumber = $number->decreaseByPercentage(15.25);

        $this->assertSame('21.61125', $newNumber->asString());
        $this->assertSame(21.61125, $newNumber->asFloat());
        $this->assertSame(22, $newNumber->asInteger());
        $this->assertSame(22, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('25.5', $number->asString());
        $this->assertSame(25.5, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(25, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByNegativePercentageWhenNumberIsAString(): void
    {
        $number = new NumberImmutable('25.5');
        $newNumber = $number->decreaseByPercentage('15.25');

        $this->assertSame('21.61125', $newNumber->asString());
        $this->assertSame(21.61125, $newNumber->asFloat());
        $this->assertSame(22, $newNumber->asInteger());
        $this->assertSame(22, $newNumber->asInteger(\PHP_ROUND_HALF_DOWN));

        $this->assertSame('25.5', $number->asString());
        $this->assertSame(25.5, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(25, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }
}
