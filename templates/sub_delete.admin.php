<?php
if ($DB_Confirmed == "1") {
    $conf = "Yes";
} else {
    $conf = "No";
}
?>

<br>
<center>
    <font size=5 color="#660000">-- Confirmation Required --</font>
    <br>
    <table width="750" bgcolor="#CCCCCC" style="border: 1px solid #000000;">
        <tr>
            <td width="150"><font size=4 color="#000066">Subscriber ID: </font></td>
            <td width="550"><font size=4 color="#003300"><?php echo $DB_SubscriberID; ?></font></td>
        </tr>
        <tr>
            <td width="150"><font size=4 color="#000066">Subscribed To: </font></td>
            <td width="550"><font size=4 color="#330033"><?php echo $DB_ResponderName; ?></font></td>
        </tr>
        <tr>
            <td width="150"><font size=4 color="#000066">Email Address: </font></td>
            <td width="550"><font size=4 color="#330033"><?php echo $DB_EmailAddress; ?></font></td>
        </tr>
        <tr>
            <td width="150"><font size=4 color="#000066">Name: </font></td>
            <td width="550"><font size=4 color="#330033"><?php echo "$DB_FirstName $DB_LastName"; ?></font></td>
        </tr>
        <tr>
            <td width="150"><font size=4 color="#000066">IP address: </font></td>
            <td width="550"><font size=4 color="#330033"><?php echo $DB_IPaddy; ?></font></td>
        </tr>
        <tr>
            <td width="150"><font size=4 color="#000066">Confirmed: </font></td>
            <td width="550"><font size=4 color="#003300"><?php echo $conf; ?></font></td>
        </tr>
        <tr>
            <td width="150"><font size=4 color="#000066">Referral Source: </font></td>
            <td width="550"><font size=4 color="#003300"><?php echo $DB_ReferralSource; ?></font></td>
        </tr>
        <tr>
            <td width="150"><font size=4 color="#000066">Unique Code: </font></td>
            <td width="550"><font size=4 color="#003300"><?php echo $DB_UniqueCode; ?></font></td>
        </tr>
        <tr>
            <td width="150"><font size=4 color="#000066">HTML Email: </font></td>
            <td width="550"><font size=4 color="#003300"><?php echo $HTMLstr; ?></font></td>
        </tr>
        <tr>
            <td width="150"><font size=4 color="#000066">Joined: </font><br></td>
            <td width="550"><font size=4 color="#003300"><?php echo $JoinedStr; ?></font><br></td>
        </tr>
        <tr>
            <td width="150"><font size=4 color="#000066">Last Activity: </font><br></td>
            <td width="550"><font size=4 color="#003300"><?php echo $LastActStr; ?></font><br></td>
        </tr>
    </table>

    <table width="750" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td colspan="2">
                <br/>
                <center><font size=4 color="#660000">Delete this subscriber?</font></center>
            </td>
        </tr>
        <tr>
            <td>
                <table align="right" cellpadding="10" cellspacing="0">
                    <tr>
                        <td>
                            <FORM action="admin.php" method=POST>
                                <input type="hidden" name="action" value="sub_delete_do">
                                <input type="hidden" name="sub_ID" value="<?php echo $Subscriber_ID; ?>">
                                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                                <input type="submit" name="Yes" value="Yes" class="butt">
                            </FORM>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table align="left" cellpadding="10" cellspacing="0">
                    <tr>
                        <td>
                            <FORM action="admin.php" method=POST>
                                <input type="hidden" name="action" value="edit_users">
                                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                                <input type="submit" name="No" value="No" class="butt">
                            </FORM>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
