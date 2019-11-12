<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;


class SessionDateController extends Controller
{
	/**
	*parameters [starting_date,days,sessions_number]
	*return sessionsDate Array
	*/
    public function sessionDate(Request $request)
    {
    	$request->validate([
            'starting_date' => 'required|date',
            'days'  => 'required|array|min:1',
            'days.*' => 'required|integer|min:1|max:7',
            'sessions_number' => 'required|integer',
        ]);

    	$starting_date = $request->starting_date;
    	$days = $request->days; 
    	$sessions_number = $request->sessions_number;
    	$total_sessions = $sessions_number * 30;

    	$difference_array = $this->create_difference_array($days);

        $session[0]=$this->cast_to_date($this->create_carbon_object($starting_date));

        $index_of_difference = 1;
    	for ($number_of_session = 1; $number_of_session < $total_sessions ;$number_of_session++)
    	{
            if($index_of_difference == sizeof($difference_array))
             {
	             $index_of_difference = 0;
             }
            $datetime = $this->create_carbon_object($session[$number_of_session - 1]);

            $datetime->addDays($difference_array[$index_of_difference]);
            $session[$number_of_session] =$this->cast_to_date($datetime); 
            $index_of_difference++;
    	}    	
    	return response()->json($session , 200);
    }



    /**

    * function for Subtract each two dates in days array
    * parameters [days]
    * return array
    */
    public function create_difference_array($days)
    {
    	for($number_of_days = sizeof($days) - 1 ;$number_of_days >= 0  ;$number_of_days--)
    	{
    		if($number_of_days != 0)
    		{
    		    $difference_array[$number_of_days] = $days[$number_of_days] - $days[$number_of_days - 1];
    	    }
    	    else
    	    {
    	    	$difference_array[$number_of_days] = 7 - $days[sizeof($days) - 1] + $days[$number_of_days];
    	    }
    	}
    	return $difference_array;
    }


    /**
    * function to make Carbon object using the date
    * parameter [date]
    * return carbon object
    */
    public function create_carbon_object($datetime)
    {
    	$datetime = Carbon::createFromFormat('Y-m-d', $datetime);
    	return $datetime;
    }


    /**
    * function to cast datetime to date
    * parameter [datetime]
    * return casted date
    */
    public function cast_to_date($datetime)
    {
       $date = date_format($datetime,'Y-m-d');
       return $date;	
    }


}

