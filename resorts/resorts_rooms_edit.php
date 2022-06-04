<?php require_once('../../Connections/kdmcnensasala.php'); ?>
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
  $updateSQL = sprintf("UPDATE resorts_rooms SET resort_id=%s, title=%s, `description`=%s, amount=%s WHERE seno=%s",
                       GetSQLValueString($_POST['resort_id'], "int"),
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['amount'], "text"),
                       GetSQLValueString($_POST['seno'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($updateSQL, $kdmcnensasala) or die(mysql_error());

  $updateGoTo = "resorts_rooms.php?resort=".$_POST['resort_id'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$colname_resort_getinfo = "-1";
if (isset($_GET['resort'])) {
  $colname_resort_getinfo = $_GET['resort'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_resort_getinfo = sprintf("SELECT * FROM resorts WHERE seno = %s", GetSQLValueString($colname_resort_getinfo, "int"));
$resort_getinfo = mysql_query($query_resort_getinfo, $kdmcnensasala) or die(mysql_error());
$row_resort_getinfo = mysql_fetch_assoc($resort_getinfo);
$totalRows_resort_getinfo = mysql_num_rows($resort_getinfo);

$colname_rooms_edit = "-1";
if (isset($_GET['room'])) {
  $colname_rooms_edit = $_GET['room'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_rooms_edit = sprintf("SELECT * FROM resorts_rooms WHERE seno = %s", GetSQLValueString($colname_rooms_edit, "int"));
$rooms_edit = mysql_query($query_rooms_edit, $kdmcnensasala) or die(mysql_error());
$row_rooms_edit = mysql_fetch_assoc($rooms_edit);
$totalRows_rooms_edit = mysql_num_rows($rooms_edit);
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/styles.css">
<script type="text/javascript" src="../support/ckeditor/ckeditor.js"></script>
</head>

<body class="body_min">
<h2 class="page_min_head">Rooms - <?php echo $row_resort_getinfo['name']; ?></h2>


<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
  <table width="100%" border="0">
    <tr>
      <td width="100">Title:</td>
      <td width="250"><label>
        <input name="title" type="text" id="title" value="<?php echo $row_rooms_edit['title']; ?>">
      </label></td>
      <td width="100" align="right">Amount:</td>
      <td width="250"><label>
        <input name="amount" type="text" id="amount" value="<?php echo $row_rooms_edit['amount']; ?>">
      </label></td>
    </tr>
    <tr>
      <td width="100">Description:</td>
      <td width="250">&nbsp;</td>
      <td width="100" align="right">&nbsp;</td>
      <td width="250">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"><label>
        <textarea name="description" id="description"><?php echo $row_rooms_edit['description']; ?></textarea>
        <script type="text/javascript">CKEDITOR.replace('description');</script>
      </label></td>
    </tr>
    <tr>
      <td width="100">&nbsp;</td>
      <td width="250">&nbsp;</td>
      <td width="100" align="right">&nbsp;</td>
      <td width="250" align="right"><label>
        <input name="seno" type="hidden" id="seno" value="<?php echo $row_rooms_edit['seno']; ?>">
        <input name="resort_id" type="hidden" id="resort_id" value="<?php echo $row_rooms_edit['resort_id']; ?>">
        <input type="submit" name="button" id="button" value="Save">
      </label></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  
</form>
</body>

</html>
<?php
mysql_free_result($resort_getinfo);

mysql_free_result($rooms_edit);
?>
