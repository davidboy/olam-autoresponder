<?php
if ($alt) {
    $css_class = "row_color_1";
} else {
    $css_class = "row_color_2";
}
?>

<table border=0 width="750" cellpadding="0" cellspacing="2" class="<?php echo $css_class; ?>">
    <tr>
        <td width="325">
            <?php echo $this_msg['Subject']; ?>
        </td>
        <td width="325">
            <?php echo $timesent; ?>
        </td>
        <td width="40">
            <FORM action="mailbursts.php" method=POST>
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                <input type="hidden" name="m_ID" value="<?php echo $this_msg['Mail_ID']; ?>">
                <input type="image" src="<?php echo $siteURL . $ResponderDirectory; ?>/images/pen_edit.gif" name="Edit"
                       value="Edit">
            </FORM>
        </td>
        <td width="40">
            <FORM action="mailbursts.php" method=POST>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="m_ID" value="<?php echo $this_msg['Mail_ID']; ?>">
                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                <input type="image" src="<?php echo $siteURL . $ResponderDirectory; ?>/images/trash_del.gif" name="Del"
                       value="Del">
            </FORM>
        </td>
    </tr>
</table>
