<?php
if ($alt) {
    $css_class = "row_color_1";
} else {
    $css_class = "row_color_2";
}
?>

<table border="0" width="100%" cellpadding="0" cellspacing="2" class="<?php echo $css_class; ?>">
    <tr>
        <td width="50"><font color="#000033" style="font-family: Arial;"><?php echo $DB_SubscriberID; ?></font></td>
        <td width="360"><font color="#000033" style="font-family: Arial;"><?php echo $DB_EmailAddress; ?></font></td>
        <td width="230"><font color="#000033" style="font-family: Arial;"><?php echo $DB_ResponderName; ?></font></td>
        <td width="45">
            <form action="admin.php" method=POST>
                <input type="hidden" name="sub_ID" value="<?php echo $DB_SubscriberID; ?>">
                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                <input type="hidden" name="action" value="sub_edit">
                <input type="image" src="<?php echo $siteURL . $ResponderDirectory; ?>/images/pen_edit.gif" name="Edit"
                       value="Edit">
            </form>
        </td>
        <td width="45">
            <form action="admin.php" method=POST>
                <input type="hidden" name="sub_ID" value="<?php echo $DB_SubscriberID; ?>">
                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                <input type="hidden" name="action" value="sub_delete">
                <input type="image" src="<?php echo $siteURL . $ResponderDirectory; ?>/images/trash_del.gif" name="Del"
                       value="Del">
            </form>
        </td>
    </tr>
</table>