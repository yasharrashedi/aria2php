# Aria2 PHP Client

This is a Aria2 PHP client ideal for use on a computer (like Raspberry Pi) with web server that's always on and connected to the internet, Just forward 80 port to local IP on your router to access it from a remote location.

there are other aria2 GUIs like https://github.com/ziahamza/webui-aria2 but to use them you must use them in your local network or open and forward all aria2 ports on your router/firewall! 

Inside the project files there is a RPI_CONFIG directory that contains sample aria2 daemon and aria2.conf files. this directory is not needed by the script and is just to configure the aria2.

This is a good idead to use no-ip.com linux software if you don't have a static IP.

set username and password (needed for login) in config.php file

![Screentshot](https://raw.githubusercontent.com/yasharrashedi/aria2php/master/screenshot.png "Screentshot")



known issues
===============================================
Save Session doesn't work although it uses commands described in aria2 documentation. (aria2.saveSession)


License
===============================================
MIT License. Refer to the LICENSE file.