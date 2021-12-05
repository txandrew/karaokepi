# Karaoke-Pi
A fun karaoke system for parties that uses a Raspberry Pi to download, queue, and play videos.
## About
The Karaoke-Pi downloads youtube videos and stores them on a raspberry pi, which and can be played via an HDMI port. The Karaoke-Pi is accessed via mobile phones where each participant can queue videos, and the pi will play the videos in an alternating order. A display is created for a raspberry pi 7.5 inch touch screen.
## Requirements
1. Appache Web Server
2. PHP Server
3. MySQL Database
4. OMX Player
## To Do
The following is a list of improvements/bugs that need to be applied.
1. Add Delay Screens 
2. Add Player as a Service
3. Add PIP Installer

# PHP API
## add.php

add.php will download youtube videos and save them into the database. 

Parameters
- youtube_url - [POST] - The youtube URL of the file to download
- youtube_id - [SESSION] - The youtube ID to download and save the file

## delete.php

delete.php will delete files from the database, and will delete the records from the database.

Parameters
- ytid - [GET] - the id of the video in the karaokepi database