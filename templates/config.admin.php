<?php
print "<br>\n";
print "<FORM action=\"admin.php\" method=POST> \n";
print "<input type=\"hidden\" name=\"action\" value=\"configure\"> \n";
print "<input type=\"hidden\" name=\"r_ID\"   value=\"$Responder_ID\"> \n";
print "<input type=\"submit\" name=\"submit\" value=\"Config\" alt=\"Config\">\n";
print "</FORM> \n";
?>