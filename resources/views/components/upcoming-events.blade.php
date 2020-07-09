## Upcoming Events

Event | When | Duration
----|----|----
@foreach($events as $event)
{{ $redactName ? 'Redacted' : $event->name }} | {{ $event->startDate->diffForHumans() }} | {{ $event->startDate->diffInHours($event->endDate) }} {{ \Illuminate\Support\Str::plural('hour', $event->startDate->diffInHours($event->endDate)) }}
@endforeach
