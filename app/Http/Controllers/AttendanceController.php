<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Handle the check-in request.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkIn()
    {
        return $this->attendanceService->checkIn();
    }

    /**
     * Handle the check-out request.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkOut()
    {
        return  $this->attendanceService->checkOut();    
    }
}
