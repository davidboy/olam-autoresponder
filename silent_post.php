<?php
# Silent post code example for PHP

function silent_post($url, $port, $file, $var_array)
{
    # Clean up some possible problems
    $url = str_replace("https://", "", $url);
    $url = str_replace("http://", "", $url);

    # Set the post data
    $postdata = "";
    foreach ($var_array as $key => $value) {
        $postdata .= $key . "=" . urlencode($value) . "&";
    }
    $postdata = trim((trim($postdata)), "&");

    # Make the header
    $header = "POST $file HTTP/1.0\r\n";
    $header .= "Host: $url\r\n";
    $header .= "Connection: close\r\n";
    $header .= "Accept-Charset: UTF-8\r\n";
    $header .= "User-Agent: PHP/" . phpversion() . "\r\n";
    $header .= "Referer: " . $_SERVER['SERVER_SOFTWARE'] . "\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: " . strlen($postdata) . "\r\n";
    $header .= "\r\n";
    $header .= $postdata . "\r\n";

    # Connect
    $fp = fsockopen($url, $port, $errno, $errstr, 10);
    if (!$fp) {
        return NULL;
    } else {
        @fputs($fp, $header);
        $file_data = '';
        while (!feof($fp)) {
            $file_data .= @fgets($fp, 1024);
        }
        @fclose($fp);
        return $file_data;
    }
}

$vars['e'] = "their@address.com";
$vars['n'] = "Their Name";
$vars['r'] = "1";
$vars['a'] = "sub";
$vars['h'] = "1";
$vars['ref'] = "co-registration from xyz.com";
silent_post('http://yoursite.com', 80, '/subscribe.php', $vars);
?>
