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

if ((isset($_GET['delete'])) && ($_GET['delete'] != "")) {
  $deleteSQL = sprintf("DELETE FROM resorts_packages WHERE seno=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($deleteSQL, $kdmcnensasala) or die(mysql_error());

  $deleteGoTo = "resorts_packages.php?resort=".$_GET['resort'];
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $deleteGoTo));
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

$colname_packages = "-1";
if (isset($_GET['resort'])) {
  $colname_packages = $_GET['resort'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_packages = sprintf("SELECT * FROM resorts_packages WHERE resort_id = %s", GetSQLValueString($colname_packages, "int"));
$packages = mysql_query($query_packages, $kdmcnensasala) or die(mysql_error());
$row_packages = mysql_fetch_assoc($packages);
$totalRows_packages = mysql_num_rows($packages);
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/styles.css">
</head>

<body class="body_min">
<h2 class="page_min_head">Packages - <?php echo $row_resort_getinfo['name']; ?></h2>

<div class="topBttn">
      <a href="resorts_packages_add.php?resort=<?php echo $_GET['resort']; ?>"><input type="submit" name="button" id="button" value="Add New Package"></a>
</div>

<table width="100%" border="0" class="viewList">
  <tr>
    <th width="30" scope="col">SN</th>
    <th width="576" scope="col">Title</th>
    <th width="78" scope="col">Actions</th>
  </tr>
  <?php $i = 1; ?>
  <?php do { ?>
    <tr>
      <td width="30" align="center"><?php echo $i; ?></td>
      <td><?php echo $row_packages['heading']; ?></td>
      <td align="center"><a href="resorts_packages_edit.php?resort=<?php echo $_GET['resort']; ?>&package=<?php echo $row_packages['seno']; ?>"><img src="../support/buttons/features/edit.fw.png" width="30" height="30"></a> <a href="resorts_packages.php?resort=<?php echo $_GET['resort']; ?>&delete=<?php echo $row_packages['seno']; ?>" onClick="return confirm('Are you sure to delete this?')"><img src="../support/buttons/features/delete.fw.png" alt="" width="30" height="30"></a></td>
    </tr>
    <?php $i = $i + 1; ?>
    <?php } while ($row_packages = mysql_fetch_assoc($packages)); ?>
</table>
</body>

</html>
<?php
mysql_free_result($resort_getinfo);

mysql_free_result($packages);
?>
