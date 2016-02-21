<?php
   if ($alt) { $css_class = "row_color_1"; }
   else { $css_class = "row_color_2"; }
?>

<table border="0" width="750" cellpadding="0" cellspacing="2" class="<?php echo $css_class; ?>">
   <tr>
      <td width="30"><?php echo $DB_ResponderID; ?></td>
      <td width="200"><?php echo $DB_ResponderName; ?></td>
      <td width="200"><?php echo $DB_OwnerEmail; ?></td>
      <td width="150"><?php echo $DB_OwnerName; ?></td>
      <td width="45">
         <center>
            <form action="mailbursts.php" method=POST>
               <input type="hidden" name="action" value="list">
               <input type="hidden" name="r_ID"   value="<?php echo $DB_ResponderID; ?>">
               <input type="image" src="<?php echo $siteURL . $ResponderDirectory; ?>/images/email_env.gif" name="Bursts" value="Bursts">
            </form>
         </center>
      </td>
      <td width="45">
         <center>
            <form action="responders.php" method=POST>
               <input type="hidden" name="action" value="update">
               <input type="hidden" name="r_ID"   value="<?php echo $DB_ResponderID; ?>">
               <input type="image" src="<?php echo $siteURL . $ResponderDirectory; ?>/images/pen_edit.gif" name="Edit" value="Edit">
            </form>
         </center>
      </td>
      <td width="45">
         <center>
            <form action="responders.php" method=POST>
               <input type="hidden" name="action" value="erase">
               <input type="hidden" name="r_ID"   value="<?php echo $DB_ResponderID; ?>">
               <input type="image" src="<?php echo $siteURL . $ResponderDirectory; ?>/images/trash_del.gif" name="Del" value="Del">
            </form>
         </center>
      </td>
   </tr>
</table>
