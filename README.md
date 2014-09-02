youtubefeed
===========

A PHP application that provides a (better) RSS feed for YouTube channels

#### Intention
I like to subscribe to my favorite YouTube channels via RSS.
YouTube provides a RSS feed for every channel (indeed not every, but for every with a "Username"), which is, however, really unreliable.

So this PHP application simply uses the YouTube Data API (v3) and generates a (better) RSS feed for channel uploads.

## Requirements
Any webserver with PHP

## Installation
Just unpack on your webserver subscribe to a channel like so:
`http://<your-install-path>/channel_uploads.php?name=https://www.youtube.com/<channel-name>`
or (for channels without a username)
`http://<your-install-path>/channel_uploads.php?id=https://www.youtube.com/channel/<channel-id>`

#### YouTube Data API Key
- Create a new Google API project: [https://console.developers.google.com](https://console.developers.google.com)
- Under "APIs & auth/APIs" enable "YouTube Data API v3"
- Under "APIs & auth/Credentials" create a new "Server key"
- Put that key in config.php

#### License
The MIT License (MIT)
