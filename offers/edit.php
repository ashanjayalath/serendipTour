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
  $updateSQL = sprintf("UPDATE offers SET heading=%s, `description`=%s, phone=%s, email=%s, reservation=%s WHERE seno=%s",
                       GetSQLValueString($_POST['heading'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['reservation'], "text"),
                       GetSQLValueString($_POST['seno'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($updateSQL, $kdmcnensasala) or die(mysql_error());

  $updateGoTo = "../support/crude/updated.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_service_edit = "-1";
if (isset($_GET['service'])) {
  $colname_service_edit = $_GET['service'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_service_edit = sprintf("SELECT * FROM offers WHERE seno = %s", GetSQLValueString($colname_service_edit, "int"));
$service_edit = mysql_query($query_service_edit, $kdmcnensasala) or die(mysql_error());
$row_service_edit = mysql_fetch_assoc($service_edit);
$totalRows_service_edit = mysql_num_rows($service_edit);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/styles.css">
<script type="text/javascript" src="../support/ckeditor/ckeditor.js"></script>	
</head>

<body class="body_min">
<h2 class="page_min_head">Offers</h2>
	
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="100%" border="0">
    <tbody>
      <tr>
        <td width="100">Heading:</td>
        <td><input name="heading" type="text" id="heading" value="<?php echo $row_service_edit['heading']; ?>"></td>
        <td align="right">Phone:</td>
        <td><input name="phone" type="text" id="phone" value="<?php echo $row_service_edit['phone']; ?>"></td>
      </tr>
      <tr>
        <td>e-mail:</td>
        <td><input name="email" type="email" id="email" value="<?php echo $row_service_edit['email']; ?>"></td>
        <td align="right">Reservation:</td>
        <td><input name="reservation" type="text" id="reservation" value="<?php echo $row_service_edit['reservation']; ?>"></td>
      </tr>
      <tr>
        <td width="100">Description:</td>
        <td width="250">&nbsp;</td>
        <td width="100">&nbsp;</td>
        <td width="250">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><textarea name="description" id="description"><?php echo $row_service_edit['description']; ?></textarea>
		  <script type="text/javascript">CKEDITOR.replace('description');</script>
	    </td>
      </tr>
      <tr>
        <td width="100">&nbsp;</td>
        <td width="250">&nbsp;</td>
        <td width="100">&nbsp;</td>
        <td width="250" align="right"><input name="seno" type="hidden" id="seno" value="<?php echo $row_service_edit['seno']; ?>">          <input type="submit" name="submit" id="submit" value="Save"></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>

</html>
<?php
mysql_free_result($service_edit);
?>
