<?php

if (empty($_GET['name']) && empty($_GET['id'])) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

require_once 'SimpleXMLElementEx.php';
require_once 'functions_youtube.php';

if (!empty($_GET['name'])) {
    $channelName = urldecode($_GET['name']);
    $channel = youtube_get_channel_by_name($channelName);
    $channelId = $channel['id'];
}

if (!empty($_GET['id'])) {
    $channelId = urldecode($_GET['id']);
    $channel = youtube_get_channel_by_id($channelId);
}

$channelName = $channel['snippet']['title'];
$uploadPlaylistId = $channel['contentDetails']['relatedPlaylists']['uploads'];
$uploads = youtube_get_uploads_for_playlist($uploadPlaylistId);

$feed = new SimpleXMLElementEx('<rss version="2.0"></rss>');
$feed->addChild('channel');
$feed->channel->addChild('title', 'YouTube uploads by ' . $channelName);
$feed->channel->addChild('link', 'https://www.youtube.com/channel/' . $channelId . '/videos');
$imageItem = $feed->channel->addChild('image');
$imageItem->addChild('url', 'https://www.youtube.com/favicon.ico');

foreach ($uploads as $upload) {
    $itemTitle = $upload['snippet']['title'];
    $itemDate = date(DATE_RSS, strtotime($upload['snippet']['publishedAt']));
    $itemAuthor = $upload['snippet']['channelTitle'];

    $uploadId = $upload['snippet']['resourceId']['videoId'];
    $itemUrl = 'https://www.youtube.com/watch?v=' . $uploadId;

    $itemText = '<table><tr><td><a href="' . $itemUrl . '"><img src="'
            . $upload['snippet']['thumbnails']['high']['url']
            . '" /></a></td><td>'
            . htmlentities($upload['snippet']['description'])
            . '</td></tr></table>';

    $item = $feed->channel->addChild('item');
    $item->addChild('title', $itemTitle);
    $item->addChild('link', $itemUrl);
    $descriptionChild = $item->addChild('description');
    $descriptionChild->addCData($itemText);
    $item->addChild('pubDate', $itemDate);
    $item->addChild('author', $itemAuthor);
}

header('Content-Type: application/rss+xml');
echo $feed->asXML();
