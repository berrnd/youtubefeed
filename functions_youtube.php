<?php

require_once 'config.php';

function youtube_get_channel_by_name($channelName) {
    $url = 'https://www.googleapis.com/youtube/v3/channels?part=id,snippet,contentDetails&forUsername=' . $channelName . '&key=' . YOUTUBE_API_KEY;
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data['items'][0];
}

function youtube_get_channel_by_id($channelId) {
    $url = 'https://www.googleapis.com/youtube/v3/channels?part=id,snippet,contentDetails&id=' . $channelId . '&key=' . YOUTUBE_API_KEY;
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data['items'][0];
}

function youtube_get_uploads_for_playlist($playlistId) {
    $url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=' . $playlistId . '&order=date&maxResults=10&key=' . YOUTUBE_API_KEY;
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data['items'];
}
