# youtube-upload-mail-reminder

Until Youtube has disabled their mail notification system, i've written this simple mail reminder script.
The script uses the XML feed from a channel, to check if a new video has been uploaded.

Requirements on Server/Webspace:

- Webserver like Apache2, nginx eg. with PHP
- SQL database (with activated mysqli module)
- Mail Transfer Agent (like ssmtp or sendmail)
- Cron

Installation

- Create a SQL user and a database
- Import the SQL dump into database
- Write the SQL connection details in file config.php
- Write receiver and sender mail adress in config.php
- Upload all files to server
- Open index.php to see the interface where you can add and delete favorite channels
- Call with a cron the file feed_parser.php may every 10 minutes

# How to get the Channel ID?

- Login into your Youtube account
- Go with the mouse pointer to your subscriptions
- As example: The channel from Space (Official) has the link https://www.youtube.com/channel/UCIR_LPmEQ9QHR0yB2lxgaxQ
- Copy the link somewhere and cut the part after channel/

<code>UCIR_LPmEQ9QHR0yB2lxgaxQ</code>

This mix of signs and numbers is the channel ID, which is required to paste in textfield 'Channel ID' on index.php file.

![Screenshot](screenshot.png)

---

Take note: On the first run of the script (feed_parser.php), a mail with many content will send.

After that only new videos was displayed in the notification mail.



Tags: Youtube, Upload, Mail, Notification

