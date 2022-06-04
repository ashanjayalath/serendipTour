<?php require_once('../Connections/kdmcnensasala.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE info_about SET about=%s, vision=%s, mission=%s, what_we_offer=%s, why_unique=%s WHERE seno=%s",
                       GetSQLValueString($_POST['about'], "text"),
                       GetSQLValueString($_POST['vision'], "text"),
                       GetSQLValueString($_POST['mission'], "text"),
                       GetSQLValueString($_POST['what_we_offer'], "text"),
                       GetSQLValueString($_POST['why_unique'], "text"),
                       GetSQLValueString($_POST['seno'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($updateSQL, $kdmcnensasala) or die(mysql_error());

  $updateGoTo = "about.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_about = "SELECT * FROM info_about";
$about = mysql_query($query_about, $kdmcnensasala) or die(mysql_error());
$row_about = mysql_fetch_assoc($about);
$totalRows_about = mysql_num_rows($about);
?>
<?php include('top.php'); ?>
		
<div style="margin-bottom:0px;">
	<div class="pageTitle">About Info</div>
	<div style="float:right;clear:both;">
	<!--<a href="http://www.google.com/" class="fancybox fancybox.iframe subnav">Kamis</a>
	<a href="http://www.google.com/" class="fancybox fancybox.iframe subnav">Kalees</a>-->
	</div>
</div>

<div class="bodyArea" style="">
  <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
<table width="100%" border="0">
	  <tbody>
	    <tr>
	      <td>About:</td>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
        </tr>
	    <tr>
	      <td colspan="4"><textarea name="about" id="about"><?php echo $row_about['about']; ?></textarea>
			<script type="text/javascript">CKEDITOR.replace('about');</script>
			</td>
        </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
        </tr>
	    <tr>
	      <td>Vision:</td>
	      <td>&nbsp;</td>
	      <td>Mission:</td>
	      <td>&nbsp;</td>
        </tr>
	    <tr>
	      <td colspan="2"><textarea name="vision" id="vision"><?php echo $row_about['vision']; ?></textarea>
			<script type="text/javascript">CKEDITOR.replace('vision');</script>
			</td>
	      <td colspan="2"><textarea name="mission" id="mission"><?php echo $row_about['mission']; ?></textarea>
			<script type="text/javascript">CKEDITOR.replace('mission');</script>
			</td>
        </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
        </tr>
	    <tr>
	      <td>What We Offer:</td>
	      <td>&nbsp;</td>
	      <td>Why we are Unique&nbsp;</td>
	      <td>&nbsp;</td>
        </tr>
	    <tr>
	      <td colspan="2"><textarea name="what_we_offer" id="what_we_offer"><?php echo $row_about['what_we_offer']; ?></textarea>
			<script type="text/javascript">CKEDITOR.replace('what_we_offer');</script>
			</td>
	      <td colspan="2"><textarea name="why_unique" id="why_unique"><?php echo $row_about['why_unique']; ?></textarea>
			<script type="text/javascript">CKEDITOR.replace('why_unique');</script>
			</td>
        </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
	      <td align="right"><input name="seno" type="hidden" id="seno" value="<?php echo $row_about['seno']; ?>">	        <input type="submit" name="submit" id="submit" value="Save"></td>
        </tr>
    </tbody>
  </table>
<input type="hidden" name="MM_update" value="form1">
  </form>
</div>

	
<?php include('bottom.php'); ?>
<?php
mysql_free_result($about);
?>
