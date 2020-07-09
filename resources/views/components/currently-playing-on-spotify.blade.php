## Currently Playing ♬

@if (!is_null($currentTrack))
[<img align="left" width="100" src="{{ array_shift($currentTrack->item->album->images)->url }}">]({{ $currentTrack->item->external_urls->spotify }})
{{ collect($currentTrack->item->album->artists)->pluck('name')->join(' ') }} - {{ $currentTrack->item->name }}

**Album:** {{ $currentTrack->item->album->name }}

&nbsp;
@else
Nothing 🙉
@endif
