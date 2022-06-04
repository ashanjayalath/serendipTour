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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO offers (heading, `description`, phone, email, reservation) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['heading'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['reservation'], "text"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($insertSQL, $kdmcnensasala) or die(mysql_error());

  $insertGoTo = "../offers/add.php?added=1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
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
		<?php if(isset($_GET['added'])){ ?>
      <tr>
        <td colspan="4" align="center" bgcolor="#8FFF52">Successfully Added!</td>
      </tr>
		<?php } ?>
      <tr>
        <td width="100">Heading:</td>
        <td><input type="text" name="heading" id="heading"></td>
        <td align="right">Phone:</td>
        <td><input type="text" name="phone" id="phone"></td>
      </tr>
      <tr>
        <td>e-mail:</td>
        <td><input type="email" name="email" id="email"></td>
        <td align="right">Reservation:</td>
        <td><input type="text" name="reservation" id="reservation"></td>
      </tr>
      <tr>
        <td width="100">Description:</td>
        <td width="250">&nbsp;</td>
        <td width="100">&nbsp;</td>
        <td width="250">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><textarea name="description" id="description"></textarea>
		  <script type="text/javascript">CKEDITOR.replace('description');</script>
		  </td>
      </tr>
      <tr>
        <td width="100">&nbsp;</td>
        <td width="250">&nbsp;</td>
        <td width="100">&nbsp;</td>
        <td width="250" align="right"><input type="submit" name="submit" id="submit" value="Save"></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>

</html>