<?php

namespace Markuskooche\Geocode\Tests;

use Markuskooche\Geocode\Exceptions\InvalidCoordinateException;
use Markuskooche\Geocode\Traits\Coordinate;
use PHPUnit\Framework\TestCase;

/**
 * @author Markus Koch
 * @license MIT
 * @package Markuskooche\Geocode
 */
class InvalidCoordinateTest extends TestCase
{
    use Coordinate;

    /** @test */
    function it_coordinate_is_valid_on_top_left() : void
    {
        try {
            $this->checkCoordinate(-180.0, -90.0);
            $this->assertTrue(true);
        } catch (InvalidCoordinateException $e) {
            $this->fail('Coordinate is valid on top left');
        }
    }

    /** @test */
    function it_coordinate_is_valid_on_top_right() : void
    {
        try {
            $this->checkCoordinate(180.0, -90.0);
            $this->assertTrue(true);
        } catch (InvalidCoordinateException $e) {
            $this->fail('Coordinate is valid on top right');
        }
    }

    /** @test */
    function it_coordinate_is_valid_on_bottom_left() : void
    {
        try {
            $this->checkCoordinate(-180.0, 90.0);
            $this->assertTrue(true);
        } catch (InvalidCoordinateException $e) {
            $this->fail('Coordinate is valid on bottom left');
        }
    }

    /** @test */
    function it_coordinate_is_valid_on_bottom_right() : void
    {
        try {
            $this->checkCoordinate(180.0, 90.0);
            $this->assertTrue(true);
        } catch (InvalidCoordinateException $e) {
            $this->fail('Coordinate is valid on bottom right');
        }
    }

    /** @test */
    function it_longitude_throws_exception_if_its_to_low() : void
    {
        $this->expectException(InvalidCoordinateException::class);
        $this->checkCoordinate(-180.0000000001, 0.0);
    }

    /** @test */
    function it_longitude_throws_exception_if_its_to_high() : void
    {
        $this->expectException(InvalidCoordinateException::class);
        $this->checkCoordinate(180.0000000001, 0.0);
    }

    /** @test */
    function it_latitude_throws_exception_if_its_to_low() : void
    {
        $this->expectException(InvalidCoordinateException::class);
        $this->checkCoordinate(0.0, -90.0000000001);
    }

    /** @test */
    function it_latitude_throws_exception_if_its_to_high() : void
    {
        $this->expectException(InvalidCoordinateException::class);
        $this->checkCoordinate(0.0, 90.0000000001);
    }

    /** @test */
    function it_invalid_coordinate_exception_returns_correct_values() : void
    {
        $exception = new InvalidCoordinateException(180.1, 90.1);
        $this->assertEquals(180.1, $exception->getLongitude());
        $this->assertEquals(90.1, $exception->getLatitude());
    }
}