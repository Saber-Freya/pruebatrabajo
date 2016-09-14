<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\EventModel;
use MaddHatter\LaravelFullcalendar\Calendar;

use Illuminate\Http\Request;

class CalendarController extends Controller {

    public function index(){

        $event = EventModel::all();

        foreach ($event as $eve) {
            $events[] = Calendar::event(
                $eve->title, //event title
                $eve->allDay, //full day event?
                $eve->start, //start time (you can also use Carbon instead of DateTime)
                $eve->end //end time (you can also use Carbon instead of DateTime)
                //$eve->id //optionally, you can specify an event ID
            );
        }

        $events[] = Calendar::event(
            'Event One', //event title
            false, //full day event?
            '2015-06-05T0800', //start time (you can also use Carbon instead of DateTime)
            '2015-06-05T0800', //end time (you can also use Carbon instead of DateTime)
            0 //optionally, you can specify an event ID
        );

        $eloquentEvent = EventModel::first(); //EventModel implements MaddHatter\LaravelFullcalendar\Event

        $calendar = Calendar::addEvents($events)//add an array with addEvents
        ->addEvent($eloquentEvent, [ //set custom color fo this event
            'color' => '#800',
        ])->setOptions([ //set fullcalendar options
            'firstDay' => 1
        ])->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)
            'viewRender' => 'function() {alert("Callbacks!");}'
        ]);

        return view('pages.calendar', compact('calendar'));

    }
}