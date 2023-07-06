<?php declare(strict_types=1);
/**
 * @author    Jacques Marneweck <jacques@siberia.co.za>
 * @copyright 2022-2023 Jacques Marneweck.  All rights strictly reserved.
 */

namespace Jacques\TeleOpti;

use Carbon\Carbon;
use Composer\Pcre\Preg;

final class DateRange
{
    /**
     * Parse the shifttimes from TeleOpti and return an array of the start date and time
     * as well as the end date and time.  Conversion of timezones are also done as shift
     * times in schedules typically in BST (either GMT+0/GMT+1 from the Teleopti server
     * in the UK).
     *
     * @throws \Exception
     */
    public static function parse(string $date, string $shifttimes, string $timezone, ?string $totimezone = null): array
    {
        /**
         * Carbon incorrectly uses a timezone offset of 0 instead of 1 for BST.
         */
        if ('BST' === $timezone) {
            $timezone = 'Europe/London';
        }

        if (!is_null($totimezone) && 'BST' === $totimezone) {
            $totimezone = 'Europe/London';
        }

        /**
         * Match against the following formats:
         *
         *  - hh:mm-hh:mm
         *  - hhmm-hhmm
         *  - hh:mm - hh:mm
         *  - hhmm - hhmm
         *  - A hh:mm - hh:mm
         *  - AA hh:mm - hh:mm
         */
        if (Preg::isMatch('!^\D?\D?\s?(\d+:?\d+)\s?-\s?(\d+:?\d+\d+)$!', $shifttimes, $matches)) {
            if (strlen($matches['1']) === 4) {
                $matches['1'] = sprintf(
                    '%02d:%02d',
                    substr($matches['1'], 0, 2),
                    substr($matches['1'], 2, 2)
                );
            }

            if (strlen($matches['2']) === 4) {
                $matches['2'] = sprintf(
                    '%02d:%02d',
                    substr($matches['2'], 0, 2),
                    substr($matches['2'], 2, 2)
                );
            }

            $startdate = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $matches['1'], $timezone);
            $enddate = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $matches['2'], $timezone);
        /**
         * Match against the following formats:
         *
         *  - hh:mm-hh:mm
         *  - hhmm-hhmm
         *  - hh:mm - hh:mm
         *  - hhmm - hhmm
         */
        } elseif (Preg::isMatch('!^\D?\D?\s?(\d+:?\d+\s?[AP][M])\s?-\s?(\d+:?\d+\d+\s?[AP][M])$!', $shifttimes, $matches)) {
            $startdate = Carbon::createFromFormat('Y-m-d H:i A', $date . ' ' . $matches['1'], $timezone);
            $enddate = Carbon::createFromFormat('Y-m-d H:i A', $date . ' ' . $matches['2'], $timezone);
        } else {
            throw new \Exception('Unable to parse the date range.');
        }

        if ($enddate->lt($startdate)) {
            $enddate->addDay();
        }

        if (!is_null($totimezone)) {
            $startdate->setTimezone($totimezone);
            $enddate->setTimezone($totimezone);
        }

        return [$startdate->toDateTimeString(), $enddate->toDateTimeString()];
    }
}
