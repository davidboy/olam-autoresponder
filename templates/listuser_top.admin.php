<br/>
<form action="admin.php" method=POST>
    <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
    <input type="hidden" name="action" value="bulk_add">
    <input type="submit" name="Add a List" value="Add a List" class="butt">
</form>
<br/>

<center>
    <table border="0" width="750" cellpadding="0" cellspacing="0" style="border: 1px solid #000000;">
        <tr>
            <td>
                <table border="0" width="100%" cellpadding="0" cellspacing="2" class="header_color">
                    <tr>
                        <td width="50"><font color="#000033">ID #</font></td>
                        <td width="360"><font color="#000033">Email Address</font></td>
                        <td width="230"><font color="#000033">Responder Name</font></td>
                        <td width="45">&nbsp;</td>
                        <td width="45">&nbsp;</td>
                    </tr>
                </table>
