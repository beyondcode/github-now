<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SpotifyController extends Controller
{
    public function authorizeApplication()
    {
        $scopes = [
            'user-read-currently-playing'
        ];

        $baseUrl = "https://accounts.spotify.com/authorize";
        $url = $baseUrl . '?' . http_build_query([
                'client_id' => config('services.spotify.client_id'),
                'grant_type' => 'authorization_code',
                'response_type' => 'code',
                'scope' => implode(' ', $scopes),
                'redirect_uri' => route('spotify.callback'),
            ]);

        return redirect()->away($url);
    }

    public function storeTokens(Request $request)
    {
        if ($request->error == 'access_denied') {
            abort('403', 'access_denied');
        }

        $clientId = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.secret');

        try {
            $response = Http::asForm()->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
            ])->post('https://accounts.spotify.com/api/token', [
                'client_id' => $clientId,
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'redirect_uri' => route('spotify.callback'),
            ]);
        } catch (RequestException $e) {
            abort($e->getCode(), $e->getMessage());
        }

        $body = json_decode((string) $response->getBody());

        Cache::put('spotifyAccessToken', $body->access_token);
        Cache::put('spotifyRefreshToken', $body->refresh_token);

        return 'You are authorized';
    }
}
