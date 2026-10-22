# m3u-playlist-cleaner

**m3u-playlist-cleaner** combines `.m3u` and `.m3u8` playlists, checks channel URLs and stream availability, and writes the channels that pass validation to a cleaned playlist. Run it with Docker using your own playlist directory.

[![Typing SVG](https://readme-typing-svg.herokuapp.com?color=%2336BCF7&lines=Perfect+Your+IPTV+Experience!)](https://git.io/typing-svg)

## Features

- **Stream Validation**: Automatically checks each channel's stream availability.
- **Duplicate Removal**: Identifies and removes duplicate channel entries.
- **Logging**: Detailed logs of actions performed on the playlists, including which channels were removed.
- **Docker Integration**: Packaged into a Docker container for easy deployment and isolation.

## Latest update — October 22, 2026

- Fixed `.m3u8` playlist discovery; renaming files to `.m3u` is no longer needed ([issue #2](https://github.com/SeanRiggs/m3u-playlist-cleaner/issues/2)).
- Published the fix in `seanriggs/m3u-playlist-cleaner:latest` for AMD64, ARM64, and ARMv7.
- Added automated regression tests, including tests inside the Docker image.

If you already use the pre-built image, pull the update before running it again:

```bash
docker pull seanriggs/m3u-playlist-cleaner:latest
```

## What's New in Version 2

Version 2 brings several improvements and changes over version 1:

- **Improved URL Validation**: Version 2 includes enhanced URL and stream validation to ensure that only working channels are included in the playlist.
- **Better Logging**: The logging system now prints to both a log file and the terminal for better tracking of stream validation.
- **Configurable Options**: Version 2 allows for more configurable options through <i>environment variables</i> such as `LOG_FILE`, `OUTPUT_FILE`, and `M3U_DIRECTORY`.
- **Streamlined Duplicate Handling**: The new version improves the handling of duplicate channels in playlists, ensuring they are handled more efficiently.

For users still interested in version 1, you can switch to the `v1` branch using the following command:

```bash
git checkout v1
```

## Prerequisites

- Docker (No need to install PHP or Composer locally, as everything is handled within the Docker container)

## Architecture
Published Docker images support these Linux container platforms. On Windows or macOS, use Docker Desktop with Linux containers.

| Platform | Typical systems |
| --- | --- |
| `linux/amd64` | Intel/AMD PCs and servers |
| `linux/arm64` | 64-bit ARM systems |
| `linux/arm/v7` | 32-bit ARMv7 systems |

## Installation

Clone the repository to your local machine:

```bash
git clone https://github.com/SeanRiggs/m3u-playlist-cleaner.git
cd m3u-playlist-cleaner
```

## Build the Docker container:

```bash
docker build -t m3u-playlist-cleaner ./m3u-playlist-cleaner
```

### Usage
Run the Docker container:

```bash
docker run -ti --rm -v "/path/to/your/m3u/files:/var/tmp/m3u" m3u-playlist-cleaner
```
## Use the pre-built image

No repository clone or local build is required. Pull the latest image, then run it with your playlist directory mounted:

```bash
docker pull seanriggs/m3u-playlist-cleaner:latest
docker run --rm -v "/path/to/your/m3u/files:/var/tmp/m3u" seanriggs/m3u-playlist-cleaner:latest
```
- Replace `/path/to/your/m3u/files` with your host playlist directory. Files must have a lowercase `.m3u` or `.m3u8` extension; subdirectories are not searched.
- By default, `playlist.m3u` and `validator.log` are written to that same host directory. Use `OUTPUT_FILE` and `LOG_FILE` to customize their locations inside the container.
- The container runs once and exits after processing the playlists. Each run reads all matching input files, including a previous output playlist if it remains in the input directory.
- The working directory only matters when building from source. You can run the pre-built image from any directory.

**Execution and Removal**

When you run this command, Docker will:

- Start an instance of the <code>m3u-playlist-cleaner</code> container.
- Enable interaction with the container via the command line if necessary.
- Ensure files created or modified in the container's <code>/var/tmp/m3u</code> directory are saved to the corresponding host directory.
- Remove the container automatically once it stops running to free up resources without manual intervention.

### Logs

Logs are written to <code>/var/tmp/m3u/validator.log </code> inside the Docker container. 

**Additional Tips:**
- Monitor and manage the size of log files regularly to prevent them from consuming excessive disk space on the host.

**validator.log example:**
```
[2024-09-06 05:06:04] 'US: UP TV' does not have a valid stream; skipping.
[2024-09-06 05:06:16] 'US: Christian TV' does not have a valid stream; skipping.
[2024-09-06 05:06:41] 'USA  CINEMAX 5 STARMAX' does not have a valid stream; skipping.
[2024-09-06 05:06:46] 'USA  HBO EAST' does not have a valid stream; skipping.
[2024-09-06 05:07:02] 'USA  CINEMAX THRILLERMAX HD' does not have a valid stream; skipping.
[2024-09-06 05:07:04] 'USA  STARZ ENCORE BLACK FHD' does not have a valid stream; skipping.
[2024-09-06 05:07:10] 'USA  CINEMAX ACTION MAX' does not have a valid stream; skipping.
[2024-09-06 05:07:32] 'USA  CINEMAX EAST' does not have a valid stream; skipping.
[2024-09-06 05:07:54] 'USA  STARZ ENCORE CLASSIC' does not have a valid stream; skipping.
[2024-09-06 05:08:05] 'USA Showtime Next' does not have a valid stream; skipping.
[2024-09-06 05:08:26] Playlist written successfully to /var/tmp/m3u/playlist.m3u
```

### Configuration
Modify the playlist_validator.php script to customize the validation logic or to change the logging verbosity as per your requirements.

## Environment Variables

These are the defaults in the published Docker image. Environment-variable paths refer to locations **inside the container**; mount a host directory to retain the files.

| Variable | Docker default | Description |
| --- | --- | --- |
| `LOG_FILE` | `/var/tmp/m3u/validator.log` | Log file location. |
| `OUTPUT_FILE` | `/var/tmp/m3u/playlist.m3u` | Cleaned playlist location. |
| `M3U_DIRECTORY` | `/var/tmp/m3u` | Directory containing `.m3u` and `.m3u8` input files. |

When running PHP directly, the default input directory is `m3u`, relative to the working directory.

### Usage

For example, use separate input and output directories to avoid reading the generated playlist on later runs:

```bash
docker run --rm \
  -e M3U_DIRECTORY="/input" \
  -e OUTPUT_FILE="/output/playlist.m3u" \
  -e LOG_FILE="/output/validator.log" \
  -v "/path/to/your/playlists:/input:ro" \
  -v "/path/to/your/output:/output" \
  seanriggs/m3u-playlist-cleaner:latest
```

Create both host directories before running the command.

**Example**
If you want to change the log file and output file while using the default M3U directory, you can run:
```docker
docker run -it --rm \
  -e LOG_FILE="/var/tmp/m3u/custom.log" \
  -e OUTPUT_FILE="/var/tmp/m3u/custom_playlist.m3u" \
  -v "/path/to/your/local/m3ufiles:/var/tmp/m3u" \
  seanriggs/m3u-playlist-cleaner:latest
```

**Important Notes**
Ensure that the specified paths for the log file and output file are writable by the Docker container.
The application will use the above default values if you don't set these variables.

## Acknowledgments

This project uses the [M3uParser](https://github.com/Gemorroj/M3uParser) library, which is licensed under the GNU Lesser General Public License (LGPL) Version 3.

You can find the source code for M3uParser at the following link:  
[https://github.com/Gemorroj/M3uParser](https://github.com/Gemorroj/M3uParser).

This project includes a copy of the GNU Lesser General Public License Version 3 in compliance with the LGPL.  
Please refer to the license document or visit the [Free Software Foundation](https://www.gnu.org/licenses/lgpl-3.0.html) for more information.


### License

Distributed under the MIT License. See LICENSE for more information.



Project Link: https://github.com/SeanRiggs/m3u-playlist-cleaner
<br>
DockerHub Link: https://hub.docker.com/r/seanriggs/m3u-playlist-cleaner


## Regression tests

With PHP 8 CLI and Python 3 installed, run from the repository root:

```bash
php -l m3u-playlist-cleaner/playlist_validator.php
python3 -m unittest discover -s tests -v
```

The tests run the real validator and bundled parser against a local HTTP stream.
They cover each supported extension alone, mixed playlists, unrelated extensions,
and an empty directory. No external stream service is needed.

To check the Alpine Docker runtime as well, build from the application directory:

```bash
docker build -t m3u-playlist-cleaner:test m3u-playlist-cleaner
docker run --rm --entrypoint php m3u-playlist-cleaner:test -l /var/tmp/playlist_validator.php
```

The published `latest` image includes the `.m3u8` discovery fix. Use `docker pull seanriggs/m3u-playlist-cleaner:latest` to update a cached image, or rebuild from source using the command above.
