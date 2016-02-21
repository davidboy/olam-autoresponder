<?php
/**
 * Allow these tags
 */
$allowedTags = '<center><u><table><td><tr><font><br><em><strong><h1><h2><h3><h4><h5><h6><b><i><a><ul><ol><li><pre><hr><blockquote><p><div><span><img>';

/**
 * Disallow these attributes/prefix within a tag
 */
$stripAttrib = 'javascript:|onclick|ondblclick|onmousedown|onmouseup|onmouseover|'.
               'onmousemove|onmouseout|onkeypress|onkeydown|onkeyup';

/**
 * @return string
 * @param string
 * @desc Strip forbidden tags and delegate tag-source check to removeEvilAttributes()
 */
function removeEvilTags($source)
{
   global $allowedTags;
   $source = strip_tags($source, $allowedTags);
   return preg_replace('/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source);
}

/**
 * @return string
 * @param string
 * @desc Strip forbidden attributes from a tag
 */
function removeEvilAttributes($tagSource)
{
   global $stripAttrib;
   return stripslashes(preg_replace("/$stripAttrib/i", 'forbidden', $tagSource));
}

function myaddslashes($st)
{
	if (get_magic_quotes_gpc())
		return $st;
	else
		return addslashes($st);
}

function MakeSafe($UnsafeSource)
  {
     return myaddslashes(htmlspecialchars(removeEvilTags(trim($UnsafeSource)),ENT_QUOTES));
  }

function MakeSuperSafe($UnsafeSource) {
     global $charset;
     return myaddslashes(htmlentities(removeEvilTags(trim($UnsafeSource)),ENT_QUOTES,$charset));
  }

function MakeSemiSafe($UnsafeSource) {
     return myaddslashes(removeEvilTags(trim($UnsafeSource)));
  }

# Will output: <a href="forbiddenalert(1);" target="_blank" forbidden =" alert(1)">test</a>
# echo removeEvilTags('<a href="javascript:alert(1);" target="_blank" OnPheasantOver="alert(1)">test</a>');
?>