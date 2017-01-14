<?php

declare(strict_types=1);

namespace Premise\Utilities\DateTime;

use Carbon\Carbon;

/**
 * Class CurrentDateTime.
 */
class CurrentDateTime
{
    /** @var null */
    private static $inst = null;
    /** @var Carbon */
    private $carbon;
    /** @var Carbon */
    private $saveCarbon;

    /**
     * CurrentDateTime constructor.
     *
     * @param Carbon $carbon
     */
    public function __construct(Carbon $carbon)
    {
        $this->carbon = $carbon;
    }

    /**
     * @return CurrentDateTime|null
     */
    public static function new()
    {
        if (self::$inst === null) {
            self::$inst = new self(Carbon::now());
        }

        return self::$inst;
    }

    /**
     * @return CurrentDateTime|string
     */
    public function currentDate()
    {
        $this->copyCarbonDate();

        return $this->returnDate($this->carbon->format('Y-m-d'));
    }

    /**
     * @param int $days
     *
     * @return string
     */
    public function daysBack(int $days)
    {
        $this->copyCarbonDate();

        return $this->returnDate($this->carbon->subDays($days)->format('Y-m-d'));
    }

    /**
     *  save the incoming carbon date.
     */
    protected function copyCarbonDate()
    {
        $this->saveCarbon = new $this->carbon($this->carbon);
    }

    /**
     * @param string $date
     *
     * @return string
     */
    protected function returnDate(string $date)
    {
        $this->carbon = $this->saveCarbon;

        return $date;
    }
}
