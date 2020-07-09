<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RefreshSpotifyAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github-now:refresh-spotify-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the spotify access token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clientId = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.secret');

        if (empty($clientId)) {
            return;
        }

        $accessToken = Cache::get('spotifyAccessToken');
        $refreshToken = Cache::get('spotifyRefreshToken');

        try {
            $response = Http::asForm()->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
            ])->post('https://accounts.spotify.com/api/token', [
                'client_id' => $clientId,
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'redirect_uri' => route('spotify.callback'),
            ]);
        } catch (RequestException $e) {
            $this->error('Access token failed to update', $e->getMessage());
        }

        $body = json_decode((string) $response->getBody());

        Cache::put('spotifyAccessToken', $body->access_token);

        $this->info('Access token updated successfully');

        return 0;
    }
}
