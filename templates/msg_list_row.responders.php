<?php
if ($alt) {
    $css_class = "row_color_1";
} else {
    $css_class = "row_color_2";
}
?>

<table border=0 width="750" cellpadding="0" cellspacing="2" class="<?php echo $css_class; ?>">
    <tr>
        <td width="275"><?php echo $DB_MsgSub; ?></td>
        <td width="375">
            <?php
            print "$T_months months, ";
            print "$T_weeks weeks, ";
            print "$T_days days, ";
            print "$T_hours hours, ";
            print "$T_minutes minutes.";
            ?>
        </td>
        <td width="45">
            <FORM action="messages.php" method=POST>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="r_ID" value="<?php echo $DB_ResponderID; ?>">
                <input type="hidden" name="MSG_ID" value="<?php echo $M_ID; ?>">
                <input type="image" src="<?php echo $siteURL . $ResponderDirectory; ?>/images/pen_edit.gif" name="Edit"
                       value="Edit">
            </FORM>
        </td>
        <td width="45">
            <FORM action="messages.php" method=POST>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="r_ID" value="<?php echo $DB_ResponderID; ?>">
                <input type="hidden" name="MSG_ID" value="<?php echo $M_ID; ?>">
                <input type="image" src="<?php echo $siteURL . $ResponderDirectory; ?>/images/trash_del.gif" name="Del"
                       value="Del">
            </FORM>
        </td>
    </tr>
</table>
