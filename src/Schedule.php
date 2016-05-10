<?php

namespace webtoucher\calendar;

/**
 * Calendar schedule.
 *
 */
class Schedule
{
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
        if (strpos($dir, DIRECTORY_SEPARATOR) !== 0) {
            $dir = $this->getDefaultDirectory() . $dir;
        }
        if (!$handle = opendir($dir)) {
            throw new Exception("The schedule \"$country\" was not found.");
        }
        while (false !== ($filename = readdir($handle))) {
            if (preg_match('/^(\d{4}|default).json$/i', $filename, $matches)) {
                $this->rules[$matches[1]] = json_decode(file_get_contents($dir . $filename), true);
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
        if (array_key_exists($year, $this->rules)) {
            return $this->checkRules($date, $year);
        } elseif (array_key_exists('default', $this->rules)) {
            return $this->checkRules($date, 'default');
        }
        return null;
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

    /**
     * @param \DateTime $date
     * @param string $name
     * @return boolean
     */
    private function checkRules($date, $name)
    {
        if (isset($this->rules[$name]['inc'])) {
            foreach ($this->rules[$name]['inc'] as $rule) {
                if ($this->checkRule($date, $rule)) {
                    return false;
                }
            }
        }
        if (isset($this->rules[$name]['exc'])) {
            foreach ($this->rules[$name]['exc'] as $rule) {
                if ($this->checkRule($date, $rule)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function getDefaultDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'rules' . DIRECTORY_SEPARATOR;
    }
}
