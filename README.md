# m3u-playlist-cleaner

The **m3u-playlist-cleaner** is a robust tool designed to validate and clean M3U playlists by verifying each channel's stream availability and removing any entries with invalid streams. Tailored for IPTV providers and users looking to maintain streamlined and efficient playlists, this cleaner ensures your playlists are up-to-date and free of dead links.

[![Typing SVG](https://readme-typing-svg.herokuapp.com?color=%2336BCF7&lines=Perfect+Your+IPTV+Experience!)](https://git.io/typing-svg)

## Features

- **Stream Validation**: Automatically checks each channel's stream availability.
- **Duplicate Removal**: Identifies and removes duplicate channel entries.
- **Logging**: Detailed logs of actions performed on the playlists, including which channels were removed.
- **Docker Integration**: Packaged into a Docker container for easy deployment and isolation.

## Prerequisites

- Docker (No need to install PHP or Composer locally, as everything is handled within the Docker container)

## Architecture
Container should run on any physical Linux machine (including RaspberryPi), Virtual Machine, or Windows 10 or newer (with docker desktop). They have been tested on Windows 10 Pro, Debian, and Ubuntu.

<table>
<tr>
<th>Architecture</th>
<th>Available</th>	
</tr>
<tr>
<td>amd64</td>
<td>✅</td>
</tr>
<tr>	
<td>arm64</td>
<td>✅</td>
</tr>
<td>arm/v7</td>
<td>✅	
</td>
</table>

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
## Use this Pre Built Image!

Don't want to mess with building the docker image yourself? We have already compiled a pre-built image for different architectures.<br><br>To run the `m3u-playlist-cleaner` using the pre-built Docker image, use the following command:

```bash
docker run -ti --rm -v "/path/to/your/m3u/files:/var/tmp/m3u" seanriggs/m3u-playlist-cleaner
```
- *Make sure* to replace "/path/to/your/m3u/files" with the path to the directory containing your M3U files. There is no requirement to name your m3u files; it only has a .m3u* extension. the output file is renamed to <code>playlist.m3u</code>. This can be modified in the playlist_validator.php file.
- *Make sure* you are running the docker command in the m3u-playlist-cleaner directory, as the required files for the container exist here. You can map the volume to your m3u files in a different location (and you should) or create a separate directory just for m3u files, which is helpful.
- **Logging**: Logging for the container is built in and will save an output log called </code>validator.log</code> in the same folder as your output file of the playlist.m3u that is generated. Logs contain channels that were removed during the parsing and why.

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
DockerHub Link: https://hub.docker.com/repository/docker/seanriggs/m3u-playlist-cleaner/general

