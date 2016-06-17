<form action="login.php" method="POST">
    <center>
        <table width="300" bgcolor="#CCCCCC" style="border: 1px solid #000000;">
            <tr>
                <td>
                    <table align="center" width="400" cellspacing="2">
                        <tr>
                            <td colspan="2">
                                <center>
                                    <p class="big_header">
                                        Admin Control Panel Login
                                    </p>
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td width="180"><font size="4" color="#000033">Login: </font></td>
                            <td><input type="text" name="login" size=35 maxlength=200 class="fields"></td>
                        </tr>
                        <tr>
                            <td width="180"><font size="4" color="#000033">Password: </font></td>
                            <td><input type="password" name="pword" size=35 maxlength=200 class="fields"></td>
                        </tr>
                    </table>
                    <table align="right">
                        <tr>
                            <td colspan="2">
                                <input type="hidden" name="action" value="do_login"/>
                                <input type="submit" name="Login" value="Login" alt="Login" class="lo_b">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</form>