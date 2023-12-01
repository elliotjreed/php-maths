<?php

declare(strict_types=1);

namespace ElliotJReed\Maths;

use PHPUnit\Framework\TestCase;

final class NumberTest extends TestCase
{
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

    public function testItReturnsTrueWhenNumberIsLessThanTheBaseNumberWhenCheckingIfNumberIsLessThanBaseNumber(): void
    {
        $number = new Number('100.01');

        $this->assertTrue($number->isLessThan('100.02'));
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
}
