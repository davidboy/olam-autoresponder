<?php
    include('common.php');
    $redir_URL = $siteURL.$ResponderDirectory.'/admin.php';
    header("Location: $redir_URL");
    print "<br>\n";
    print "Now loading the control panel...<br>\n";
    print "<br>\n";
    print "If your browser doesn't support redirects then you'll need to <A HREF=\"$redir_URL\">click here.</A><br>\n";
    print "<br>\n";
?>
