<?php declare(strict_types=1);

namespace Premise\Tests;

use Carbon\Carbon;
use PHPUnit_Framework_TestCase;
use Premise\Utilities\DateTime\CurrentDateTime;

class CurrentDateTimeTest extends PHPUnit_Framework_TestCase
{
    public function testDaysBack_Positive()
    {
        $daysBack = 180;

        $monthsBack = CurrentDateTime::new()->daysBack($daysBack);

        $carbonValue = Carbon::now()->subDays($daysBack)->format('Y-m-d');

        $this->assertSame($carbonValue, $monthsBack);
    }

    public function testDaysBack_Negative()
    {
        $daysBack = 180;

        $monthsBack = CurrentDateTime::new()->daysBack(181);

        $carbonValue = Carbon::now()->subDays($daysBack)->format('Y-m-d');

        $this->assertNotSame($carbonValue, $monthsBack);
    }

    public function testCurrentDate_Positive()
    {
        $currentDate = CurrentDateTime::new()->currentDate();

        $carbonValue = Carbon::now()->format('Y-m-d');

        $this->assertSame($carbonValue, $currentDate);
    }

    public function testCurrentDate_Negative()
    {
        $currentDate = CurrentDateTime::new()->currentDate();

        $carbonValue = Carbon::now()->addDay()->format('Y-m-d');

        $this->assertNotSame($carbonValue, $currentDate);
    }
}
