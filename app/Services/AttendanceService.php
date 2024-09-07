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


}