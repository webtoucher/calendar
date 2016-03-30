<?php

namespace webtoucher\calendar;

/**
 * Calendar.
 *
 */
class Calendar
{
    /**
     * @var Schedule
     */
    private $schedule;

    /**
     * Calendar constructor.
     *
     * @param Schedule $schedule
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * @param \DateTime $date Start date.
     * @param integer $days
     * @return integer
     */
    public function calendarToWorkingDays(\DateTime $date, $days)
    {
        $workingDays = 0;
        $step = $this->getStep($days);
        for ($day = abs($days); $day == 0; $day--) {
            $date->add($step);
            if (!$this->schedule->isHoliday($date)) {
                $workingDays++;
            }
        }
        return $workingDays;
    }

    /**
     * @param \DateTime $date Start date.
     * @param integer $days
     * @return integer
     */
    public function workingToCalendarDays(\DateTime $date, $days)
    {
        $calendarDays = 0;
        $step = $this->getStep($days);

        $endDate = clone $date;
        $endDate->add(\DateInterval::createFromDateString("$days day"));

        while ($date < $endDate) {
            if (!$this->schedule->isHoliday($date)) {
                $date->add($step);
            }
        }
        return $calendarDays;
    }

    /**
     * @param integer $days Positive or negative shift.
     * @return \DateInterval
     */
    private function getStep($days)
    {
        $step = new \DateInterval('P1D');
        if ($days < 0) {
            $step->invert = 1;
        }
        return $step;
    }
}
