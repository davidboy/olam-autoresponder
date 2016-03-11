<?php
include_once('popup_js.php');
?>

    <table cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td width="690">
                <FORM action="admin.php" method=POST>
                    <input type="hidden" name="action" value="custom_codeit">
                    <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                    <input type="submit" name="submit" value="Custom Code-it!">
                </FORM>
            </td>
            <td>
                <a href="manual.html#codeit" onclick="return popper('manual.html#codeit')">Help</a>
            </td>
        </tr>
    </table>
    <br/>

<?php
print "<br><font size=\"4\" color=\"#003300\">Here is your subscription form for that responder ID:</font><br><br>\n";
print "<center>\n";
print "<table cellspacing=\"10\" bgcolor=\"#CCCCCC\" style=\"border: 1px solid #000000;\"><tr><td>\n";
print "<form action=\"$siteURL$ResponderDirectory/subscribe.php\" method=GET>\n";
print "<strong><font color=\"#660000\">Your name (First, Last):</font></strong><br>\n";
print "<input type=\"text\" name=\"f\" style=\"background-color : #FFFFFF\" size=11 maxlength=40> \n";
print "<input type=\"text\" name=\"l\" style=\"background-color : #FFFFFF\" size=11 maxlength=40>\n";
print "<br><br>\n";
print "<strong><font color=\"#000066\">Email address:</font></strong><br>\n";
print "<input type=\"text\" name=\"e\" style=\"background-color : #FFFFFF\" size=20 maxlength=50>\n";
print "<input type=\"image\" src=\"$siteURL$ResponderDirectory/images/go-button.gif\" name=\"submit\" value=\"Submit\"><br>\n";
print "<input type=\"hidden\" name=\"r\"   value=\"$Responder_ID\">\n";
print "<input type=\"hidden\" name=\"a\"   value=\"sub\">\n";
print "<input type=\"hidden\" name=\"ref\" value=\"none\">\n";
print "<br>\n";
print "<font color=\"#003300\">HTML: <input type=\"RADIO\" name=\"h\" value=\"1\">Yes &nbsp;\n";
print "<input type=\"RADIO\" name=\"h\" value=\"0\" checked=\"checked\">No<br>\n";
print "</font></form>\n";
print "</td></tr></table>\n";
print "</center>\n";

print "<br><hr style = \"border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;\"><br>\n";
print "<font size=\"4\" color=\"#660066\">Or copy and paste this code into your page:</font><br><br>\n";

print "&lt;center&gt;<br>\n";
print "&lt;table cellspacing=\"10\" bgcolor=\"#CCCCCC\" style=\"border: 1px solid #000000;\"&gt;&lt;tr&gt;&lt;td&gt;<br>\n";
print "&lt;form action=\"$siteURL$ResponderDirectory/subscribe.php\" method=GET&gt;<br>\n";
print "&lt;strong&gt;&lt;font color=\"#660000\"&gt;Your name (First, Last):&lt;/font&gt;&lt;/strong&gt;&lt;br&gt;<br>\n";
print "&lt;input type=\"text\" name=\"f\" style=\"background-color : #FFFFFF\" size=11 maxlength=40&gt; <br>\n";
print "&lt;input type=\"text\" name=\"l\" style=\"background-color : #FFFFFF\" size=11 maxlength=40&gt;<br>\n";
print "&lt;br&gt;&lt;br&gt;<br>\n";
print "&lt;strong&gt;&lt;font color=\"#000066\"&gt;Email address:&lt;/font&gt;&lt;/strong&gt;&lt;br&gt;<br>\n";
print "&lt;input type=\"text\" name=\"e\" style=\"background-color : #FFFFFF\" size=20 maxlength=50&gt;<br>\n";
print "&lt;input type=\"image\" src=\"$siteURL$ResponderDirectory/images/go-button.gif\" name=\"submit\" value=\"Submit\"&gt;&lt;br&gt;<br>\n";
print "&lt;input type=\"hidden\" name=\"r\"   value=\"$Responder_ID\"&gt;<br>\n";
print "&lt;input type=\"hidden\" name=\"a\"   value=\"sub\"&gt;<br>\n";
print "&lt;input type=\"hidden\" name=\"ref\" value=\"none\"&gt;<br>\n";
print "&lt;br&gt;<br>\n";
print "&lt;font color=\"#003300\"&gt;HTML: &lt;input type=\"RADIO\" name=\"h\" value=\"1\"&gt;Yes &nbsp;<br>\n";
print "&lt;input type=\"RADIO\" name=\"h\" value=\"0\" checked=\"checked\"&gt;No&lt;br&gt;<br>\n";
print "&lt;/font&gt;&lt;/form&gt;<br>\n";
print "&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;<br>\n";
print "&lt;/center&gt;<br>\n";
?>