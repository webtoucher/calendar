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
     * @param \DateTime|integer $days End date or positive/negative number of calendar days.
     * @return integer Number of working days.
     */
    public function calendarToWorkingDays(\DateTime $date, $days)
    {
        if (!$days) {
            return 0;
        }
        if ($days instanceof \DateTime) {
            $days = $this->intervalToDays($date->diff($days));
        }

        $workingDays = 0;
        $sign = $days < 0 ? -1 : 1;
        $step = $this->getStep($sign);
        for ($day = 0; $day != $days; $day += $sign) {
            $date->add($step);
            if (!$this->schedule->isHoliday($date)) {
                $workingDays += $sign;
            }
        }
        return $workingDays;
    }

    /**
     * @param \DateTime $date Start date.
     * @param integer $days Positive/negative number of working days.
     * @return integer Number of working days.
     */
    public function workingToCalendarDays(\DateTime $date, $days)
    {
        if (!$days) {
            return 0;
        }

        $workingDays = 0;
        $sign = $days < 0 ? -1 : 1;
        $step = $this->getStep($sign);
        $endDate = clone $date;
        $day = 0;
        while ($day != $days) {
            $endDate->add($step);
            if (!$this->schedule->isHoliday($endDate)) {
                $day += $sign;
            }
        }
        return $this->intervalToDays($date->diff($endDate));
    }

    /**
     * @param \DateTime $date Start or End date (see param $isEndDate).
     * @param boolean $isEndDate Search direction: FALSE - from set date, TRUE - to set date.
     * @return \DateTime Working day.
     */
    public function findClosestWorkingDay(\DateTime $date, $isEndDate = false)
    {
        $step = $this->getStep($isEndDate ? -1 : 1);
        $foundDate = clone $date;
        while ($this->schedule->isHoliday($foundDate)) {
            $foundDate->add($step);
        }
        return $foundDate;
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

    /**
     * @param \DateInterval $interval
     * @return integer  Positive or negative shift.
     */
    private function intervalToDays(\DateInterval $interval)
    {
        $days = $interval->days;
        if ($interval->invert == 1) {
            $days *= -1;
        }
        return $days;
    }
}
