<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getSchedule() {
        //get user schedule
        $schedule = Schedule::where('user_id',Auth::user()->id)
            ->whereRaw('MONTH(cal_date) = MONTH(CURRENT_DATE())')
            ->whereRaw('YEAR(cal_date) = YEAR(CURRENT_DATE())')
            ->select(DB::raw('DAY(cal_date) as cal_day'),'cal_date','event')
            ->get();

        $arr = [];

        foreach ($schedule as $sched) {
            $arr[$sched->cal_day] = $sched->event;
        }

        $today = today();
        $dates = [];
        $returnData = "<h3 class=\"font-weight-bold\">".$today->format('M Y')."</h3>";

        for($i=1; $i < $today->daysInMonth + 1; ++$i) {
            $dates[$i]["dayOfMonth"] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('j');
            $dates[$i]["dayOfWeek"] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('D');
        }

        foreach ($dates as $date) {
            $event = @$arr[$date["dayOfMonth"]];
            $color = ($event != "") ? 'style="background-color: #EDFCED"' : '';

            $returnData .= "<div class=\"container-fluid\">
                                <div class=\"row\" $color>
                                    <div class=\"col-3 border-top p-3\">".$date["dayOfMonth"]." ".$date["dayOfWeek"]."</div>
                                    <div class=\"col-9 border-top p-3\">".$event."</div>
                                </div>
                            </div>";
        }

        return $returnData;
    }

    public function saveSched(Request $request) {
        /*$validatedData = $request->validate([
            'event' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'day' => 'required'
        ]);*/

        $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d');

        Schedule::where('user_id',Auth::user()->id)
            ->whereRAW("DATE(cal_date) BETWEEN DATE('".$from_date."') AND DATE('".$to_date."')")
            ->delete();

        $dates = CarbonPeriod::create( $from_date, $to_date );

        foreach ($dates as $date) {
            $dayOfWeek = Carbon::parse($date)->format('D');
            $date = Carbon::parse($date)->format('Y-m-d');

            if (in_array($dayOfWeek, $request->day)) {
                $schedule = new Schedule();
                $schedule['event'] = $request->event;
                $schedule['cal_date'] = $date;
                $schedule['user_id'] = Auth::user()->id;
                $schedule->save();
            }
        }

        return $this->getSchedule();
    }
}
