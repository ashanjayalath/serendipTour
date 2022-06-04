<?php require_once('../../Connections/kdmcnensasala.php'); ?>
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
  $updateSQL = sprintf("UPDATE resorts_packages SET resort_id=%s, heading=%s, descripton=%s WHERE seno=%s",
                       GetSQLValueString($_POST['resort_id'], "int"),
                       GetSQLValueString($_POST['heading'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['seno'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($updateSQL, $kdmcnensasala) or die(mysql_error());

  $updateGoTo = "resorts_packages.php?resort=".$_POST['resort_id'];
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_resort_getinfo = "-1";
if (isset($_GET['resort'])) {
  $colname_resort_getinfo = $_GET['resort'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_resort_getinfo = sprintf("SELECT * FROM resorts WHERE seno = %s", GetSQLValueString($colname_resort_getinfo, "int"));
$resort_getinfo = mysql_query($query_resort_getinfo, $kdmcnensasala) or die(mysql_error());
$row_resort_getinfo = mysql_fetch_assoc($resort_getinfo);
$totalRows_resort_getinfo = mysql_num_rows($resort_getinfo);

$colname_packages_edit = "-1";
if (isset($_GET['package'])) {
  $colname_packages_edit = $_GET['package'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_packages_edit = sprintf("SELECT * FROM resorts_packages WHERE seno = %s", GetSQLValueString($colname_packages_edit, "int"));
$packages_edit = mysql_query($query_packages_edit, $kdmcnensasala) or die(mysql_error());
$row_packages_edit = mysql_fetch_assoc($packages_edit);
$totalRows_packages_edit = mysql_num_rows($packages_edit);
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/styles.css">
<script type="text/javascript" src="../support/ckeditor/ckeditor.js"></script>
</head>

<body class="body_min">
<h2 class="page_min_head">Packages - <?php echo $row_resort_getinfo['name']; ?></h2>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="100%" border="0">
    <tr>
      <td width="100">Name:</td>
      <td colspan="3"><label for="heading2"></label>
      <input name="heading" type="text" id="heading2" value="<?php echo $row_packages_edit['heading']; ?>"></td>
    </tr>
    <tr>
      <td width="100">Description:</td>
      <td width="250">&nbsp;</td>
      <td width="100">&nbsp;</td>
      <td width="250">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"><label for="description"></label>
      <textarea name="description" id="description"><?php echo $row_packages_edit['descripton']; ?></textarea>
      <script type="text/javascript">CKEDITOR.replace('description');</script>
      </td>
    </tr>
    <tr>
      <td width="100">&nbsp;</td>
      <td width="250">&nbsp;</td>
      <td width="100">&nbsp;</td>
      <td width="250" align="right"><input name="seno" type="hidden" id="seno" value="<?php echo $row_packages_edit['seno']; ?>">
      <input name="resort_id" type="hidden" id="resort_id" value="<?php echo $row_packages_edit['resort_id']; ?>">        <input type="submit" name="button" id="button" value="Save"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>

</html>
<?php
mysql_free_result($resort_getinfo);

mysql_free_result($packages_edit);
?>
