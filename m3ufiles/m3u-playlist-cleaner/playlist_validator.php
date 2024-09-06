<?php
include 'vendor/autoload.php';

use M3uParser\M3uParser;

$logFile = '/var/tmp/m3u/validator.log'; // Path to log file
function logEvent($message) {
    global $logFile;
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

$obj = new M3uParser();
$obj->addDefaultTags();
$files = glob('m3u/*.m3u');
$urlCache = []; // Define the cache outside of the function to persist it

$a = [];
foreach ($files as $file) {
    $data = $obj->parseFile($file);

    foreach ($data as $entry) {
        $suffix = '';
        $normalizedChannelName = normalizeChannelName($entry->getExtTags()[0]->getTitle());
        $normalizedChannelKey = normalizeChannelKey($normalizedChannelName);

        // check for duplicate channel names
        if (isset($a[$normalizedChannelKey]) && $a[$normalizedChannelKey]['path'] == $entry->getPath()) {
            logEvent("Skipping duplicate entry for '$normalizedChannelName'.");
            continue;
        }

        if (!validUrl($entry->getPath(), $urlCache)) {
            logEvent("'$normalizedChannelName' does not have a valid URL; skipping.");
            continue;
        }

        if (!validStream($entry->getPath())) {
            logEvent("'$normalizedChannelName' does not have a valid stream; skipping.");
            continue;
        }

        if ($suffix) {
            $entry->getExtTags()[0]->setTitle($normalizedChannelName . $suffix);
            $entry->getExtTags()[0]->setAttribute('tvg-name', $normalizedChannelName . $suffix);
        }

        $a[$normalizedChannelKey . $suffix] = ['entry' => $entry->__toString()];
    }
}

ksort($a);
writePlaylist($a, '/var/tmp/m3u/playlist.m3u'); // Specify the output filename

function normalizeChannelKey($channelName) {
    return strtolower(preg_replace('/[^\w]/', '', $channelName));
}

function normalizeChannelName($channelName) {
    return strlen(trim($channelName, ':')) ? trim($channelName) : 'Unknown';
}

function validUrl($url, &$cache) {
    if (!filter_var($url, FILTER_VALIDATE_URL)) return false;
    $components = parse_url($url);
    $cacheKey = $components['scheme'] . $components['host'] . @$components['port'];
    
    if (isset($cache[$cacheKey])) return $cache[$cacheKey];

    $live = pingPort($components['host'], $components['scheme'], @$components['port']);
    $cache[$cacheKey] = $live;
    return $live;
}

function validStream($url) {
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $section = file_get_contents($url, false, $context, 0, 1024);
    return strlen($section) === 1024;
}

function writePlaylist($array, $filename) {
    if (!count($array)) {
        logEvent("No channels available.");
        return;
    }
    $fp = fopen($filename, 'w');
    if ($fp === false) {
        logEvent("Failed to open file: $filename");
        return;
    }
    fwrite($fp, "#EXTM3U\n");
    foreach ($array as $channel) {
        fwrite($fp, $channel['entry'] . "\n");
    }
    fclose($fp);
    logEvent("Playlist written successfully to $filename");
}

function pingPort($host, $proto, $port = null) {
    if ($port === null) {
        $port = ($proto === 'https') ? 443 : (($proto === 'http') ? 80 : (($proto === 'rtmp') ? 1935 : (($proto === 'rtsp') ? 554 : null)));
    }
    $resource = @fsockopen($host, $port, $errno, $errstr, 5);
    $live = (bool)$resource;
    if ($resource) fclose($resource);
    return $live;
}
