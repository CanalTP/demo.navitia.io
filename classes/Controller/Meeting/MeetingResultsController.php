<?php

namespace Nv2\Controller\Meeting;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Http\Request;

class MeetingResultsController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function run()
    {
        $this->template->fetch('module/meeting/results.php');
    }
}