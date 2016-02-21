<?php
     if ($config['tinyMCE'] == 1) {
?>
<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
 <script language="javascript" type="text/javascript">
 tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	editor_selector : "html_area",
	editor_deselector : "text_area",
        convert_urls : false
 });
 </script>
<?php
     }
?>