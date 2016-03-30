# Calendar
This library helps to calculate date difference considering holidays.

[![Latest Stable Version](https://poser.pugx.org/webtoucher/calendar/v/stable)](https://packagist.org/packages/webtoucher/calendar)
[![Total Downloads](https://poser.pugx.org/webtoucher/calendar/downloads)](https://packagist.org/packages/webtoucher/calendar)
[![Daily Downloads](https://poser.pugx.org/webtoucher/calendar/d/daily)](https://packagist.org/packages/webtoucher/calendar)
[![Latest Unstable Version](https://poser.pugx.org/webtoucher/calendar/v/unstable)](https://packagist.org/packages/webtoucher/calendar)
[![License](https://poser.pugx.org/webtoucher/calendar/license)](https://packagist.org/packages/webtoucher/calendar)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require webtoucher/calendar "*"
```

or add

```
"webtoucher/calendar": "*"
```

to the ```require``` section of your `composer.json` file.

## Usage

Create calendar for your country:

```php
$calendar = new Calendar(new Schedule('ru'));
```

Also you can use your external rules:

```php
$calendar = new Calendar(new Schedule('/var/www/my-site/rules/pl'));
```

Calculate number of working days with one of follow ways:

```php
echo $calendar->calendarToWorkingDays(new \DateTime('2016-02-24'), new \DateTime('2016-02-29')); // 3
```

```php
echo $calendar->calendarToWorkingDays(new \DateTime('2016-02-29'), new \DateTime('2016-02-24')); // -3
```

```php
echo $calendar->calendarToWorkingDays(new \DateTime('2016-02-24'), 5); // 3
```

```php
echo $calendar->calendarToWorkingDays(new \DateTime('2016-02-29'), -5); // -3
```

Calculate number of calendar days by working days with one of follow ways:

```php
echo $calendar->workingToCalendarDays(new \DateTime('2016-02-24'), 3); // 5
```

```php
echo $calendar->workingToCalendarDays(new \DateTime('2016-02-29'), -3); // -5
```

## Additional information

You can help the project by adding rules for another countries. Send me your pull requests. But please use the same formating for json files.
