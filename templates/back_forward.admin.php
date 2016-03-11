<?php
      print "<br><table border=\"0\" cellspacing=\"0\" width=\"100%\">\n";
      print "<tr>\n";
      print "<td width=\"50%\"> \n";
      if ($SearchCount > 0) {
        print "<p align=\"left\">\n";
        print "<form action=\"admin.php\" method=POST>\n";
        print "<input type=\"hidden\" name=\"action\" value=\"edit_users\">\n";
        print "<input type=\"hidden\" name=\"r_ID\" value=\"$Responder_ID\">\n";
        print "<input type=\"hidden\" name=\"Search_Count\" value=\"$Search_Count_BackStr\">\n";
        print "<input type=\"submit\" name=\"Previous\" value=\"<< Previous Page\">\n";
        print "</form>\n";
        print "</p>\n";
      }
      print "</td>\n";
      print "<td align=\"right\" width=\"50%\">\n";
      if (($SearchCount + ($DB_search_result->num_rows - 1)) < $Max_Results_Count) {
        print "<p align=\"right\">\n";
        print "<form action=\"admin.php\" method=POST>\n";
        print "<input type=\"hidden\" name=\"action\" value=\"edit_users\">\n";
        print "<input type=\"hidden\" name=\"r_ID\" value=\"$Responder_ID\">\n";
        print "<input type=\"hidden\" name=\"Search_Count\" value=\"$Search_Count_ForwardStr\">\n";
        print "<input type=\"submit\" name=\"Next\" value=\"Next Page >>\">\n";
        print "</form>\n";
        print "</p>\n";
      }
      print "</td> \n";
      print "</tr> \n";
      print "</table> \n";
?>