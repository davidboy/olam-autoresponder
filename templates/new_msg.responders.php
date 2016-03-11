<?php
print "<table align=\"right\"><tr><td> \n";
print "<form action=\"messages.php\" method=POST> \n";
print "<input type=\"hidden\" name=\"action\" value=\"create\"> \n";
print "<input type=\"hidden\" name=\"r_ID\"   value=\"$Responder_ID\"> \n";
print "<input type=\"submit\" name=\"submit\" value=\"Add a New Message\"> \n";
print "</form> \n";
print "</td></tr></table> \n";
?>