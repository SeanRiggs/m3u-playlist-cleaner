# m3u-playlist-cleaner

The **m3u-playlist-cleaner** is a robust tool designed to validate and clean M3U playlists by verifying each channel's stream availability and removing any entries with invalid streams. Tailored for IPTV providers and users looking to maintain streamlined and efficient playlists, this cleaner ensures your playlists are up-to-date and free of dead links.

[![Typing SVG](https://readme-typing-svg.herokuapp.com?color=%2336BCF7&lines=Perfect+Your+IPTV+Experience!)](https://git.io/typing-svg)

## Features

- **Stream Validation**: Automatically checks each channel's stream availability.
- **Duplicate Removal**: Identifies and removes duplicate channel entries.
- **Logging**: Detailed logs of actions performed on the playlists, including which channels were removed.
- **Docker Integration**: Packaged into a Docker container for easy deployment and isolation.

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

## Environment Variables

This application allows the use of several environment variables to customize its behavior. Below are the available environment variables along with their default values and descriptions:
```
| Variable                | Default Value                | Description                                                                                  |
|-------------------------|------------------------------|----------------------------------------------------------------------------------------------|
| `LOG_FILE`              | `/var/tmp/m3u/validator.log` | Specifies the path to the log file where validation logs will be written.                   |
| `OUTPUT_FILE`           | `/var/tmp/m3u/playlist.m3u`  | Defines the output file name for the generated playlist.                                    |
| `M3U_DIRECTORY`         | `m3u`                        | Sets the directory where M3U files are located. This directory should contain `.m3u` files. |
```

### Usage

You can set these environment variables in your terminal session before running the Docker container. For example:

```bash
docker run -it --rm \
  -e LOG_FILE="/path/to/your/logfile.log" \
  -e OUTPUT_FILE="/path/to/your/output/playlist.m3u" \
  -e M3U_DIRECTORY="/path/to/your/m3u/files" \
  -v "/path/to/your/local/m3ufiles:/var/tmp/m3u" \
  m3u-playlist-cleaner
```
**Example**
If you want to change the log file and output file while using the default M3U directory, you can run:
```docker
docker run -it --rm \
  -e LOG_FILE="/var/tmp/m3u/custom.log" \
  -e OUTPUT_FILE="/var/tmp/m3u/custom_playlist.m3u" \
  -v "/path/to/your/local/m3ufiles:/var/tmp/m3u" \
  m3u-playlist-cleaner
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

