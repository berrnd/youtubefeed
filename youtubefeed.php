<?php

require_once 'config.php';
require_once 'packages/PHP-SimpleCache/SimpleCache.php';

function youtube_get_channel_by_name($channelName) {
    $cache = new Gilbitron\Util\SimpleCache();
    $cache->cache_time = 604800; //1 week

    $url = 'https://www.googleapis.com/youtube/v3/channels?part=id,snippet,contentDetails&forUsername=' . $channelName . '&key=' . YOUTUBE_API_KEY;
    $response = $cache->get_data("youtube_get_channel_by_name_$channelName", $url);
    $data = json_decode($response, true);
    return $data['items'][0];
}

function youtube_get_channel_by_id($channelId) {
    $cache = new Gilbitron\Util\SimpleCache();
    $cache->cache_time = 604800; //1 week

    $url = 'https://www.googleapis.com/youtube/v3/channels?part=id,snippet,contentDetails&id=' . $channelId . '&key=' . YOUTUBE_API_KEY;
    $response = $cache->get_data("youtube_get_channel_by_id_$channelId", $url);
    $data = json_decode($response, true);
    return $data['items'][0];
}

function youtube_get_uploads_for_playlist($playlistId) {
    $url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=' . $playlistId . '&order=date&maxResults=10&key=' . YOUTUBE_API_KEY;
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data['items'];
}

function xml_entities($string) {
    return str_replace(
            array("&", "<", ">", '"', "'"), array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"), $string
    );
}
