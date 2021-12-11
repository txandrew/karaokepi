# Karaoke-Pi
A fun karaoke system for parties that uses a Raspberry Pi to download, queue, and play videos.
## About
The Karaoke-Pi downloads youtube videos and stores them on a raspberry pi, which and can be played through a computer or through the rpi's browser on an HDMI screen. The Karaoke-Pi is accessed via mobile phones where each participant can queue videos, and the pi will play the videos in an alternating order.

## Installation
Before installing any applications, it is always best to update your raspberry pi's packages. For more details, take a look at the [Raspberry Pi: Install Apache + MySQL + PHP (LAMP Server)](https://randomnerdtutorials.com/raspberry-pi-apache-mysql-php-lamp-server/)

1. Update your repository
2. Install Appache Web Server
3. Install PHP Server-Side Scripting
4. Install MySQL Database
5. Setup Karaoke-Pi Application
6. **Optional** Install PHPMyAdmin

### Update the Repository
```
sudo apt-get update
sudo apt-get upgrade
```

### Appache Web Server

```
sudo apt install apache2 -y
```

### PHP Server
``` 
sudo apt install php -y
```

### MySQL Database
```
sudo apt install mariadb-server php-mysql -y
```

We need to setup a user account for the Karaoke Database. __It is recommended to change the password after IDENTIFIED BY,__ example below is karaoke
```
mysql -u root
GRANT ALL PRIVILEGES ON *.* TO 'kpi-server'@'localhost' IDENTIFIED BY 'karaoke';
exit
```

Next we are going to setup the Karaoke Database itself.
```
mysql -u kpi-server -p
CREATE DATABASE karaokepi;
exit
```

### Setup the PHP MySQL Library
```
sudo apt install php8.0-mysql -y
```

### Setup the Karaoke Application
Navigate to the www folder that you want to host your karaoke page from. After the installation of apache, the default web location is at /var/www/html. You may want to ```mkdir``` your own folder, if you have multiple programs on your server.
```
git clone https://github.com/txandrew/karaokepi.git
cd setup
mysql -u kpi-server -p karaoke < file.sql
```

Give ```www-data``` permissions to the videos folder.

```
sudo setfacl -m u:www-data:rwx karaokepi/videos/

sudo mkdir /var/karaoke
sudo mkdir /var/karaoke/youtube-dl
sudo setfacl -R -m u:www-data:rwx /var/karaoke
```

Create a ```db_init.php``` file that will store the password for you DB access
```php
<?php
$conn = new mysqli("localhost","userid","database","password");
?>
```

Another note, there are several folders, and if you change the path from ```/var/www/html/karaokepi```, you will need to search and replace those files

### Optional - phpMyAdmin

The phpMyAdmin module gives you a web based user interface to managing your database.
```
sudo apt install phpmyadmin -y
```

## Notes

If you are trying to access your karaokepi device, you will want to use a Dynamic DNS service if your ISP does not give you a static IP; however, note that many ISPs block port 80 (HTML). You will need to use an alternate port, and setup your router to change it to port 80.

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
