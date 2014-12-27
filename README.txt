---------
Intro
---------

OpenTorX is a open source content index website, freely available for anyone to 
use for personal or comercial use under the MIT license.

https://github.com/ajm113/opentorx

-------
Requirements
-------
Apache or a PHP supported web server.
Basic server maintenance and database knowledge.
PHP 4.5 =>
--------
Install
--------

These steps assume you know proper server configurations and or know how to setup a virtual server
in apache or other web servers for local machine access.

1. Clone / Extract source code to a web server directory.
2. Configure mysql settings in database.php located in. "/application/config/" and create a new database or use a already made one.

Change these lines to access the database.

$db['default']['username'] = 'yourusername';
$db['default']['password'] = 'yourpassword';
$db['default']['database'] = 'opentorx';  //What database you would like to use.

If you plan on using another database type. Please change this line:
$db['default']['dbdriver'] = 'mydatabasesystem';

Supported:  mysql, mysqli, postgre, odbc, mssql, sqlite, oci8

3. Import opentorx_data_dump.txt into your database. This file includes the table structure.

NOTE: This dump file is using Kickass torrent's partial dump.
If you plan on using a full dump or wish to use import another you may get them here:
https://kickass.so/api/

I also included a example of how to import the data dumps into your MySQL database in 
kickass_import.txt

4. Enjoy the experience!

--------
Troubleshooting:
--------

I'm getting errors/warnings from swiss_cache.php on some pages!
Ensure your cache folder has full permissions! Set folder /application/cache to 0777.

I can load the front page fine, but I get apache/web server 404 error on other pages!
If your running this on project on a sub folder. I.E /localhost/opentorx. You will
need to configure .htaccess for your server! Or simply create a virtual host!
... I will let you google how to do this.

How can I import x and y's database dump?
You will need to consult a online forum, or the database system your using to see how you
can do local file imports.

Can I use this for my website?
YES! Be sure to send me a link!

I still need help! :(
You can ask questions on the github page or contact me!

Whats your email?
andrewmcrobb at gmail d.o.t com

APC cache isn't working!
swiss_cache is still in alpha and I will be working on a fix soon!

How come share isn't working?
This feature is work in progress and hope to have this working in the next version.
