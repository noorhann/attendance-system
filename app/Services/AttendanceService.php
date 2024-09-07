<?php

namespace App\Services;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Response;

class AttendanceService
{
    /**
    * Check-in the authenticated user.
    *
    *  @return \Illuminate\Http\Response
    */
    public function checkIn()
    {
            $user = Auth::user();

            $existingAttendance = Attendance::where('user_id', $user->id)
                ->whereNull('check_out')
                ->latest('check_in')
                ->first();    
                
            if($existingAttendance){
                return apiResponse(
                    false,
                    'You are already checked in.',
                    Response::HTTP_BAD_REQUEST,
                );
            }

            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->check_in = Carbon::now();
            $attendance->save();

            return apiResponse(
                true,
                'Checked In Successfully',
                Response::HTTP_OK,
            );
    }

    /**
     * Check-out the authenticated user.
     *
     *  @return \Illuminate\Http\Response
     */
    public function checkOut()
    {
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereNull('check_out')
            ->latest('check_in')
            ->first();

        if (!$attendance) {
            return apiResponse(
                false,
                'No active check-in record found.',
                Response::HTTP_NOT_FOUND,
            );
        }

        $attendance->check_out = Carbon::now();
        $attendance->save();

        return apiResponse(
            true,
            'Checked Out Successfully',
            Response::HTTP_OK,
        );
    }

    /**
     * Calculate the total number of hours worked by the authenticated user
     * within a specified date range.
     *
     * @param string $startDate The start date of the range in 'Y-m-d' format.
     * @param string $endDate The end date of the range in 'Y-m-d' format.
     * @return float The total number of hours worked.
     */
    public function getTotalHoursWorked($startDate, $endDate)
    {
        $user = Auth::user();

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('check_in', [$startDate, $endDate])
            ->whereNotNull('check_out')
            ->get();

        $totalHours = 0;
        $totalMinutes = 0;

        $attendances->each(function ($attendance) use (&$totalHours, &$totalMinutes) {
            if (is_array($attendance->hours_worked) && isset($attendance->hours_worked['hours'], $attendance->hours_worked['minutes'])) {
                $totalHours += $attendance->hours_worked['hours'];
                $totalMinutes += $attendance->hours_worked['minutes'];
            }
        });

        $totalHours += floor($totalMinutes / 60);
        $totalMinutes = $totalMinutes % 60;

        return [
            'totalHours' => $totalHours,
            'totalMinutes' => $totalMinutes 
        ];
    }

}