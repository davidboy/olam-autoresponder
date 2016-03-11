<?php
if ($alt) {
    $css_class = "row_color_1";
} else {
    $css_class = "row_color_2";
}
?>

<table border="0" width="750" cellpadding="0" cellspacing="2" class="<?php echo $css_class; ?>">
    <tr>
        <td width="50">
            <center>
                <?php
                if ($data['Enabled'] == 1) {
                    print "<img src=\"$siteURL$ResponderDirectory/images/checkmark.gif\" border=\"0\">\n";
                }
                ?>
            </center>
        </td>
        <td width="300"><?php echo $data['EmailAddy']; ?></td>
        <td width="300"><?php echo $data['username']; ?></td>
        <td width="45">
            <center>
                <form action="bouncers.php" method=POST>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="b_ID" value="<?php echo $data['BouncerID']; ?>">
                    <input type="image" src="<?php echo $siteURL . $ResponderDirectory; ?>/images/pen_edit.gif"
                           name="Edit" value="Edit">
                </form>
            </center>
        </td>
        <td width="45">
            <center>
                <form action="bouncers.php" method=POST>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="b_ID" value="<?php echo $data['BouncerID']; ?>">
                    <input type="image" src="<?php echo $siteURL . $ResponderDirectory; ?>/images/trash_del.gif"
                           name="Del" value="Del">
                </form>
            </center>
        </td>
    </tr>
</table>
