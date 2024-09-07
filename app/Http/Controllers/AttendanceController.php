<?php

namespace App\Http\Controllers;

use App\Http\Requests\TotalAttendanceHoursRequest;
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

    /**
     * Retrieve the total number of hours worked by the authenticated user
     * within a specified date range.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTotalHours(TotalAttendanceHoursRequest $request)
    {

        try {
            $totalHours = $this->attendanceService->getTotalHoursWorked(
                $request->start_date,
                $request->end_date
            );

            return apiResponse(
                true,
                'Total hours worked retrieved successfully.',
                Response::HTTP_OK,
                ['total_hours' => $totalHours]
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'An error occurred while retrieving total hours.',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
