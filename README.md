# m3u-playlist-cleaner

The **m3u-playlist-cleaner** is a robust tool designed to validate and clean M3U playlists by verifying each channel's stream availability and removing any entries with invalid streams. Tailored for IPTV providers and users looking to maintain streamlined and efficient playlists, this cleaner ensures your playlists are up-to-date and free of dead links.

## Features

- **Stream Validation**: Automatically checks each channel's stream availability.
- **Duplicate Removal**: Identifies and removes duplicate channel entries.
- **Logging**: Detailed logs of actions performed on the playlists, including which channels were removed.
- **Docker Integration**: Packaged into a Docker container for easy deployment and isolation.

## Prerequisites

Before you begin, ensure you have the following installed on your system:
- Docker
- PHP 8.0 or higher
- Composer

## Installation

Clone the repository to your local machine:

```bash
git clone https://github.com/SeanRiggs/m3u-playlist-cleaner.git
cd m3u-playlist-cleaner
```

## Build the Docker container:

```bash
docker build -t m3u-playlist-cleaner .
``

### Usage
Run the Docker container:

```bash
docker run -ti --rm -v "/path/to/your/m3u/files:/var/tmp/m3u" m3u-playlist-cleaner
```

Make sure to replace "/path/to/your/m3u/files" with the path to the directory containing your M3U files.

### Configuration
Modify the playlist_validator.php script to customize the validation logic or to change the logging verbosity as per your requirements.

### Logs

Logs are written to /var/tmp/m3u/validator.log inside the Docker container. To keep logs on your host, mount a volume to this path when running the Docker container:

```bash
docker run -ti --rm -v "/path/to/your/m3u/files:/var/tmp/m3u" -v "/path/to/your/logs:/var/tmp/m3u/logs" m3u-playlist-cleaner
```
### License

Distributed under the MIT License. See LICENSE for more information.



Project Link: https://github.com/SeanRiggs/m3u-playlist-cleaner

