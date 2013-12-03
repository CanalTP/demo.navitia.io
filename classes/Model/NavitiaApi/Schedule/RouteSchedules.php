<?php

namespace Nv2\Model\NavitiaApi\Schedule;

use Nv2\Model\NavitiaApi\Base\NavitiaApi;
use Nv2\Model\Entity\Schedule\RouteSchedules\Result;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class RouteSchedules extends NavitiaApi
{
    // request
    protected $routeUri;
    protected $datetime;

    // response
    public $ScheduleList;

    private function __construct()
    {
        $this->routeUri = null;
        $this->datetime = null;

        $this->ScheduleList = null;
    }

    public static function create()
    {
        return new self();
    }

    public function routeUri($uri)
    {
        $this->routeUri = $uri;
        return $this;
    }

    public function dateTime($datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }

    public function getResult()
    {
        $feed = NavitiaRequest::create()
            ->api('route_schedules')
            ->filter('route', 'uri', '=', $this->routeUri)
            ->param('from_datetime', $this->datetime)
            ->param('depth', 2)
            ->execute();

        $feed = json_decode($feed['content']);

        if (isset($feed->route_schedules)) {
            foreach ($feed->route_schedules as $schedule) {
                $scheduleObject = Result::create()
                    ->fill($schedule);
                $this->addSchedule($scheduleObject);
            }
        }

        return $this->ScheduleList;
    }

    public function addSchedule(Result $schedule)
    {
        $this->ScheduleList[] = $schedule;
    }
}