<?php

if (empty($_GET['channel-name']) && empty($_GET['channel-id'])) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

require_once 'SimpleXMLElementEx.php';
require_once 'youtubefeed.php';

if (!empty($_GET['channel-name'])) {
    $channelName = urldecode($_GET['channel-name']);
    $channel = youtube_get_channel_by_name($channelName);
    $channelId = $channel['id'];
}

if (!empty($_GET['channel-id'])) {
    $channelId = urldecode($_GET['channel-id']);
    $channel = youtube_get_channel_by_id($channelId);
}

$channelName = $channel['snippet']['title'];
$uploadPlaylistId = $channel['contentDetails']['relatedPlaylists']['uploads'];
$uploads = youtube_get_uploads_for_playlist($uploadPlaylistId);

$feed = new SimpleXMLElementEx('<rss version="2.0"></rss>');
$feed->addChild('channel');
$feed->channel->addChild('title', 'YouTube uploads by ' . xml_entities($channelName));
$feed->channel->addChild('link', 'https://www.youtube.com/channel/' . $channelId . '/videos');
$imageItem = $feed->channel->addChild('image');
$imageItem->addChild('url', 'https://www.youtube.com/favicon.ico');

foreach ($uploads as $upload) {
    $uploadId = $upload['snippet']['resourceId']['videoId'];
    $video = youtube_get_video($uploadId);

    $durationIso8601 = new DateInterval($video['contentDetails']['duration']);
    $duration = $durationIso8601->format('%I:%S');

    $itemTitle = $upload['snippet']['title'] . ' [' . $duration . ']';
    $itemDate = date(DATE_RSS, strtotime($upload['snippet']['publishedAt']));
    $itemAuthor = $upload['snippet']['channelTitle'];
    $itemUrl = 'https://www.youtube.com/watch?v=' . $uploadId;

    $itemText = '<div>'
            . '<a href="' . $itemUrl . '"><img width=320" height="180" align="left" src="' . $upload['snippet']['thumbnails']['medium']['url'] . '" /></a>'
            . nl2br(htmlentities($upload['snippet']['description']))
            . '</div>';

    $item = $feed->channel->addChild('item');
    $item->addChild('title', xml_entities($itemTitle));
    $item->addChild('link', $itemUrl);
    $descriptionChild = $item->addChild('description');
    $descriptionChild->addCData($itemText);
    $item->addChild('pubDate', $itemDate);
    $item->addChild('author', $itemAuthor);
}

header('Content-Type: application/rss+xml');
echo $feed->asXML();
