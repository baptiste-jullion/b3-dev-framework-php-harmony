<?php

namespace App\Service;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class SpotifyService
{
    private Client $client;
    private string $clientId;
    private string $clientSecret;

    public function __construct(ParameterBagInterface $params)
    {
        $this->clientId = $params->get('spotify_client_id');
        $this->clientSecret = $params->get('spotify_client_secret');

        $this->client = new Client([
                                       'base_uri' => 'https://api.spotify.com/v1/',
                                       'timeout' => 5.0,
                                   ]);
    }

    public function getArtist(string $artistName): ?array
    {
        $accessToken = $this->getAccessToken();

        try {
            $response = $this->client->get('search', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
                'query' => [
                    'q' => $artistName,
                    'type' => 'artist',
                    'limit' => 1,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['artists']['items'][0])) {
                return $data['artists']['items'][0];
            }

            return null;
        } catch (GuzzleException $e) {
            // Log the error
            error_log('Spotify API error: ' . $e->getMessage());

            return null;
        }
    }

    private function getAccessToken(): string
    {
        $response = $this->client->post('https://accounts.spotify.com/api/token', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data['access_token'];
    }
}