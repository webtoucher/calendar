<?php

namespace webtoucher\calendar;

/**
 * Calendar schedule.
 *
 */
class Schedule
{
    const DEFAULT_DIRECTORY = __DIR__ . DIRECTORY_SEPARATOR . 'rules' . DIRECTORY_SEPARATOR;

    /**
     * @var array
     */
    private $rules = [];

    /**
     * Schedule constructor.
     *
     * @param string $country
     * @throws Exception When schedule is not exists.
     */
    public function __construct($country)
    {
        $dir = $country . DIRECTORY_SEPARATOR;
        if (strpos($dir, DIRECTORY_SEPARATOR) !== 1) {
            $dir = self::DEFAULT_DIRECTORY . $dir;
        }
        if (!$handle = opendir($dir)) {
            throw new Exception("The schedule \"$country\" was not found.");
        }
        while (false !== ($filename = readdir($handle))) {
            if (preg_match('/^(\d{4}).json$/i', $filename, $matches)) {
                $this->rules[$matches[0]] = json_decode(require $filename);
            }
        }
        closedir($handle);
    }

    /**
     * @param \DateTime $date
     * @return boolean|null
     */
    public function isHoliday(\DateTime $date)
    {
        $year = $date->format('Y');
        if (!array_key_exists($year, $this->rules)) {
            return null;
        }
        if (isset($this->rules[$year]['inc'])) {
            foreach ($this->rules[$year]['inc'] as $rule) {
                if ($this->checkRule($date, $rule)) {
                    return false;
                }
            }
        }
        if (isset($this->rules[$year]['exc'])) {
            foreach ($this->rules[$year]['exc'] as $rule) {
                if ($this->checkRule($date, $rule)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param \DateTime $date
     * @param array|string $rule
     * @return boolean
     */
    public function checkRule(\DateTime $date, $rule)
    {
        if (is_string($rule)) {
            $rule = ['n.j' => $rule];
        }
        return $date->format(key($rule)) === current($rule);
    }
}
