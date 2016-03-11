<?php
if (userIsLoggedIn()) {
    include_once('popup_js.php');
    ?>
    <center>
        <table border="0" width="760" cellpadding="2" cellspacing="0" class="cp_table">
            <tr>
                <td width="120">
                    <form action="responders.php" method=POST>
                        <input type="hidden" name="action" value="list">
                        <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                        <input type="submit" name="submit" value="Edit Resps" class="cp_butt">
                    </form>
                </td>
                <td width="120">
                    <form action="admin.php" method=POST>
                        <input type="hidden" name="action" value="list">
                        <input type="submit" name="submit" value="Edit Users" class="cp_butt">
                    </form>
                </td>
                <td colspan="4">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="cp_table">
                        <tr>
                            <td>
                                <form action="admin.php" method=POST>
                                    <input type="hidden" name="action" value="Form_Gen">
                                    <input type="submit" name="submit" value="Code It!" class="cp_butt"> &nbsp;
                                    <?php responderPulldown('r_ID'); ?>
                                </form>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="120">
                    <form action="admin.php" method=POST>
                        <input type="hidden" name="action" value="sub_addnew">
                        <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                        <input type="submit" name="submit" value="Add Users" class="cp_butt">
                    </form>
                </td>
                <td width="120">
                    <form action="admin.php" method=POST>
                        <input type="hidden" name="action" value="bulk_add">
                        <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                        <input type="submit" name="submit" value="Bulk Add" class="cp_butt">
                    </form>
                </td>
                <td width="120">
                    <form action="edit_config.php" method=POST>
                        <input type="hidden" name="action" value="edit">
                        <input type="submit" name="submit" value="Configure" class="cp_butt">
                    </form>
                </td>
                <td width="120">
                    <form action="blacklist.php" method=POST>
                        <input type="hidden" name="action" value="list">
                        <input type="submit" name="submit" value="Blacklist" class="cp_butt">
                    </form>
                </td>
                <td width="120">
                    <form action="tools.php" method=POST>
                        <input type="hidden" name="action" value="list">
                        <input type="submit" name="submit" value="Tools" class="cp_butt">
                    </form>
                </td>
                <td width="120">
                    <form action="bouncers.php" method=POST>
                        <input type="hidden" name="action" value="list">
                        <input type="submit" name="submit" value="Bouncers" class="cp_butt">
                    </form>
                </td>
            </tr>
        </table>
    </center>

    <table align="right" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <center><a href="manual.html#<?php echo $help_section; ?>"
                           onclick="return popper('manual.html#<?php echo $help_section; ?>')">Help</a></center>
            </td>
            <td width="50">&nbsp;</td>
            <td>
                <form action="logout.php" method=POST>
                    <input type="hidden" name="action" value="logout">
                    <input type="submit" name="submit" value="<< Logout >>" class="butt">
                </form>
            </td>
        </tr>
    </table>
    <br/>

    <?php
}
?>