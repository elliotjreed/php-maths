<?php

declare(strict_types=1);

namespace Tests\ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\DivisionByZero;
use ElliotJReed\Maths\Exception\InvalidDecimalPlaces;
use ElliotJReed\Maths\Exception\InvalidExponent;
use ElliotJReed\Maths\Exception\InvalidPowerModulusDivisor;
use ElliotJReed\Maths\Exception\NonNumericValue;
use ElliotJReed\Maths\Number;
use PHPUnit\Framework\TestCase;

final class NumberTest extends TestCase
{
    public function testItThrowsExceptionWhenNonNumericValueProvided(): void
    {
        $this->expectException(NonNumericValue::class);
        $this->expectExceptionMessage('Non-numeric string provided. Value provided: DEFINITELY NOT NUMERIC');

        new Number('DEFINITELY NOT NUMERIC');
    }

    public function testItReturnsNumberWhenBaseNumberIsInScientificNotation(): void
    {
        $number = new Number('8.431e-05');

        $this->assertSame('0.00008431', $number->asString());
        $this->assertSame(0.00008431, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberWhenNegativeBaseNumberIsInScientificNotation(): void
    {
        $number = new Number('-8.431e-05');

        $this->assertSame('-0.00008431', $number->asString());
        $this->assertSame(-0.00008431, $number->asFloat());
        $this->assertSame(0, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberAsStringToDefinedDecimalPlaces(): void
    {
        $number = new Number(10000.29533);

        $this->assertSame('10000.30', $number->asString(2));
        $this->assertSame('10000.29533', $number->asString());
        $this->assertSame(10000.29533, $number->asFloat());
    }

    public function testItReturnsNumberAsWholeNumberStringToZeroDefinedDecimalPlaces(): void
    {
        $number = new Number(10000.29533);

        $this->assertSame('10000', $number->asString(0));
        $this->assertSame('10000.29533', $number->asString());
        $this->assertSame(10000.29533, $number->asFloat());
    }

    public function testItReturnsNumberAsStringToDefinedThousandsSeparator(): void
    {
        $number = new Number(10000.29533);

        $this->assertSame('10,000.30', $number->asString(2, ','));
        $this->assertSame('10000.29533', $number->asString());
        $this->assertSame(10000.29533, $number->asFloat());
    }

    public function testItThrowsExceptionWhenDecimalPlacesArgumentIsLessThanZeroWhenReturningNumberAsAString(): void
    {
        $number = new Number('1.005');

        $this->expectException(InvalidDecimalPlaces::class);
        $this->expectExceptionMessage(
            'Decimal places must be a whole number greater than or equal to 0. Invalid decimal places number: -2'
        );

        $number->asString(-2);
    }

    public function testItMultipliesFloatingPointNumberAsFloat(): void
    {
        $number = new Number(0.295);
        $number->multiply(100);

        $this->assertSame('29.5', $number->asString());
        $this->assertSame(29.5, $number->asFloat());
        $this->assertSame(30, $number->asInteger());
        $this->assertSame(29, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesFloatingPointNumberAsString(): void
    {
        $number = new Number('0.295');
        $number->multiply('100');

        $this->assertSame('29.5', $number->asString());
        $this->assertSame(29.5, $number->asFloat());
        $this->assertSame(30, $number->asInteger());
        $this->assertSame(29, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesFloatingPointNumberAsNumberObject(): void
    {
        $number = new Number('0.295');
        $number->multiply(new Number(100));

        $this->assertSame('29.5', $number->asString());
        $this->assertSame(29.5, $number->asFloat());
        $this->assertSame(30, $number->asInteger());
        $this->assertSame(29, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new Number(0.333);
        $number->multiply(1.861, 102.5);

        $this->assertSame('63.5205825', $number->asString());
        $this->assertSame(63.5205825, $number->asFloat());
        $this->assertSame(64, $number->asInteger());
        $this->assertSame(64, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new Number(0.333);
        $number->multiply(1.861)->multiply(102.5);

        $this->assertSame('63.5205825', $number->asString());
        $this->assertSame(63.5205825, $number->asFloat());
        $this->assertSame(64, $number->asInteger());
        $this->assertSame(64, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new Number(22);
        $number->multiply(2.5, 1.1);

        $this->assertSame('60.5', $number->asString());
        $this->assertSame(60.5, $number->asFloat());
        $this->assertSame(61, $number->asInteger());
        $this->assertSame(60, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new Number(22);
        $number->multiply(2.5)->multiply(1.1);

        $this->assertSame('60.5', $number->asString());
        $this->assertSame(60.5, $number->asFloat());
        $this->assertSame(61, $number->asInteger());
        $this->assertSame(60, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsString(): void
    {
        $number = new Number('0.333');
        $number->multiply('1.861', '102.5');

        $this->assertSame('63.5205825', $number->asString());
        $this->assertSame(63.5205825, $number->asFloat());
        $this->assertSame(64, $number->asInteger());
        $this->assertSame(64, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new Number('0.333');
        $number->multiply('1.861')->multiply('102.5');

        $this->assertSame('63.5205825', $number->asString());
        $this->assertSame(63.5205825, $number->asFloat());
        $this->assertSame(64, $number->asInteger());
        $this->assertSame(64, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new Number('22');
        $number->multiply('2.5', '1.1');

        $this->assertSame('60.5', $number->asString());
        $this->assertSame(60.5, $number->asFloat());
        $this->assertSame(61, $number->asInteger());
        $this->assertSame(60, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new Number('22');
        $number->multiply('2.5')->multiply('1.1');

        $this->assertSame('60.5', $number->asString());
        $this->assertSame(60.5, $number->asFloat());
        $this->assertSame(61, $number->asInteger());
        $this->assertSame(60, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsFloatingPointNumberAsFloat(): void
    {
        $number = new Number(1.295);
        $number->add(1.333);

        $this->assertSame('2.628', $number->asString());
        $this->assertSame(2.628, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsFloatingPointNumberAsString(): void
    {
        $number = new Number('1.295');
        $number->add('1.333');

        $this->assertSame('2.628', $number->asString());
        $this->assertSame(2.628, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsFloatingPointNumberAsNumberObject(): void
    {
        $number = new Number('1.295');
        $number->add(new Number(1.333));

        $this->assertSame('2.628', $number->asString());
        $this->assertSame(2.628, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new Number(0.333);
        $number->add(1.861, 102.5);

        $this->assertSame('104.694', $number->asString());
        $this->assertSame(104.694, $number->asFloat());
        $this->assertSame(105, $number->asInteger());
        $this->assertSame(105, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new Number(0.333);
        $number->add(1.861)->add(102.5);

        $this->assertSame('104.694', $number->asString());
        $this->assertSame(104.694, $number->asFloat());
        $this->assertSame(105, $number->asInteger());
        $this->assertSame(105, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new Number(22.2);
        $number->add(2.13, 1.17);

        $this->assertSame('25.5', $number->asString());
        $this->assertSame(25.5, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(25, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new Number(22.2);
        $number->add(2.13)->add(1.17);

        $this->assertSame('25.5', $number->asString());
        $this->assertSame(25.5, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(25, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumber(): void
    {
        $number = new Number(22.2);
        $number->add(2.63, 1.17);

        $this->assertSame('26', $number->asString());
        $this->assertSame(26.0, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(26, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumberWhenChained(): void
    {
        $number = new Number(22.2);
        $number->add(2.63)->add(1.17);

        $this->assertSame('26', $number->asString());
        $this->assertSame(26.0, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(26, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsString(): void
    {
        $number = new Number('0.333');
        $number->add('1.861', '102.5');

        $this->assertSame('104.694', $number->asString());
        $this->assertSame(104.694, $number->asFloat());
        $this->assertSame(105, $number->asInteger());
        $this->assertSame(105, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new Number('0.333');
        $number->add('1.861')->add('102.5');

        $this->assertSame('104.694', $number->asString());
        $this->assertSame(104.694, $number->asFloat());
        $this->assertSame(105, $number->asInteger());
        $this->assertSame(105, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new Number('22.2');
        $number->add('2.13', '1.17');

        $this->assertSame('25.5', $number->asString());
        $this->assertSame(25.5, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(25, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new Number('22.2');
        $number->add('2.13')->add('1.17');

        $this->assertSame('25.5', $number->asString());
        $this->assertSame(25.5, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(25, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsFloatingPointNumberAsFloat(): void
    {
        $number = new Number(1.295);
        $number->subtract(2.333);

        $this->assertSame('-1.038', $number->asString());
        $this->assertSame(-1.038, $number->asFloat());
        $this->assertSame(-1, $number->asInteger());
        $this->assertSame(-1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsFloatingPointNumberAsString(): void
    {
        $number = new Number('1.295');
        $number->subtract('2.333');

        $this->assertSame('-1.038', $number->asString());
        $this->assertSame(-1.038, $number->asFloat());
        $this->assertSame(-1, $number->asInteger());
        $this->assertSame(-1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsFloatingPointNumberAsNumberObject(): void
    {
        $number = new Number('1.295');
        $number->subtract(new Number(2.333));

        $this->assertSame('-1.038', $number->asString());
        $this->assertSame(-1.038, $number->asFloat());
        $this->assertSame(-1, $number->asInteger());
        $this->assertSame(-1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new Number(3.333);
        $number->subtract(1.861, 0.5);

        $this->assertSame('0.972', $number->asString());
        $this->assertSame(0.972, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new Number(3.333);
        $number->subtract(1.861)->subtract(0.5);

        $this->assertSame('0.972', $number->asString());
        $this->assertSame(0.972, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new Number(3.5);
        $number->subtract(1.5, 0.5);

        $this->assertSame('1.5', $number->asString());
        $this->assertSame(1.5, $number->asFloat());
        $this->assertSame(2, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new Number(3.5);
        $number->subtract(1.5)->subtract(0.5);

        $this->assertSame('1.5', $number->asString());
        $this->assertSame(1.5, $number->asFloat());
        $this->assertSame(2, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumber(): void
    {
        $number = new Number(22.63);
        $number->subtract(2.12, 1.51);

        $this->assertSame('19', $number->asString());
        $this->assertSame(19.0, $number->asFloat());
        $this->assertSame(19, $number->asInteger());
        $this->assertSame(19, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumberWhenChained(): void
    {
        $number = new Number(22.63);
        $number->subtract(2.12)->subtract(1.51);

        $this->assertSame('19', $number->asString());
        $this->assertSame(19.0, $number->asFloat());
        $this->assertSame(19, $number->asInteger());
        $this->assertSame(19, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsString(): void
    {
        $number = new Number('3.333');
        $number->subtract('1.861', '0.5');

        $this->assertSame('0.972', $number->asString());
        $this->assertSame(0.972, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new Number('3.333');
        $number->subtract('1.861')->subtract('0.5');

        $this->assertSame('0.972', $number->asString());
        $this->assertSame(0.972, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new Number('3.5');
        $number->subtract('1.5', '0.5');

        $this->assertSame('1.5', $number->asString());
        $this->assertSame(1.5, $number->asFloat());
        $this->assertSame(2, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new Number('3.5');
        $number->subtract('1.5')->subtract('0.5');

        $this->assertSame('1.5', $number->asString());
        $this->assertSame(1.5, $number->asFloat());
        $this->assertSame(2, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesFloatingPointNumberAsFloat(): void
    {
        $number = new Number(10.295);
        $number->divide(10);

        $this->assertSame('1.0295', $number->asString());
        $this->assertSame(1.0295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesFloatingPointNumberAsString(): void
    {
        $number = new Number('10.295');
        $number->divide('10');

        $this->assertSame('1.0295', $number->asString());
        $this->assertSame(1.0295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesFloatingPointNumberAsNumberObject(): void
    {
        $number = new Number('10.295');
        $number->divide(new Number(10));

        $this->assertSame('1.0295', $number->asString());
        $this->assertSame(1.0295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new Number(70.5);
        $number->divide(1.25, 4.8);

        $this->assertSame('11.75', $number->asString());
        $this->assertSame(11.75, $number->asFloat());
        $this->assertSame(12, $number->asInteger());
        $this->assertSame(12, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new Number(70.5);
        $number->divide(1.25)->divide(4.8);

        $this->assertSame('11.75', $number->asString());
        $this->assertSame(11.75, $number->asFloat());
        $this->assertSame(12, $number->asInteger());
        $this->assertSame(12, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new Number(148.8375);
        $number->divide(3.5, 12.15);

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new Number(148.8375);
        $number->divide(3.5)->divide(12.15);

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsString(): void
    {
        $number = new Number('70.5');
        $number->divide('1.25', '4.8');

        $this->assertSame('11.75', $number->asString());
        $this->assertSame(11.75, $number->asFloat());
        $this->assertSame(12, $number->asInteger());
        $this->assertSame(12, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new Number('70.5');
        $number->divide('1.25')->divide('4.8');

        $this->assertSame('11.75', $number->asString());
        $this->assertSame(11.75, $number->asFloat());
        $this->assertSame(12, $number->asInteger());
        $this->assertSame(12, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new Number('148.8375');
        $number->divide('3.5', '12.15');

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new Number('148.8375');
        $number->divide('3.5')->divide('12.15');

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItThrowsExceptionWhenDividingBaseNumberByZeroWhenNumberIsAnInteger(): void
    {
        $number = new Number(100);

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide(0);
    }

    public function testItThrowsExceptionWhenDividingBaseNumberByZeroWhenNumberIsAFloat(): void
    {
        $number = new Number(100.123);

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide(0.0);
    }

    public function testItThrowsExceptionWhenDividingBaseNumberByZeroWhenNumberIsAString(): void
    {
        $number = new Number('100.123');

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide('0.0');
    }

    public function testItThrowsExceptionWhenDividingBaseNumberWhenBaseNumberIsAnIntegerAndIsZero(): void
    {
        $number = new Number(0);

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide(100);
    }

    public function testItThrowsExceptionWhenDividingBaseNumberWhenNumberIsAFloatAndIsZero(): void
    {
        $number = new Number(0);

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide(123.456);
    }

    public function testItThrowsExceptionWhenDividingBaseNumberWhenNumberIsAStringAndIsZero(): void
    {
        $number = new Number('0');

        $this->expectException(DivisionByZero::class);
        $this->expectExceptionMessage('Division by zero.');

        $number->divide('33.99');
    }

    public function testItReturnsTheSquareRootWhenBaseNumberIsAFloat(): void
    {
        $number = new Number(30.25);
        $number->squareRoot();

        $this->assertSame('5.5', $number->asString());
        $this->assertSame(5.5, $number->asFloat());
        $this->assertSame(6, $number->asInteger());
        $this->assertSame(5, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsTheSquareRootWhenBaseNumberIsAString(): void
    {
        $number = new Number('30.25');
        $number->squareRoot();

        $this->assertSame('5.5', $number->asString());
        $this->assertSame(5.5, $number->asFloat());
        $this->assertSame(6, $number->asInteger());
        $this->assertSame(5, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsTheSquareRootWhenBaseNumberIsAnInteger(): void
    {
        $number = new Number(25);
        $number->squareRoot();

        $this->assertSame('5', $number->asString());
        $this->assertSame(5.0, $number->asFloat());
        $this->assertSame(5, $number->asInteger());
        $this->assertSame(5, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenBaseNumberIsAnInteger(): void
    {
        $number = new Number(5);
        $number->modulus(3);

        $this->assertSame('2', $number->asString());
        $this->assertSame(2.0, $number->asFloat());
        $this->assertSame(2, $number->asInteger());
        $this->assertSame(2, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenBaseNumberIsAFloat(): void
    {
        $number = new Number(5.5);
        $number->modulus(2.5);

        $this->assertSame('0.5', $number->asString());
        $this->assertSame(0.5, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenBaseNumberIsAString(): void
    {
        $number = new Number('5.5');
        $number->modulus('2.5');

        $this->assertSame('0.5', $number->asString());
        $this->assertSame(0.5, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenModulusIsANumberObject(): void
    {
        $number = new Number('5.5');
        $number->modulus(new Number(2.5));

        $this->assertSame('0.5', $number->asString());
        $this->assertSame(0.5, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberRaisedToPowerExponentWhenBaseNumberIsAnInteger(): void
    {
        $number = new Number(5);
        $number->raiseToPower(3);

        $this->assertSame('125', $number->asString());
        $this->assertSame(125.0, $number->asFloat());
        $this->assertSame(125, $number->asInteger());
        $this->assertSame(125, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberRaisedToPowerExponentWhenBaseNumberIsAFloat(): void
    {
        $number = new Number(2.75);
        $number->raiseToPower(2);

        $this->assertSame('7.5625', $number->asString());
        $this->assertSame(7.5625, $number->asFloat());
        $this->assertSame(8, $number->asInteger());
        $this->assertSame(8, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberRaisedToPowerExponentWhenExponentIsANumberObject(): void
    {
        $number = new Number(2.75);
        $number->raiseToPower(new Number(2));

        $this->assertSame('7.5625', $number->asString());
        $this->assertSame(7.5625, $number->asFloat());
        $this->assertSame(8, $number->asInteger());
        $this->assertSame(8, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItThrowsExceptionWhenExponentIsNotAWholeNumberWhenRaisingToPower(): void
    {
        $number = new Number(25);

        $this->expectException(InvalidExponent::class);
        $this->expectExceptionMessage('Exponent must be a whole number. Invalid exponent: 1.5');

        $number->raiseToPower(1.5);
    }

    public function testItThrowsExceptionWhenExponentIsNotAWholeNumberWhenRaisingToPowerAndExponentIsANumberObject(): void
    {
        $number = new Number(25);

        $this->expectException(InvalidExponent::class);
        $this->expectExceptionMessage('Exponent must be a whole number. Invalid exponent: 1.5');

        $number->raiseToPower(new Number(1.5));
    }

    public function testItReturnsNumberRaisedToPowerExponentAndReducedByModulusWhenBaseNumberIsAnInteger(): void
    {
        $number = new Number(5371);
        $number->raiseToPowerReduceByModulus(2, 7);

        $this->assertSame('4', $number->asString());
        $this->assertSame(4.0, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(4, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberRaisedToPowerExponentAndReducedByModulusWhenBaseNumberIsAString(): void
    {
        $number = new Number('5371');
        $number->raiseToPowerReduceByModulus(2, 7);

        $this->assertSame('4', $number->asString());
        $this->assertSame(4.0, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(4, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberRaisedToPowerExponentAndReducedByModulusWhenExponentAndDivisorsAreNumberObjects(): void
    {
        $number = new Number('5371');
        $number->raiseToPowerReduceByModulus(new Number(2), new Number(7));

        $this->assertSame('4', $number->asString());
        $this->assertSame(4.0, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(4, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItThrowsExceptionWhenExponentIsNotAWholeNumberWhenRaisingToPowerAndReducingByModulus(): void
    {
        $number = new Number('5371');

        $this->expectException(InvalidExponent::class);
        $this->expectExceptionMessage('Exponent must be a whole number. Invalid exponent: 2.2');

        $number->raiseToPowerReduceByModulus(2.2, 7);
    }

    public function testItThrowsExceptionWhenDivisorIsNotAWholeNumberWhenRaisingToPowerAndReducingByModulus(): void
    {
        $number = new Number('5371');

        $this->expectException(InvalidPowerModulusDivisor::class);
        $this->expectExceptionMessage('Divisor must be a whole number. Invalid divisor: 7.5');

        $number->raiseToPowerReduceByModulus(2, 7.5);
    }

    public function testItReturnsTrueWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsLessThanBaseNumber(): void
    {
        $number = new Number('100.01');

        $this->assertTrue($number->isLessThan('100.02'));
    }

    public function testItThrowsExceptionWhenExponentIsNotAWholeNumberWhenRaisingToPowerAndReducingByModulusAndExponentAndDivisorsAreNumberObjects(): void
    {
        $number = new Number('5371');

        $this->expectException(InvalidExponent::class);
        $this->expectExceptionMessage('Exponent must be a whole number. Invalid exponent: 2.2');

        $number->raiseToPowerReduceByModulus(new Number(2.2), new Number(7));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsLessThanBaseNumber(): void
    {
        $number = new Number('1.003');

        $this->assertFalse($number->isLessThan('1.002'));
    }

    public function testItReturnsFalseWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsLessThanBaseNumber(): void
    {
        $number = new Number('1.003');

        $this->assertFalse($number->isLessThan('1.003'));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsLessThanBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('1.003');

        $this->assertFalse($number->isLessThan(new Number(1.002)));
    }

    public function testItReturnsFalseWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsLessThanBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('1.003');

        $this->assertFalse($number->isLessThan(new Number(1.003)));
    }

    public function testItReturnsTrueWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanBaseNumber(): void
    {
        $number = new Number('100.02');

        $this->assertTrue($number->isGreaterThan('100.01'));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanBaseNumber(): void
    {
        $number = new Number('1.002');

        $this->assertFalse($number->isGreaterThan('1.003'));
    }

    public function testItReturnsFalseWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsGreaterThanBaseNumber(): void
    {
        $number = new Number('1.002');

        $this->assertFalse($number->isGreaterThan('1.002'));
    }

    public function testItReturnsTrueWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('100.02');

        $this->assertTrue($number->isGreaterThan(new Number('100.01')));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('1.002');

        $this->assertFalse($number->isGreaterThan(new Number('1.003')));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsEqualToTheBaseNumber(): void
    {
        $number = new Number('100.02');

        $this->assertFalse($number->isEqualTo('100.01'));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsEqualToTheBaseNumber(): void
    {
        $number = new Number('1.002');

        $this->assertFalse($number->isEqualTo('1.003'));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberEqualToTheThanBaseNumber(): void
    {
        $number = new Number('1.002');

        $this->assertTrue($number->isEqualTo('1.002'));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('100.02');

        $this->assertFalse($number->isEqualTo(new Number('100.01')));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('1.002');

        $this->assertFalse($number->isEqualTo(new Number('1.003')));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberEqualToTheThanBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('1.002');

        $this->assertTrue($number->isEqualTo(new Number('1.002')));
    }

    public function testItReturnsTrueWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseNumber(): void
    {
        $number = new Number('100.01');

        $this->assertTrue($number->isLessThanOrEqualTo('100.02'));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseBaseNumber(): void
    {
        $number = new Number('1.003');

        $this->assertFalse($number->isLessThanOrEqualTo('1.002'));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseNumber(): void
    {
        $number = new Number('1.003');

        $this->assertTrue($number->isLessThanOrEqualTo('1.003'));
    }

    public function testItReturnsTrueWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('100.01');

        $this->assertTrue($number->isLessThanOrEqualTo(new Number('100.02')));
    }

    public function testItReturnsFalseWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('1.003');

        $this->assertFalse($number->isLessThanOrEqualTo(new Number('1.002')));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsLessThanOrEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('1.003');

        $this->assertTrue($number->isLessThanOrEqualTo(new Number('1.003')));
    }

    public function testItReturnsTrueWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumber(): void
    {
        $number = new Number('100.02');

        $this->assertTrue($number->isGreaterThanOrEqualTo('100.01'));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumber(): void
    {
        $number = new Number('1.002');

        $this->assertFalse($number->isGreaterThanOrEqualTo('1.003'));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumber(): void
    {
        $number = new Number('1.002');

        $this->assertTrue($number->isGreaterThanOrEqualTo('1.002'));
    }

    public function testItReturnsTrueWhenNumberIsGreaterThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('100.02');

        $this->assertTrue($number->isGreaterThanOrEqualTo(new Number('100.01')));
    }

    public function testItReturnsFalseWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('1.002');

        $this->assertFalse($number->isGreaterThanOrEqualTo(new Number('1.003')));
    }

    public function testItReturnsTrueWhenNumberIsEqualToTheBaseNumberWhenCheckingIfNumberIsGreaterThanOrEqualToTheBaseNumberAndComparatorNumberIsANumberObject(): void
    {
        $number = new Number('1.002');

        $this->assertTrue($number->isGreaterThanOrEqualTo(new Number('1.002')));
    }

    public function testItReturnsTrueWhenTheBaseNumberIsZeroWhenTheBaseNumberIsAnInteger(): void
    {
        $number = new Number(0);

        $this->assertTrue($number->isZero());
    }

    public function testItReturnsTrueWhenTheBaseNumberIsZeroWhenTheBaseNumberIsAFloat(): void
    {
        $number = new Number(0.0);

        $this->assertTrue($number->isZero());
    }

    public function testItReturnsTrueWhenTheBaseNumberIsZeroWhenTheBaseNumberIsAString(): void
    {
        $number = new Number('0.0');

        $this->assertTrue($number->isZero());
    }

    public function testItReturnsItFalseWhenTheBaseNumberIsNotZeroWhenTheBaseNumberIsAnInteger(): void
    {
        $number = new Number(-123);

        $this->assertFalse($number->isZero());
    }

    public function testItReturnsItFalseWhenTheBaseNumberIsNotZeroWhenTheBaseNumberIsAFloat(): void
    {
        $number = new Number(0.00000000000001);

        $this->assertFalse($number->isZero());
    }

    public function testItReturnsItFalseWhenTheBaseNumberIsNotZeroWhenTheBaseNumberIsANegativeFloat(): void
    {
        $number = new Number(-0.000000000000001);

        $this->assertFalse($number->isZero());
    }

    public function testItReturnsItFalseWhenTheBaseNumberIsNotZeroWhenTheBaseNumberIsAString(): void
    {
        $number = new Number('-0.01');

        $this->assertFalse($number->isZero());
    }

    public function testItReturnsNumberSetToDefinedDecimalPlacesWhenRoundingUpByDefault(): void
    {
        $number = new Number('1.005');
        $number->roundToDecimalPlaces(2);

        $this->assertSame('1.01', $number->asString());
        $this->assertSame(1.01, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberSetToDefinedDecimalPlacesWhenRoundingDown(): void
    {
        $number = new Number('1.005');
        $number->roundToDecimalPlaces(2, \PHP_ROUND_HALF_DOWN);

        $this->assertSame('1', $number->asString());
        $this->assertSame(1.0, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItThrowsExceptionWhenDecimalPlacesArgumentIsLessThanZeroWhenRoundingNumber(): void
    {
        $number = new Number('1.005');

        $this->expectException(InvalidDecimalPlaces::class);
        $this->expectExceptionMessage(
            'Decimal places must be a whole number greater than or equal to 0. Invalid decimal places number: -2'
        );

        $number->roundToDecimalPlaces(-2);
    }

    public function testItIncreasesBaseNumberByPercentageWhenNumberIsAnInteger(): void
    {
        $number = new Number(100);
        $number->increaseByPercentage(10);

        $this->assertSame('110', $number->asString());
        $this->assertSame(110.0, $number->asFloat());
        $this->assertSame(110, $number->asInteger());
        $this->assertSame(110, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItIncreasesBaseNumberByPercentageWhenNumberIsAFloat(): void
    {
        $number = new Number(100.50);
        $number->increaseByPercentage(10.125);

        $this->assertSame('110.675625', $number->asString());
        $this->assertSame(110.675625, $number->asFloat());
        $this->assertSame(111, $number->asInteger());
        $this->assertSame(111, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItIncreasesBaseNumberByPercentageWhenNumberIsAString(): void
    {
        $number = new Number('100.50');
        $number->increaseByPercentage('10.125');

        $this->assertSame('110.675625', $number->asString());
        $this->assertSame(110.675625, $number->asFloat());
        $this->assertSame(111, $number->asInteger());
        $this->assertSame(111, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItIncreasesBaseNumberByNegativePercentageWhenNumberIsAnInteger(): void
    {
        $number = new Number(100);
        $number->increaseByPercentage(-10);

        $this->assertSame('90', $number->asString());
        $this->assertSame(90.0, $number->asFloat());
        $this->assertSame(90, $number->asInteger());
        $this->assertSame(90, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItIncreasesBaseNumberByNegativePercentageWhenNumberIsAFloat(): void
    {
        $number = new Number(-100.25);
        $number->increaseByPercentage(-10.55);

        $this->assertSame('-89.673625', $number->asString());
        $this->assertSame(-89.673625, $number->asFloat());
        $this->assertSame(-90, $number->asInteger());
        $this->assertSame(-90, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItIncreasesBaseNumberByNegativePercentageWhenNumberIsAString(): void
    {
        $number = new Number('100.33');
        $number->increaseByPercentage('-10.66');

        $this->assertSame('89.634822', $number->asString());
        $this->assertSame(89.634822, $number->asFloat());
        $this->assertSame(90, $number->asInteger());
        $this->assertSame(90, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByPercentageWhenNumberIsAnInteger(): void
    {
        $number = new Number(100);
        $number->decreaseByPercentage(10);

        $this->assertSame('90', $number->asString());
        $this->assertSame(90.0, $number->asFloat());
        $this->assertSame(90, $number->asInteger());
        $this->assertSame(90, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByPercentageWhenNumberIsAFloat(): void
    {
        $number = new Number(10.99);
        $number->decreaseByPercentage(5.123);

        $this->assertSame('10.4269823', $number->asString());
        $this->assertSame(10.4269823, $number->asFloat());
        $this->assertSame(10, $number->asInteger());
        $this->assertSame(10, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByPercentageWhenNumberIsAString(): void
    {
        $number = new Number('10.99');
        $number->decreaseByPercentage('5.123');

        $this->assertSame('10.4269823', $number->asString());
        $this->assertSame(10.4269823, $number->asFloat());
        $this->assertSame(10, $number->asInteger());
        $this->assertSame(10, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByNegativePercentageWhenNumberIsAnInteger(): void
    {
        $number = new Number(100);
        $number->decreaseByPercentage(-10);

        $this->assertSame('110', $number->asString());
        $this->assertSame(110.0, $number->asFloat());
        $this->assertSame(110, $number->asInteger());
        $this->assertSame(110, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByNegativePercentageWhenNumberIsAFloat(): void
    {
        $number = new Number(25.5);
        $number->decreaseByPercentage(15.25);

        $this->assertSame('21.61125', $number->asString());
        $this->assertSame(21.61125, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDecreasesBaseNumberByNegativePercentageWhenNumberIsAString(): void
    {
        $number = new Number('25.5');
        $number->decreaseByPercentage('15.25');

        $this->assertSame('21.61125', $number->asString());
        $this->assertSame(21.61125, $number->asFloat());
        $this->assertSame(22, $number->asInteger());
        $this->assertSame(22, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }
}
