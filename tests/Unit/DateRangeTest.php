<?php declare(strict_types=1);
/**
 * @author    Jacques Marneweck <jacques@siberia.co.za>
 * @copyright 2022 Jacques Marneweck.  All rights strictly reserved.
 */

namespace Jacques\TeleOpti\Tests\Unit;

use Jacques\TeleOpti\DateRange;
use PHPUnit\Framework\TestCase;

final class DateRangeTest extends TestCase
{
    /**
     * @dataProvider provideDateRangeData
     */
    public function testDateRanges($expectedResult, $date, $daterange, $timezone): void
    {
        $result = DateRange::parse($date, $daterange, $timezone);

        self::assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider provideDateRangeChangeTimeZoneData
     */
    public function testDateRangesChangeTimeZone($expectedResult, $date, $daterange, $timezone, $totimezone): void
    {
        $result = DateRange::parse($date, $daterange, $timezone, $totimezone);

        self::assertSame($expectedResult, $result);
    }


    /**
     * @return array
     */
    public function provideDateRangeData(): array
    {
        return [
            'test hours with out : and spaces' => [
                [
                    '2022-07-22 19:00:00',
                    '2022-07-23 00:00:00',
                ],
                '2022-07-22',
                '1900-0000',
                'SAST',
            ],
            'test hours without : and with spaces' => [
                [
                    '2022-07-22 19:00:00',
                    '2022-07-23 00:00:00',
                ],
                '2022-07-22',
                '1900 - 0000',
                'SAST',
            ],
            'test hours with : and without spaces' => [
                [
                    '2022-07-22 19:00:00',
                    '2022-07-23 00:00:00',
                ],
                '2022-07-22',
                '19:00-00:00',
                'SAST',
            ],
            'test hours with : and spaces' => [
                [
                    '2022-07-22 19:00:00',
                    '2022-07-23 00:00:00',
                ],
                '2022-07-22',
                '19:00 - 00:00',
                'SAST',
            ],
            'test hours with one alpha, : and spaces' => [
                [
                    '2022-07-22 19:00:00',
                    '2022-07-23 00:00:00',
                ],
                '2022-07-22',
                'L 19:00 - 00:00',
                'SAST',
            ],
            'test hours with two alpha, : and spaces' => [
                [
                    '2022-07-22 19:00:00',
                    '2022-07-23 00:00:00',
                ],
                '2022-07-22',
                'LA 19:00 - 00:00',
                'SAST',
            ],
            'test hours with one : and spaces and pm' => [
                [
                    '2022-07-22 11:00:00',
                    '2022-07-22 20:00:00',
                ],
                '2022-07-22',
                '11:00 AM - 08:00 PM',
                'SAST',
            ],
            'test hours with one alpha, : and spaces and pm' => [
                [
                    '2022-07-22 19:00:00',
                    '2022-07-23 00:00:00',
                ],
                '2022-07-22',
                'L 7:00 PM - 12:00 AM',
                'SAST',
            ],
            'test hours with two alpha, : and spaces and pm' => [
                [
                    '2022-07-22 19:00:00',
                    '2022-07-23 00:00:00',
                ],
                '2022-07-22',
                'LA 7:00 PM - 12:00 AM',
                'SAST',
            ],
            'test sage hours with no space between the am/pm seperator' => [
                [
                    '2022-09-26 17:00:00',
                    '2022-09-27 02:00:00',
                ],
                '2022-09-26',
                '5:00PM - 02:00AM',
                'SAST',
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideDateRangeChangeTimeZoneData(): array
    {
        return [
            'test hours with out : and spaces' => [
                [
                    '2022-07-22 20:00:00',
                    '2022-07-23 01:00:00',
                ],
                '2022-07-22',
                '1900-0000',
                'BST',
                'SAST',
            ],
            'test hours without : and with spaces' => [
                [
                    '2022-07-22 20:00:00',
                    '2022-07-23 01:00:00',
                ],
                '2022-07-22',
                '1900 - 0000',
                'BST',
                'SAST',
            ],
            'test hours with : and without spaces' => [
                [
                    '2022-07-22 20:00:00',
                    '2022-07-23 01:00:00',
                ],
                '2022-07-22',
                '19:00-00:00',
                'BST',
                'SAST',
            ],
            'test hours with : and spaces' => [
                [
                    '2022-07-22 20:00:00',
                    '2022-07-23 01:00:00',
                ],
                '2022-07-22',
                '19:00 - 00:00',
                'BST',
                'SAST',
            ],
            'test hours with one : and spaces and pm' => [
                [
                    '2022-07-22 12:00:00',
                    '2022-07-22 21:00:00',
                ],
                '2022-07-22',
                '11:00 AM - 08:00 PM',
                'BST',
                'SAST',
            ],
            'test hours with one alpha, : and spaces' => [
                [
                    '2022-07-22 20:00:00',
                    '2022-07-23 01:00:00',
                ],
                '2022-07-22',
                'L 19:00 - 00:00',
                'BST',
                'SAST',
            ],
            'test hours with two alpha, : and spaces' => [
                [
                    '2022-07-22 20:00:00',
                    '2022-07-23 01:00:00',
                ],
                '2022-07-22',
                'LA 19:00 - 00:00',
                'BST',
                'SAST',
            ],
            'test hours with one alpha, : and spaces and pm' => [
                [
                    '2022-07-22 20:00:00',
                    '2022-07-23 01:00:00',
                ],
                '2022-07-22',
                'L 7:00 PM - 12:00 AM',
                'BST',
                'SAST',
            ],
            'test hours with two alpha, : and spaces and pm' => [
                [
                    '2022-07-22 20:00:00',
                    '2022-07-23 01:00:00',
                ],
                '2022-07-22',
                'LA 7:00 PM - 12:00 AM',
                'BST',
                'SAST',
            ],
            'test sage hours with no space between the am/pm seperator' => [
                [
                    '2022-09-26 18:00:00',
                    '2022-09-27 03:00:00',
                ],
                '2022-09-26',
                '5:00PM - 02:00AM',
                'BST',
                'SAST',
            ],
        ];
    }
}
