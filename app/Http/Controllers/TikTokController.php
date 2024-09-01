<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TikTokController extends Controller
{
    private $client_id = 'aw2vl3o7qtxt5viw';
    private $client_secret = '5pe5gBBPUVJ62BF2uNdsx2AroHEwSi1g';
    private $redirect_uri = 'https://duckapp.rf.gd/';

    public function redirectToTikTok()
    {
        $url = "https://open-api.tiktok.com/platform/oauth/connect/?client_key={$this->client_id}&response_type=code&scope=user.info.basic,video.upload&redirect_uri={$this->redirect_uri}&state=your_state";

        return redirect($url);
    }

    public function handleTikTokCallback(Request $request)
    {
        $code = $request->query('code');

        $client = new Client();
        $response = $client->post('https://open-api.tiktok.com/oauth/access_token/', [
            'form_params' => [
                'client_key' => $this->client_id,
                'client_secret' => $this->client_secret,
                'code' => $code,
                'grant_type' => 'authorization_code',
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $access_token = $data['data']['access_token'];
        $open_id = $data['data']['open_id'];

        session(['tiktok_access_token' => $access_token, 'tiktok_open_id' => $open_id]);

        return redirect()->route('upload.form');
    }

    public function showUploadForm()
    {
        return view('tiktok.upload');
    }

    public function uploadVideo(Request $request)
    {
        $video = $request->file('video');
        $path = $video->getPathname();

        $client = new Client();
        $response = $client->post('https://open-api.tiktok.com/video/upload/', [
            'headers' => [
                'Authorization' => 'Bearer ' . session('tiktok_access_token'),
            ],
            'multipart' => [
                [
                    'name' => 'video',
                    'contents' => fopen($path, 'r'),
                    'filename' => $video->getClientOriginalName(),
                ],
                [
                    'name' => 'open_id',
                    'contents' => session('tiktok_open_id'),
                ],
                [
                    'name' => 'access_token',
                    'contents' => session('tiktok_access_token'),
                ],
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['data']['video_id'])) {
            return back()->with('success', 'Video uploaded successfully!');
        }

        return back()->with('error', 'Failed to upload video!');
    }
}
