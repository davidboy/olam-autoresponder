<?php
if ($alt) {
    $css_class = "row_color_1";
} else {
    $css_class = "row_color_2";
}
?>

<table border=0 width="750" cellpadding="0" cellspacing="2" class="<?php echo $css_class; ?>">
    <tr>
        <td width="50"><font color="#330000"><?php echo $DB_ResponderID; ?></font></td>
        <td width="500"><font color="#000033"><?php echo $DB_ResponderName; ?></font></td>
        <td width="100"><font color="#000033"><?php echo $User_Count; ?></font></td>
        <td width="75">
            <form action="admin.php" method=POST>
                <input type="hidden" name="r_ID" value="<?php echo $DB_ResponderID; ?>">
                <input type="hidden" name="action" value="edit_users">
                <input type="submit" name="submit" value="Users" class="butt">
            </form>
        </td>
    </tr>
</table>
