<?php

declare(strict_types=1);

namespace Tests\ElliotJReed\Maths;

use ElliotJReed\Maths\Exception\InvalidDecimalPlaces;
use ElliotJReed\Maths\Exception\InvalidExponent;
use ElliotJReed\Maths\Exception\InvalidPowerModulusDivisor;
use ElliotJReed\Maths\NumberImmutable;
use PHPUnit\Framework\TestCase;

final class NumberImmutableTest extends TestCase
{
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
        $this->expectExceptionMessage('Decimal places must be a whole number greater than or equal to 0. Invalid decimal places number: -2');

        $number->asString(-2);
    }

    public function testItMultipliesFloatingPointNumberAsFloat(): void
    {
        $number = new NumberImmutable(0.295);
        $number = $number->multiply(100);

        $this->assertSame('29.5', $number->asString());
        $this->assertSame(29.5, $number->asFloat());
        $this->assertSame(30, $number->asInteger());
        $this->assertSame(29, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesFloatingPointNumberAsString(): void
    {
        $number = new NumberImmutable('0.295');
        $number = $number->multiply('100');

        $this->assertSame('29.5', $number->asString());
        $this->assertSame(29.5, $number->asFloat());
        $this->assertSame(30, $number->asInteger());
        $this->assertSame(29, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesFloatingPointNumberAsNumberObject(): void
    {
        $number = new NumberImmutable('0.295');
        $number = $number->multiply(new NumberImmutable(100));

        $this->assertSame('29.5', $number->asString());
        $this->assertSame(29.5, $number->asFloat());
        $this->assertSame(30, $number->asInteger());
        $this->assertSame(29, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new NumberImmutable(0.333);
        $number = $number->multiply(1.861, 102.5);

        $this->assertSame('63.5205825', $number->asString());
        $this->assertSame(63.5205825, $number->asFloat());
        $this->assertSame(64, $number->asInteger());
        $this->assertSame(64, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new NumberImmutable(0.333);
        $number = $number->multiply(1.861)->multiply(102.5);

        $this->assertSame('63.5205825', $number->asString());
        $this->assertSame(63.5205825, $number->asFloat());
        $this->assertSame(64, $number->asInteger());
        $this->assertSame(64, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable(22);
        $number = $number->multiply(2.5, 1.1);

        $this->assertSame('60.5', $number->asString());
        $this->assertSame(60.5, $number->asFloat());
        $this->assertSame(61, $number->asInteger());
        $this->assertSame(60, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable(22);
        $number = $number->multiply(2.5)->multiply(1.1);

        $this->assertSame('60.5', $number->asString());
        $this->assertSame(60.5, $number->asFloat());
        $this->assertSame(61, $number->asInteger());
        $this->assertSame(60, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsString(): void
    {
        $number = new NumberImmutable('0.333');
        $number = $number->multiply('1.861', '102.5');

        $this->assertSame('63.5205825', $number->asString());
        $this->assertSame(63.5205825, $number->asFloat());
        $this->assertSame(64, $number->asInteger());
        $this->assertSame(64, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new NumberImmutable('0.333');
        $number = $number->multiply('1.861')->multiply('102.5');

        $this->assertSame('63.5205825', $number->asString());
        $this->assertSame(63.5205825, $number->asFloat());
        $this->assertSame(64, $number->asInteger());
        $this->assertSame(64, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable('22');
        $number = $number->multiply('2.5', '1.1');

        $this->assertSame('60.5', $number->asString());
        $this->assertSame(60.5, $number->asFloat());
        $this->assertSame(61, $number->asInteger());
        $this->assertSame(60, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItMultipliesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable('22');
        $number = $number->multiply('2.5')->multiply('1.1');

        $this->assertSame('60.5', $number->asString());
        $this->assertSame(60.5, $number->asFloat());
        $this->assertSame(61, $number->asInteger());
        $this->assertSame(60, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsFloatingPointNumberAsFloat(): void
    {
        $number = new NumberImmutable(1.295);
        $number = $number->add(1.333);

        $this->assertSame('2.628', $number->asString());
        $this->assertSame(2.628, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsFloatingPointNumberAsString(): void
    {
        $number = new NumberImmutable('1.295');
        $number = $number->add('1.333');

        $this->assertSame('2.628', $number->asString());
        $this->assertSame(2.628, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsFloatingPointNumberAsNumberObject(): void
    {
        $number = new NumberImmutable('1.295');
        $number = $number->add(new NumberImmutable(1.333));

        $this->assertSame('2.628', $number->asString());
        $this->assertSame(2.628, $number->asFloat());
        $this->assertSame(3, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new NumberImmutable(0.333);
        $number = $number->add(1.861, 102.5);

        $this->assertSame('104.694', $number->asString());
        $this->assertSame(104.694, $number->asFloat());
        $this->assertSame(105, $number->asInteger());
        $this->assertSame(105, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new NumberImmutable(0.333);
        $number = $number->add(1.861)->add(102.5);

        $this->assertSame('104.694', $number->asString());
        $this->assertSame(104.694, $number->asFloat());
        $this->assertSame(105, $number->asInteger());
        $this->assertSame(105, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable(22.2);
        $number = $number->add(2.13, 1.17);

        $this->assertSame('25.5', $number->asString());
        $this->assertSame(25.5, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(25, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable(22.2);
        $number = $number->add(2.13)->add(1.17);

        $this->assertSame('25.5', $number->asString());
        $this->assertSame(25.5, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(25, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumberImmutable(): void
    {
        $number = new NumberImmutable(22.2);
        $number = $number->add(2.63, 1.17);

        $this->assertSame('26', $number->asString());
        $this->assertSame(26.0, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(26, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumberWhenChained(): void
    {
        $number = new NumberImmutable(22.2);
        $number = $number->add(2.63)->add(1.17);

        $this->assertSame('26', $number->asString());
        $this->assertSame(26.0, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(26, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsString(): void
    {
        $number = new NumberImmutable('0.333');
        $number = $number->add('1.861', '102.5');

        $this->assertSame('104.694', $number->asString());
        $this->assertSame(104.694, $number->asFloat());
        $this->assertSame(105, $number->asInteger());
        $this->assertSame(105, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new NumberImmutable('0.333');
        $number = $number->add('1.861')->add('102.5');

        $this->assertSame('104.694', $number->asString());
        $this->assertSame(104.694, $number->asFloat());
        $this->assertSame(105, $number->asInteger());
        $this->assertSame(105, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable('22.2');
        $number = $number->add('2.13', '1.17');

        $this->assertSame('25.5', $number->asString());
        $this->assertSame(25.5, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(25, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItAddsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable('22.2');
        $number = $number->add('2.13')->add('1.17');

        $this->assertSame('25.5', $number->asString());
        $this->assertSame(25.5, $number->asFloat());
        $this->assertSame(26, $number->asInteger());
        $this->assertSame(25, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsFloatingPointNumberAsFloat(): void
    {
        $number = new NumberImmutable(1.295);
        $number = $number->subtract(2.333);

        $this->assertSame('-1.038', $number->asString());
        $this->assertSame(-1.038, $number->asFloat());
        $this->assertSame(-1, $number->asInteger());
        $this->assertSame(-1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsFloatingPointNumberAsString(): void
    {
        $number = new NumberImmutable('1.295');
        $number = $number->subtract('2.333');

        $this->assertSame('-1.038', $number->asString());
        $this->assertSame(-1.038, $number->asFloat());
        $this->assertSame(-1, $number->asInteger());
        $this->assertSame(-1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsFloatingPointNumberAsNumberObject(): void
    {
        $number = new NumberImmutable('1.295');
        $number = $number->subtract(new NumberImmutable(2.333));

        $this->assertSame('-1.038', $number->asString());
        $this->assertSame(-1.038, $number->asFloat());
        $this->assertSame(-1, $number->asInteger());
        $this->assertSame(-1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new NumberImmutable(3.333);
        $number = $number->subtract(1.861, 0.5);

        $this->assertSame('0.972', $number->asString());
        $this->assertSame(0.972, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new NumberImmutable(3.333);
        $number = $number->subtract(1.861)->subtract(0.5);

        $this->assertSame('0.972', $number->asString());
        $this->assertSame(0.972, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable(3.5);
        $number = $number->subtract(1.5, 0.5);

        $this->assertSame('1.5', $number->asString());
        $this->assertSame(1.5, $number->asFloat());
        $this->assertSame(2, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable(3.5);
        $number = $number->subtract(1.5)->subtract(0.5);

        $this->assertSame('1.5', $number->asString());
        $this->assertSame(1.5, $number->asFloat());
        $this->assertSame(2, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumberImmutable(): void
    {
        $number = new NumberImmutable(22.63);
        $number = $number->subtract(2.12, 1.51);

        $this->assertSame('19', $number->asString());
        $this->assertSame(19.0, $number->asFloat());
        $this->assertSame(19, $number->asInteger());
        $this->assertSame(19, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsFloatWhenResultIsWholeNumberWhenChained(): void
    {
        $number = new NumberImmutable(22.63);
        $number = $number->subtract(2.12)->subtract(1.51);

        $this->assertSame('19', $number->asString());
        $this->assertSame(19.0, $number->asFloat());
        $this->assertSame(19, $number->asInteger());
        $this->assertSame(19, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsString(): void
    {
        $number = new NumberImmutable('3.333');
        $number = $number->subtract('1.861', '0.5');

        $this->assertSame('0.972', $number->asString());
        $this->assertSame(0.972, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new NumberImmutable('3.333');
        $number = $number->subtract('1.861')->subtract('0.5');

        $this->assertSame('0.972', $number->asString());
        $this->assertSame(0.972, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable('3.5');
        $number = $number->subtract('1.5', '0.5');

        $this->assertSame('1.5', $number->asString());
        $this->assertSame(1.5, $number->asFloat());
        $this->assertSame(2, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItSubtractsMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable('3.5');
        $number = $number->subtract('1.5')->subtract('0.5');

        $this->assertSame('1.5', $number->asString());
        $this->assertSame(1.5, $number->asFloat());
        $this->assertSame(2, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesFloatingPointNumberAsFloat(): void
    {
        $number = new NumberImmutable(10.295);
        $number = $number->divide(10);

        $this->assertSame('1.0295', $number->asString());
        $this->assertSame(1.0295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesFloatingPointNumberAsString(): void
    {
        $number = new NumberImmutable('10.295');
        $number = $number->divide('10');

        $this->assertSame('1.0295', $number->asString());
        $this->assertSame(1.0295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesFloatingPointNumberAsNumberObject(): void
    {
        $number = new NumberImmutable('10.295');
        $number = $number->divide(new NumberImmutable(10));

        $this->assertSame('1.0295', $number->asString());
        $this->assertSame(1.0295, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloat(): void
    {
        $number = new NumberImmutable(70.5);
        $number = $number->divide(1.25, 4.8);

        $this->assertSame('11.75', $number->asString());
        $this->assertSame(11.75, $number->asFloat());
        $this->assertSame(12, $number->asInteger());
        $this->assertSame(12, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloatWhenChained(): void
    {
        $number = new NumberImmutable(70.5);
        $number = $number->divide(1.25)->divide(4.8);

        $this->assertSame('11.75', $number->asString());
        $this->assertSame(11.75, $number->asFloat());
        $this->assertSame(12, $number->asInteger());
        $this->assertSame(12, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable(148.8375);
        $number = $number->divide(3.5, 12.15);

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsFloatAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable(148.8375);
        $number = $number->divide(3.5)->divide(12.15);

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsString(): void
    {
        $number = new NumberImmutable('70.5');
        $number = $number->divide('1.25', '4.8');

        $this->assertSame('11.75', $number->asString());
        $this->assertSame(11.75, $number->asFloat());
        $this->assertSame(12, $number->asInteger());
        $this->assertSame(12, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsStringWhenChained(): void
    {
        $number = new NumberImmutable('70.5');
        $number = $number->divide('1.25')->divide('4.8');

        $this->assertSame('11.75', $number->asString());
        $this->assertSame(11.75, $number->asFloat());
        $this->assertSame(12, $number->asInteger());
        $this->assertSame(12, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForInteger(): void
    {
        $number = new NumberImmutable('148.8375');
        $number = $number->divide('3.5', '12.15');

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItDividesMultipleFloatingPointNumbersAsStringAndReturnsRoundedValueForIntegerWhenChained(): void
    {
        $number = new NumberImmutable('148.8375');
        $number = $number->divide('3.5')->divide('12.15');

        $this->assertSame('3.5', $number->asString());
        $this->assertSame(3.5, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(3, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsTheSquareRootWhenBaseNumberIsAFloat(): void
    {
        $number = new NumberImmutable(30.25);
        $number = $number->squareRoot();

        $this->assertSame('5.5', $number->asString());
        $this->assertSame(5.5, $number->asFloat());
        $this->assertSame(6, $number->asInteger());
        $this->assertSame(5, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsTheSquareRootWhenBaseNumberIsAString(): void
    {
        $number = new NumberImmutable('30.25');
        $number = $number->squareRoot();

        $this->assertSame('5.5', $number->asString());
        $this->assertSame(5.5, $number->asFloat());
        $this->assertSame(6, $number->asInteger());
        $this->assertSame(5, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsTheSquareRootWhenBaseNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(25);
        $number = $number->squareRoot();

        $this->assertSame('5', $number->asString());
        $this->assertSame(5.0, $number->asFloat());
        $this->assertSame(5, $number->asInteger());
        $this->assertSame(5, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenBaseNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(5);
        $number = $number->modulus(3);

        $this->assertSame('2', $number->asString());
        $this->assertSame(2.0, $number->asFloat());
        $this->assertSame(2, $number->asInteger());
        $this->assertSame(2, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenBaseNumberIsAFloat(): void
    {
        $number = new NumberImmutable(5.5);
        $number = $number->modulus(2.5);

        $this->assertSame('0.5', $number->asString());
        $this->assertSame(0.5, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenBaseNumberIsAString(): void
    {
        $number = new NumberImmutable('5.5');
        $number = $number->modulus('2.5');

        $this->assertSame('0.5', $number->asString());
        $this->assertSame(0.5, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsModulusWhenModulusIsANumberObject(): void
    {
        $number = new NumberImmutable('5.5');
        $number = $number->modulus(new NumberImmutable(2.5));

        $this->assertSame('0.5', $number->asString());
        $this->assertSame(0.5, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(0, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberAisedToPowerExponentWhenBaseNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(5);
        $number = $number->raiseToPower(3);

        $this->assertSame('125', $number->asString());
        $this->assertSame(125.0, $number->asFloat());
        $this->assertSame(125, $number->asInteger());
        $this->assertSame(125, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberAisedToPowerExponentWhenBaseNumberIsAFloat(): void
    {
        $number = new NumberImmutable(2.75);
        $number = $number->raiseToPower(2);

        $this->assertSame('7.5625', $number->asString());
        $this->assertSame(7.5625, $number->asFloat());
        $this->assertSame(8, $number->asInteger());
        $this->assertSame(8, $number->asInteger(\PHP_ROUND_HALF_DOWN)); // TODO: use a rounded number
    }

    public function testItReturnsNumberAisedToPowerExponentWhenExponentIsANumberObject(): void
    {
        $number = new NumberImmutable(2.75);
        $number = $number->raiseToPower(new NumberImmutable(2));

        $this->assertSame('7.5625', $number->asString());
        $this->assertSame(7.5625, $number->asFloat());
        $this->assertSame(8, $number->asInteger());
        $this->assertSame(8, $number->asInteger(\PHP_ROUND_HALF_DOWN)); // TODO: use a rounded number
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

    public function testItReturnsNumberAisedToPowerExponentAndReducedByModulusWhenBaseNumberIsAnInteger(): void
    {
        $number = new NumberImmutable(5371);
        $number = $number->raiseToPowerReduceByModulus(2, 7);

        $this->assertSame('4', $number->asString());
        $this->assertSame(4.0, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(4, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberAisedToPowerExponentAndReducedByModulusWhenBaseNumberIsAString(): void
    {
        $number = new NumberImmutable('5371');
        $number = $number->raiseToPowerReduceByModulus(2, 7);

        $this->assertSame('4', $number->asString());
        $this->assertSame(4.0, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(4, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberAisedToPowerExponentAndReducedByModulusWhenExponentAndDivisorsAreNumberObjects(): void
    {
        $number = new NumberImmutable('5371');
        $number = $number->raiseToPowerReduceByModulus(new NumberImmutable(2), new NumberImmutable(7));

        $this->assertSame('4', $number->asString());
        $this->assertSame(4.0, $number->asFloat());
        $this->assertSame(4, $number->asInteger());
        $this->assertSame(4, $number->asInteger(\PHP_ROUND_HALF_DOWN));
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

    public function testItReturnsNumberSetToDefinedDecimalPlacesWhenRoundingUpByDefault(): void
    {
        $number = new NumberImmutable('1.005');
        $number = $number->roundToDecimalPlaces(2);

        $this->assertSame('1.01', $number->asString());
        $this->assertSame(1.01, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItReturnsNumberSetToDefinedDecimalPlacesWhenRoundingDown(): void
    {
        $number = new NumberImmutable('1.005');
        $number = $number->roundToDecimalPlaces(2, \PHP_ROUND_HALF_DOWN);

        $this->assertSame('1', $number->asString());
        $this->assertSame(1.0, $number->asFloat());
        $this->assertSame(1, $number->asInteger());
        $this->assertSame(1, $number->asInteger(\PHP_ROUND_HALF_DOWN));
    }

    public function testItThrowsExceptionWhenDecimalPlacesArgumentIsLessThanZeroWhenRoundingNumberImmutable(): void
    {
        $number = new NumberImmutable('1.005');

        $this->expectException(InvalidDecimalPlaces::class);
        $this->expectExceptionMessage('Decimal places must be a whole number greater than or equal to 0. Invalid decimal places number: -2');

        $number->roundToDecimalPlaces(-2);
    }
}
