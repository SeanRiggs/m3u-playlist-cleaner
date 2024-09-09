<?php
include 'vendor/autoload.php';

use M3uParser\M3uParser;

// Configuration
$config = [
    'logFile' => getenv('LOG_FILE') ?: '/var/tmp/m3u/validator.log',
    'outputFile' => getenv('OUTPUT_FILE') ?: '/var/tmp/m3u/playlist.m3u',
    'm3uDirectory' => getenv('M3U_DIRECTORY') ?: 'm3u',
];

$logFile = $config['logFile']; // Path to log file

function logEvent($message) {
    global $logFile;
    $logMessage = date('[Y-m-d H:i:s] ') . $message . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
    echo $logMessage; // Output to terminal
}

$obj = new M3uParser();
$obj->addDefaultTags();
$files = glob($config['m3uDirectory'] . '/*.m3u');
$urlCache = []; // Define the cache outside of the function to persist it

$a = [];
foreach ($files as $file) {
    try {
        $data = $obj->parseFile($file);

        foreach ($data as $entry) {
            $suffix = '';
            $normalizedChannelName = normalizeChannelName($entry->getExtTags()[0]->getTitle());
            $normalizedChannelKey = normalizeChannelKey($normalizedChannelName);

            if (isset($a[$normalizedChannelKey]) && $a[$normalizedChannelKey]['path'] == $entry->getPath()) {
                logEvent("Skipping duplicate entry for '$normalizedChannelName'.");
                continue;
            }

            try {
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
            } catch (Exception $e) {
                logEvent("Error processing entry for '$normalizedChannelName': " . $e->getMessage());
            }
        }
    } catch (Exception $e) {
        logEvent("Error parsing file '$file': " . $e->getMessage());
    }
}

ksort($a);
writePlaylist($a, $config['outputFile']); // Specify the output filename

function normalizeChannelKey($channelName) {
    return strtolower(preg_replace('/[^\w]/', '', $channelName));
}

function normalizeChannelName($channelName) {
    return strlen(trim($channelName, ':')) ? trim($channelName) : 'Unknown';
}

function validUrl($url, &$cache) {
    try {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            logEvent("Invalid URL format: $url");
            return false;
        }
        $components = parse_url($url);
        $cacheKey = $components['scheme'] . $components['host'] . @$components['port'];
        
        if (isset($cache[$cacheKey])) return $cache[$cacheKey];

        $live = pingPort($components['host'], $components['scheme'], @$components['port']);
        $cache[$cacheKey] = $live;
        return $live;
    } catch (Exception $e) {
        logEvent("Error validating URL '$url': " . $e->getMessage());
        return false;
    }
}

function validStream($url) {
    try {
        $context = stream_context_create(['http' => ['timeout' => 5]]);
        $section = @file_get_contents($url, false, $context, 0, 1024);
        if ($section === false) {
            logEvent("Stream validation failed, unable to read: $url");
            return false;
        }
        return strlen($section) === 1024;
    } catch (Exception $e) {
        logEvent("Error validating stream '$url': " . $e->getMessage());
        return false;
    }
}

function writePlaylist($array, $filename) {
    if (!count($array)) {
        logEvent("No channels available to write to the playlist.");
        return;
    }
    $fp = fopen($filename, 'w');
    if ($fp === false) {
        logEvent("Failed to open file: $filename");
        return;
    }
    fwrite($fp, "#EXTM3U\n");
    foreach ($array as $channel) {
        if (fwrite($fp, $channel['entry'] . "\n") === false) {
            logEvent("Failed to write channel data to file: $filename");
            break;
        }
    }
    if (!fclose($fp)) {
        logEvent("Failed to close file after writing: $filename");
    } else {
        logEvent("Playlist written successfully to $filename");
    }
}

function pingPort($host, $proto, $port = null) {
    if ($port === null) {
        $port = ($proto === 'https') ? 443 : (($proto === 'http') ? 80 : (($proto === 'rtmp') ? 1935 : (($proto === 'rtsp') ? 554 : null)));
    }
    $errno = 0;
    $errstr = '';
    $resource = @fsockopen($host, $port, $errno, $errstr, 5);
    if ($resource === false) {
        logEvent("Failed to ping port: $errstr ($errno)");
        return false;
    }
    fclose($resource);
    return true;
}
