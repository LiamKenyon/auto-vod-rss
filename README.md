# Auto VOD via RSS
You'll need to create a download file named rss.php and place in any directory.

You'll also need to download the file named .htaccess in the same directory.

Now using this is SUPER simple. 
Simply replace the values in the $rss_array = array( section while keeping in mind the convention of encapsulating the array. 
Now to use this simply point your IPTV application to http://{DOMAIN}/{DIRECTORY}/RSS for the M3U file and http://{DOMAIN}/{DIRECTORY}/RSS/EPG for the EPG file. 
hat's it!
