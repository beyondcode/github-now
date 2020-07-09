<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Spatie\GoogleCalendar\Event;

class UpcomingEvents extends Component
{
    public $redactName;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($redactName = true)
    {
        $this->redactName = $redactName;
    }

    public function events()
    {
        return Event::get()->take(10);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.upcoming-events');
    }
}
