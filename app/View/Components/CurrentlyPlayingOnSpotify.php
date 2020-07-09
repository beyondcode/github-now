<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;
use SpotifyWebAPI\SpotifyWebAPI;

class CurrentlyPlayingOnSpotify extends Component
{
    /** @var SpotifyWebAPI */
    protected $api;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->api = new SpotifyWebAPI();
        $this->api->setAccessToken(Cache::get('spotifyAccessToken'));
    }

    public function currentTrack()
    {
        return $this->api->getMyCurrentTrack();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.currently-playing-on-spotify');
    }

    public function shouldRender()
    {
        return !empty(Cache::get('spotifyAccessToken'));
    }
}
