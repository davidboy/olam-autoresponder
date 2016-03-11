<?php
$charset = $config['charset'];
$charset_selected[$charset] = " SELECTED";
if ($config['autocall_sendmails'] == "1") {
    $acs_1 = " CHECKED";
    $acs_2 = "";
} else {
    $acs_1 = "";
    $acs_2 = " CHECKED";
}
if ($config['check_mail'] == "1") {
    $chk_m1 = " CHECKED";
    $chk_m2 = "";
} else {
    $chk_m1 = "";
    $chk_m2 = " CHECKED";
}
if ($config['check_bounces'] == "1") {
    $chk_b1 = " CHECKED";
    $chk_b2 = "";
} else {
    $chk_b1 = "";
    $chk_b2 = " CHECKED";
}
if ($config['tinyMCE'] == "1") {
    $mce_1 = " CHECKED";
    $mce_2 = "";
} else {
    $mce_1 = "";
    $mce_2 = " CHECKED";
}

# Subs per page
$blah = $config['subs_per_page'];
$sbp[10] = "";
$sbp[25] = "";
$sbp[50] = "";
$sbp[75] = "";
$sbp[100] = "";
$sbp[250] = "";
$sbp[1000] = "";
$sbp[5000] = "";
$sbp[10000] = "";
$sbp[$blah] = " SELECTED";

# Add sub size
$blah = $config['add_sub_size'];
$asz[1] = "";
$asz[3] = "";
$asz[5] = "";
$asz[10] = "";
$asz[15] = "";
$asz[20] = "";
$asz[25] = "";
$asz[50] = "";
$asz[100] = "";
$asz[$blah] = " SELECTED";

include_once('popup_js.php');
?>

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td width="700">
            &nbsp;
        </td>
        <td>
            <a href="manual.html#configure" onclick="return popper('manual.html#configure')">Help</a>
        </td>
    </tr>
</table>
<br/>

<FORM action="edit_config.php" method=POST>
    <input type="hidden" name="action" value="save">
    <center>
        <table width="700" border="0" cellspacing="5" cellpadding="1" style="border: 1px solid #000000;">
            <tr>
                <td colspan="2" bgcolor="#0000CC">
                    <font color="#FFFFFF" size="4pt" face="Tahoma, Arial, Helvetica">
                        <center><strong>Edit Configuration</strong></center>
                    </font>
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            System directory for this install:
                        </font>
                    </strong>
                </td>
                <td>
                    <strong><?php echo $abs_directory; ?></strong>
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Max sends per sendmails run:
                        </font>
                    </strong>
                </td>
                <td>
                    <input maxlength="95" size="10" name="max_send_count"
                           value="<?php echo $config['max_send_count']; ?>">
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Daily send limit:
                        </font>
                    </strong>
                </td>
                <td>
                    <input maxlength="95" size="10" name="daily_limit" value="<?php echo $config['daily_limit']; ?>">
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Months of inactivity that trims:
                        </font>
                    </strong>
                </td>
                <td>
                    <input maxlength="10" size="10" name="last_activity_trim"
                           value="<?php echo $config['last_activity_trim']; ?>"> months (0 disables)
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Charset:
                        </font>
                    </strong>
                </td>
                <td>
                    <select name="charset">
                        <OPTION value="ISO-8859-1"<?php echo $charset_selected['ISO-8859-1']; ?>>ISO-8859-1</option>
                        <OPTION value="ISO-8859-15"<?php echo $charset_selected['ISO-8859-15']; ?>>ISO-8859-15</option>
                        <OPTION value="UTF-8"<?php echo $charset_selected['UTF-8']; ?>>UTF-8</option>
                        <OPTION value="cp866"<?php echo $charset_selected['cp866']; ?>>cp866</option>
                        <OPTION value="cp1251"<?php echo $charset_selected['cp1251']; ?>>cp1251</option>
                        <OPTION value="cp1252"<?php echo $charset_selected['cp1252']; ?>>cp1252</option>
                        <OPTION value="KOI8-R"<?php echo $charset_selected['KOI8-R']; ?>>KOI8-R</option>
                        <OPTION value="BIG5"<?php echo $charset_selected['BIG5']; ?>>BIG5</option>
                        <OPTION value="GB2312"<?php echo $charset_selected['GB2312']; ?>>GB2312</option>
                        <OPTION value="BIG5-HKSCS"<?php echo $charset_selected['BIG5-HKSCS']; ?>>BIG5-HKSCS</option>
                        <OPTION value="Shift_JIS"<?php echo $charset_selected['Shift_JIS']; ?>>Shift_JIS</option>
                        <OPTION value="EUC-JP"<?php echo $charset_selected['EUC-JP']; ?>>EUC-JP</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Check mail on sendmails.php:
                        </font>
                    </strong>
                </td>
                <td>
                    <input type="RADIO" name="check_mail" value="1"<?php echo $chk_m1; ?>>Yes &nbsp;
                    <input type="RADIO" name="check_mail" value="0"<?php echo $chk_m2; ?>>No
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Check bounces on sendmails.php:
                        </font>
                    </strong>
                </td>
                <td>
                    <input type="RADIO" name="check_bounces" value="1"<?php echo $chk_b1; ?>>Yes &nbsp;
                    <input type="RADIO" name="check_bounces" value="0"<?php echo $chk_b2; ?>>No
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Autocall sendmails.php on subscribe:
                        </font>
                    </strong>
                </td>
                <td>
                    <input type="RADIO" name="autocall_sendmails" value="1"<?php echo $acs_1; ?>>Yes &nbsp;
                    <input type="RADIO" name="autocall_sendmails" value="0"<?php echo $acs_2; ?>>No
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Lines on the subscriber add page:
                        </font>
                    </strong>
                </td>
                <td>
                    <select name="add_sub_size">
                        <OPTION value="1"<?php echo $asz[1]; ?>>1</option>
                        <OPTION value="3"<?php echo $asz[3]; ?>>3</option>
                        <OPTION value="5"<?php echo $asz[5]; ?>>5</option>
                        <OPTION value="10"<?php echo $asz[10]; ?>>10</option>
                        <OPTION value="15"<?php echo $asz[15]; ?>>15</option>
                        <OPTION value="20"<?php echo $asz[20]; ?>>20</option>
                        <OPTION value="25"<?php echo $asz[25]; ?>>25</option>
                        <OPTION value="50"<?php echo $asz[50]; ?>>50</option>
                        <OPTION value="100"<?php echo $asz[100]; ?>>100</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Subscribers per page on the list:
                        </font>
                    </strong>
                </td>
                <td>
                    <select name="subs_per_page">
                        <OPTION value="10"<?php echo $sbp[10]; ?>>10</option>
                        <OPTION value="25"<?php echo $sbp[25]; ?>>25</option>
                        <OPTION value="50"<?php echo $sbp[50]; ?>>50</option>
                        <OPTION value="75"<?php echo $sbp[75]; ?>>75</option>
                        <OPTION value="100"<?php echo $sbp[100]; ?>>100</option>
                        <OPTION value="250"<?php echo $sbp[250]; ?>>250</option>
                        <OPTION value="500"<?php echo $sbp[500]; ?>>500</option>
                        <OPTION value="1000"<?php echo $sbp[1000]; ?>>1000</option>
                        <OPTION value="5000"<?php echo $sbp[5000]; ?>>5000</option>
                        <OPTION value="10000"<?php echo $sbp[10000]; ?>>10000</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Site code:
                        </font>
                    </strong>
                </td>
                <td>
                    <input maxlength="200" size="50" name="site_code" value="<?php echo $config['site_code']; ?>">
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Admin username:
                        </font>
                    </strong>
                </td>
                <td>
                    <input maxlength="100" size="50" name="admin_user" value="<?php echo $config['admin_user']; ?>">
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Admin password:
                        </font>
                    </strong>
                </td>
                <td>
                    <input type="password" maxlength="100" size="50" name="admin_pass">
                </td>
            </tr>
            <tr>
                <td width="275">
                    <strong>
                        <font color="#003366" face="arial" size="2">
                            Enable TinyMCE:
                        </font>
                    </strong>
                </td>
                <td>
                    <input type="RADIO" name="tinyMCE" value="1"<?php echo $mce_1; ?>>Yes &nbsp;
                    <input type="RADIO" name="tinyMCE" value="0"<?php echo $mce_2; ?>>No
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    <input id="submit" type="submit" value="Save Changes">
                </td>
            </tr>
        </table>
    </center>
</form> 
