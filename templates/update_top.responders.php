<?php
      if ($DB_OptMethod == "Single") {
           $opt_1 = "CHECKED";
           $opt_2 = "";
      }
      else {
           $opt_1 = "";
           $opt_2 = "CHECKED";
      }
      include_once('popup_js.php');
?>

<table border="0" cellpadding="5">
   <tr>
      <td width="700">
         <A HREF="#responder_msgs"><< Zoom down to the responder's messages >></A>
      </td>
      <td>
         <a href="manual.html#editresps2" onclick="return popper('manual.html#editresps2')">Help</a>
      </td>
   </tr>
</table>

<?php
      print "<br> \n";
      print "<center> \n";
      print "<table width=\"700\"><tr><td> \n";
      print "<table width=\"100%\" bgcolor=\"#660000\" style=\"border: 1px solid #000000;\"><tr><td> \n";
      print "<p class=\"white_header\">-- Edit Responder --</p>\n";
      print "</td></tr></table> \n";
      print "<center> \n";
      print "<table width=\"100%\" bgcolor=\"#CCCCCC\" style=\"border: 1px solid #000000;\"><tr><td> \n";
      print "<center> \n";
      print "<table border=\"0\"> \n";
      print "<tr><td width=\"200\"> \n";
      print "<FORM action=\"responders.php\" method=POST> \n";
      print "<strong><br>Responder Name:</strong></td> \n";
      print "<td><input name=Resp_Name size=63 maxlength=250 class=\"fields\" value=\"$DB_ResponderName\"></td>\n";
      print "</tr> \n";
      print "<tr><td width=\"200\"><strong>Opt-In Level:</strong></td> \n";
      print "<td>\n";
      print "<input type=\"radio\" name=\"OptMethod\" value=\"Single\" $opt_1>Single \n";
      print "<input type=\"radio\" name=\"OptMethod\" value=\"Double\" $opt_2>Double\n";
      print "</td>\n";
      print "</tr>\n";
      print "<tr><td width=\"200\"><strong>Notify Owner on Sub/Unsub:</strong></td> \n";
      print "<td>\n";
      if ($DB_NotifyOnSub == 1) {
         print "    <input type=\"RADIO\" name=\"NotifyOwner\" value=\"1\" checked>Yes \n";
         print "    <input type=\"RADIO\" name=\"NotifyOwner\" value=\"0\">No\n";
      } else {
         print "    <input type=\"RADIO\" name=\"NotifyOwner\" value=\"1\">Yes \n";
         print "    <input type=\"RADIO\" name=\"NotifyOwner\" value=\"0\" checked>No\n";
      }
      print "</td>\n";
      print "</tr>\n";
      print "<tr><td width=\"200\"><strong>Owner Name:</strong></td> \n";
      print "<td><input name=Owner_Name size=63 maxlength=97 value=\"$DB_OwnerName\" class=\"fields\"></td>\n";
      print "</tr> \n";
      print "<tr><td width=\"200\"><strong>Owner Email:</strong></td>\n";
      print "<td><input name=Owner_Email size=63 maxlength=100 value=\"$DB_OwnerEmail\" class=\"fields\"></td>\n";
      print "</tr> \n";
      print "<tr><td width=\"200\"><strong>Reply-to Email:</strong></td> \n";
      print "<td><input name=Reply_To size=63 maxlength=100 value=\"$DB_ReplyToEmail\" class=\"fields\"></td> \n";
      print "</tr> \n";
      print "<tr> \n";
      print "<td colspan=\"2\"> \n";
      print "<br> \n";
      print "<strong>Responder Description:</strong> --- <em>[Note: Supports HTML -- Be careful!]</em><br> \n";
      print "<textarea name=\"Resp_Desc\" rows=14 cols=90 class=\"html_area\">$DB_ResponderDesc</textarea> \n";
      print "<br><br></td> \n";
      print "</tr> \n";
      print "<tr><td width=\"200\"><strong>Opt-In Redirect URL:</strong></td> \n";
      print "<td><input name=OptInRedir size=63 maxlength=100 value=\"$DB_OptInRedir\" class=\"fields\"></td>\n";
      print "</tr> \n";
      print "<tr><td width=\"200\"><strong>Opt-Out Redirect URL:</strong></td> \n";
      print "<td><input name=OptOutRedir size=63 maxlength=100 value=\"$DB_OptOutRedir\" class=\"fields\"></td>\n";
      print "</tr> \n";
      print "<tr> \n";
      print "<td colspan=\"2\"> \n";
      print "<br> \n";
      print "<strong>Opt-In Confirmation Page:</strong> --- <em>[Note: Supports HTML -- Be careful!]</em><br> \n";
      print "<textarea name=\"OptInDisplay\" rows=14 cols=90 class=\"html_area\">$DB_OptInDisplay</textarea> \n";
      print "</td> \n";
      print "</tr> \n";
      print "<tr> \n";
      print "<td colspan=\"2\"> \n";
      print "<br> \n";
      print "<strong>Opt-Out Confirmation Page:</strong> --- <em>[Note: Supports HTML -- Be careful!]</em><br> \n";
      print "<textarea name=\"OptOutDisplay\" rows=14 cols=90 class=\"html_area\">$DB_OptOutDisplay</textarea> \n";
      print "</td> \n";
      print "</tr> \n";
      print "<tr> \n";
      print "<td colspan=\"2\">\n";
      print "<input type=\"hidden\" name=\"action\" value=\"do_update\"> \n";
      print "<input type=\"hidden\" name=\"r_ID\"   value=\"$Responder_ID\"> \n";
      print "<p align=\"right\"> \n";
      print "<input type=\"submit\" name=\"Save\" value=\"Save\" alt=\"Save\" class=\"save_b\">  \n";
      print "</p> \n";
      print "</td> \n";
      print "</tr> \n";
      print "</td></tr></table> \n";
      print "</center> \n";
      print "</FORM> \n";
      print "</td></tr></table> \n";
      print "</center> \n";
      print "<br> \n";
      print "</td></tr></table> \n";
      print "<table width=\"100%\"><tr> \n";
      print "<td width=\"200\"> \n";
      print "<FORM action=\"responders.php\" method=POST> \n";
      print "<input type=\"hidden\" name=\"action\" value=\"list\"> \n";
      print "<input type=\"submit\" name=\"back\"   value=\"<< Back\" alt=\"<< Back\" class=\"b_b\">  \n";
      print "</FORM> \n";
      print "</td> \n";
      print "<td width=\"200\">\n";
      print "<FORM action=\"responders.php\" method=POST>\n";
      print "<input type=\"hidden\" name=\"action\"     value=\"custom_stuff\">\n";
      print "<input type=\"hidden\" name=\"r_ID\"       value=\"$Responder_ID\">\n";
      print "<input type=\"submit\" name=\"submit\"     value=\"All custom data\">\n";
      print "</FORM>\n";
      print "</td>\n";
      print "<td width=\"80\"> \n";
      print "<FORM action=\"responders.php\" method=POST> \n";
      print "<input type=\"hidden\" name=\"action\" value=\"POP3\"> \n";
      print "<input type=\"hidden\" name=\"r_ID\"   value=\"$Responder_ID\"> \n";
      print "<input type=\"submit\" name=\"POP3\" value=\"POP3\" alt=\"POP3\"> \n";
      print "</FORM> \n";
      print "</td> \n";
      print "</tr></table> \n";
      print "</center> \n";
?>
