<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use RRule\RRule;
use Validator;
use Auth;

class EventController extends Controller
{   
	// public function show($eventid){
    //     //
    // }
    
    public function store(Request $request)	{

        if($user = Auth::user()) {
            $event = new Event;

            $input = $request->input();

            // Single fields
            $event->user_id = (int)$input['user_id'];
            $event->title = $input['title'];
            $event->description = $input['description'];
            $event->start = $input['start'];
            $event->duration = ($input['duration'] ? $this->hoursToMinutes($input['duration']) : null);
            $event->allDay = $input['allday'];
            $event->project_id = $input['project_id'];
            $event->price = $input['price'];

            $rrule = $input['rrule'];
            $freq = $rrule['freq'];

            // Recurrence fields
            if($freq != "ONCE") {

                $rruleData = [];
                
                $rruleData['FREQ'] = $freq;
                $rruleData['DTSTART'] = $input['start'];
                
                switch ($freq) {

                    // Daily //
                    case 'DAILY': 

                        $dailyfreq = (int)$rrule['daily']['freq'];

                        if($dailyfreq == 1) {
                            $rruleData['INTERVAL'] = 1;
                        } else if($dailyfreq == 2) {
                            $rruleData['INTERVAL'] = (int)$rrule['daily']['interval'];
                        }
                        break;
                    
                    // Weekly //
                    case 'WEEKLY': 

                        $rruleData['INTERVAL'] = (int)$rrule['weekly']['interval'];                    
                        $rruleData['BYDAY'] = $rrule['weekly']['bywdaylist'];
                        break;

                    // Monthly //
                    case 'MONTHLY': 

                        $monthlyfreq = $rrule['monthly']['freq'];
                        
                        if($monthlyfreq == 'datenum'){

                            $rruleData['BYMONTHDAY'] = (int)$rrule['monthly']['datenum']['daynum'];
                            $rruleData['INTERVAL'] = (int)$rrule['monthly']['datenum']['interval'];

                        } else if($monthlyfreq == 'datename'){
        
                            $rruleData['BYDAY'] = $rrule['monthly']['datename']['nth'] . $rrule['monthly']['datename']['day'];
                            $rruleData['INTERVAL'] = (int)$rrule['monthly']['datename']['interval'];
                        }
                        break;

                    // Yearly //
                    case 'YEARLY': 

                        $rruleData['INTERVAL'] = (int)$rrule['yearly']['interval'];
                        
                        $yearlyfreq = $rrule['yearly']['freq'];
                        
                        if($yearlyfreq == 'date'){

                            $rruleData['BYMONTHDAY'] = (int)$rrule['yearly']['date']['daynum'];
                            $rruleData['BYMONTH'] = (int)$rrule['yearly']['date']['month'];

                        } else if($yearlyfreq == 'nthdate') {
                            
                            $rruleData['BYSETPOS'] = $rrule['yearly']['nthdate']['nth'];
                            $rruleData['BYDAY'] = $rrule['yearly']['nthdate']['day'];
                            $rruleData['BYMONTH'] = (int)$rrule['yearly']['nthdate']['month'];
                            
                        }                    
                        break;
                }

                // End Type
                if($rrule['end']['endtype'] == "occurrences") {
                    $rruleData['COUNT'] = $rrule['end']['occurrences'];
                } else if($rrule['end']['endtype'] == "date") {
                    $rruleData['UNTIL'] = $rrule['end']['enddate'];
                }
                    
                $rrule = new RRule($rruleData);
        
                $event->rrule = $rrule;
            }

            $event->save();

        }

    }

    public function update(Request $request, $eventid){
		
        //
    }

    public function destroy($eventid){

        if($user = Auth::user()) {

            $event = Event::find($eventid);
            
            $event->delete();
        
        }
    }

    public function getEventList(Request $request){
        
        $userid = Auth::id();

        $events = Event::select(
            'id', 
            'title', 
            'description', 
            'rrule', 
            'start', 
            'allday')
            ->where('user_id', $userid)
            ->with('project')
            ->get();
            
        foreach($events as $event) {
            if($event->rrule != null) {
                $rule = new RRule($event->rrule);
                $event->rrule = $rule->humanReadable(['locale' => 'nl']);
            }
        }       

        return $events;
    }

    public function getCalendarEvents(Request $request){
        
        $from = new Carbon($request->from);
        $till = new Carbon($request->till);

        $singleEvents = Event::select(
            'id',
            'user_id',
            'title', 
            'allDay',
            'start', 
            'duration', 
            'allDay', 
            'description')
            ->where('rrule', null)
            ->whereHas('project')
            ->whereBetween('start', [$from, $till])
            ->orderBy('start', 'DESC')
            ->get()
            ->toArray();
        
        $rruleEvents = Event::select('id')
            ->where('rrule' , '!=', null)
            ->where('start', '<=', $till)
            ->get();

        
        $occurrenceEvents = array();

        // Get occurrences
        foreach($rruleEvents as $rruleEvent) {

            $eventid = $rruleEvent->id;

            $occurrences = $this->getEventOccurrencesBetween($eventid, $from, $till);
            $occurrences = $occurrences->toArray();

            $occurrenceEvents = array_merge($occurrenceEvents, $occurrences);
        }

        $calendarEvents =[];

        // Combine event array's
        $events = array_merge($singleEvents, $occurrenceEvents);

        foreach($events as $event) {

            // Convert tinyint to bool for Calendar
            $event['allDay'] = ($event['allDay'] == 1 ? true : false);

            // Set endtime if it's not an allDay event
            if(!$event['allDay']){
                $startDate = Carbon::parse($event['start']);
                $event['end'] = $startDate->addMinutes($event['duration']); // addMinutes
            }
            $calendarEvents[] = $event;
        }
        return json_encode($calendarEvents);
    }

    public static function getEventOccurrencesBetween($eventid, $from, $till){

        $event = Event::find($eventid, [
            'id',
            'user_id',
            'title', 
            'start', 
            'allDay',
            'duration', 
            'rrule',
            'allDay', 
            'description'
        ]);
        
        $rrule = new RRule($event->rrule);

        $events = collect([]);

        $eventOccurrences = $rrule->getOccurrencesBetween($from, $till);

        foreach($eventOccurrences as $occurrence) {
            $newEvent = $event->replicate();
            $newEvent->start = Carbon::parse($occurrence);
            $events->push($newEvent);
        }
        
        return $events;
    }

	private function hoursToMinutes($inputTime){

        $time = explode(':', $inputTime);

        return (($time[0]*60) + $time[1]);

    }
}
