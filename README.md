# m3u-playlist-cleaner

The **m3u-playlist-cleaner** is a robust tool designed to validate and clean M3U playlists by verifying each channel's stream availability and removing any entries with invalid streams. Tailored for IPTV providers and users looking to maintain streamlined and efficient playlists, this cleaner ensures your playlists are up-to-date and free of dead links.

## Features

- **Stream Validation**: Automatically checks each channel's stream availability.
- **Duplicate Removal**: Identifies and removes duplicate channel entries.
- **Logging**: Detailed logs of actions performed on the playlists, including which channels were removed.
- **Docker Integration**: Packaged into a Docker container for easy deployment and isolation.

## Prerequisites

- Docker (No need to install PHP or Composer locally, as everything is handled within the Docker container)

## Installation

Clone the repository to your local machine:

```bash
git clone https://github.com/SeanRiggs/m3u-playlist-cleaner.git
cd m3u-playlist-cleaner
```

## Build the Docker container:

```bash
docker build -t m3u-playlist-cleaner .
```

### Usage
Run the Docker container:

```bash
docker run -ti --rm -v "/path/to/your/m3u/files:/var/tmp/m3u" m3u-playlist-cleaner
```

- *Make sure* to replace "/path/to/your/m3u/files" with the path to the directory containing your M3U files.
- **Logging**: Most users will want to see logging to see differences in playlist and output for success and failures. If you want log output created, please run the docker command in the <a href="https://github.com/SeanRiggs/m3u-playlist-cleaner/edit/main/README.md#logs"><mark>log</mark></a> section below.

**Execution and Removal**

When you run this command, Docker will:

- Start an instance of the <code>m3u-playlist-cleaner</code> container.
- Enable interaction with the container via the command line if necessary.
- Ensure files created or modified in the container's <code>/var/tmp/m3u</code> directory are saved to the corresponding host directory.
- Remove the container automatically once it stops running to free up resources without manual intervention.

### Logs

Logs are written to <code>/var/tmp/m3u/validator.log </code> inside the Docker container. To keep logs on your host, mount a volume to this path when running the Docker container:

```bash
docker run -ti --rm -v "/path/to/your/m3u/files:/var/tmp/m3u" -v "/path/to/your/logs:/var/tmp/m3u" m3u-playlist-cleaner
```

**Additional Tips:**
- Ensure that the directory <code>/path/to/your/logs</code> on your host is correctly set up and has the appropriate permissions for Docker to write files.
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

## Acknowledgments

This project uses the [M3uParser](https://github.com/Gemorroj/M3uParser) library, which is licensed under the GNU Lesser General Public License (LGPL) Version 3.

You can find the source code for M3uParser at the following link:  
[https://github.com/Gemorroj/M3uParser](https://github.com/Gemorroj/M3uParser).

This project includes a copy of the GNU Lesser General Public License Version 3 in compliance with the LGPL.  
Please refer to the license document or visit the [Free Software Foundation](https://www.gnu.org/licenses/lgpl-3.0.html) for more information.


### License

Distributed under the MIT License. See LICENSE for more information.



Project Link: https://github.com/SeanRiggs/m3u-playlist-cleaner

