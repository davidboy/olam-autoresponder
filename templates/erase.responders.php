<?php
if ($DB_NotifyOnSub == 1) {
    $notify = "Yes";
} else {
    $notify = "No";
}
?>

<br/>
<center>
    <font size=5 color="#660000">-- Confirmation Required --</font>
    <br/>
    <table width="750" bgcolor="#CCCCCC" style="border: 1px solid #000000;">
        <tr>
            <td width="250"><font size=4 color="#000066">Responder Name: </font></td>
            <td width="450"><font size=4 color="#003300"><?php echo $DB_ResponderName; ?></font></td>
        </tr>
        <tr>
            <td width="250"><font size=4 color="#000066">Opt-In Level: </font><br></td>
            <td width="450"><font size=4 color="#003300"><?php echo $DB_OptMethod; ?></font><br></td>
        </tr>
        <tr>
            <td width="250"><font size=4 color="#000066">Notify Owner on Sub/Unsub: </font><br></td>
            <td width="450"><font size=4 color="#003300"><?php echo $notify; ?></font><br></td>
        </tr>
        <tr>
            <td width="250"><font size=4 color="#000066">Responder Desc: </font></td>
            <td width="450"><font size=4 color="#330033"><?php echo $DB_ResponderDesc; ?></font></td>
        </tr>
        <tr>
            <td width="250"><font size=4 color="#000066">Owner Name: </font></td>
            <td width="450"><font size=4 color="#003300"><?php echo $DB_OwnerName; ?></font></td>
        </tr>
        <tr>
            <td width="250"><font size=4 color="#000066">Owner Email: </font></td>
            <td width="450"><font size=4 color="#330033"><?php echo $DB_OwnerEmail; ?></font></td>
        </tr>
        <tr>
            <td width="250"><font size=4 color="#000066">Reply-To Email: </font><br></td>
            <td width="450"><font size=4 color="#003300"><?php echo $DB_ReplyToEmail; ?></font><br></td>
        </tr>
        <tr>
            <td width="250"><font size=4 color="#000066">Opt-In Redirect: </font><br></td>
            <td width="450"><font size=4 color="#003300"><?php echo $DB_OptInRedir; ?></font><br></td>
        </tr>
        <tr>
            <td width="250"><font size=4 color="#000066">Opt-Out Redirect: </font><br></td>
            <td width="450"><font size=4 color="#003300"><?php echo $DB_OptOutRedir; ?></font><br></td>
        </tr>
        <tr>
            <td width="250"><font size=4 color="#000066">Opt-In Conf Msg: </font><br></td>
            <td width="450"><font size=4 color="#003300"><?php echo $DB_OptInDisplay; ?></font><br></td>
        </tr>
        <tr>
            <td width="250"><font size=4 color="#000066">Opt-Out Conf Msg: </font><br></td>
            <td width="450"><font size=4 color="#003300"><?php echo $DB_OptOutDisplay; ?></font><br></td>
        </tr>
    </table>
    <table width="750" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td colspan="2">
                <br/>
                <center><font size=4 color="#660000">Delete this responder?</font></center>
            </td>
        </tr>
        <tr>
            <td>
                <table align="right" cellpadding="10" cellspacing="0">
                    <tr>
                        <td>
                            <FORM action="responders.php" method=POST>
                                <input type="hidden" name="action" value="do_erase">
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
                            <FORM action="responders.php" method=POST>
                                <input type="hidden" name="action" value="list">
                                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                                <input type="submit" name="No" value="No" class="butt">
                            </FORM>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</center>
