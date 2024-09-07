<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\MonthlyWorkingHoursNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
class SendMonthlyWorkingHoursNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:monthly-working-hours';
    protected $description = 'Send notification to users with their total working hours for the previous month';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        $previousMonth = $now->subMonth();

        User::chunk(100, function ($users) use ($previousMonth) {
            foreach ($users as $user) {
                $totalHours = DB::table('attendances')
                ->where('user_id', $user->id)
                    ->whereBetween('check_in', [$previousMonth->startOfMonth(), $previousMonth->endOfMonth()])
                    ->get()
                    ->sum(function ($attendance) {
                        $checkIn = Carbon::parse($attendance->check_in);
                        $checkOut = Carbon::parse($attendance->check_out);
                        return $checkOut->diffInHours($checkIn) + $checkOut->diffInMinutes($checkIn) / 60;
                    });

                Notification::send($user, new MonthlyWorkingHoursNotification($totalHours));
            }
        });


        $this->info('Monthly working hours notifications sent.');
    }

}
