# Intro

OpenTorX is a open source content index website, freely available for anyone to 
use for personal or comercial use under the MIT license.

![preview](https://raw.githubusercontent.com/ajm113/Open-Tor-X/master/preview.png)

I'll be setting up a demo sometime soon.


# Requirements

Apache/Nginx or a PHP supported web server.
Basic server maintenance and database knowledge.
PHP 4.5 =>

# Install

These steps assume you know proper server configurations and or know how to setup a virtual server
in apache or other web servers for local machine access.

- Clone / Extract source code to a web server directory.
- Configure mysql settings in database.php located in. "/application/config/" and create a new database or use a already made one.

Change these lines to access the database.

```
$db['default']['username'] = 'yourusername';
$db['default']['password'] = 'yourpassword';
$db['default']['database'] = 'opentorx';  //What database you would like to use.
```

If you plan on using another database type. Please change this line:
$db['default']['dbdriver'] = 'mydatabasesystem';

Supported:  mysql, mysqli, postgre, odbc, mssql, sqlite, oci8

- Import opentorx_data_dump.sql into your database. This file includes the table structure.

NOTE: This dump file is using KAT partial dump. (When it was online at the time.)

- Enjoy the experience!

# Troubleshooting:

*How come the torrent download links are broken?*

The site hosting the torrents has went down. For now the already available dump will be a placeholder until you get some real data.

*I can load the front page fine, but I get apache/web server 404 error on other pages!*

If your running this on project on a sub folder. I.E /localhost/opentorx. You will
need to configure .htaccess for your server! Or simply create a virtual host!
... I will let you google how to do this.

*How can I import x and y's database dump?*

You will need to consult a online forum, or the database system your using to see how you
can do local file imports of the data dump.

*Can I use this for my website?*

YES! Be sure to send me a link!

*I still need help! :(*

You can ask questions on the github issue page or contact me!

*How come share isn't available?*

This feature is going to be depricated and removed! (Also the site torcache site is gone.)