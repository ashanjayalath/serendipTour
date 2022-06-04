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
  $deleteSQL = sprintf("DELETE FROM resorts_activity WHERE seno=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($deleteSQL, $kdmcnensasala) or die(mysql_error());

  $deleteGoTo = "resorts_activity.php?resort=".$_GET['resort'];
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

$colname_activity = "-1";
if (isset($_GET['resort'])) {
  $colname_activity = $_GET['resort'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_activity = sprintf("SELECT * FROM resorts_activity WHERE resort_id = %s", GetSQLValueString($colname_activity, "int"));
$activity = mysql_query($query_activity, $kdmcnensasala) or die(mysql_error());
$row_activity = mysql_fetch_assoc($activity);
$totalRows_activity = mysql_num_rows($activity);
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/styles.css">
</head>

<body class="body_min">
<h2 class="page_min_head">Activity - <?php echo $row_resort_getinfo['name']; ?></h2>

<div class="topBttn">
      <a href="resorts_activity_add.php?resort=<?php echo $_GET['resort']; ?>"><input type="submit" name="button" id="button" value="Add New Activity"></a>
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
      <td><?php echo $row_activity['heading']; ?></td>
      <td align="center"><a href="resorts_activity_edit.php?resort=<?php echo $_GET['resort']; ?>&activity=<?php echo $row_activity['seno']; ?>"><img src="../support/buttons/features/edit.fw.png" width="30" height="30"></a> <a href="resorts_activity.php?resort=<?php echo $_GET['resort']; ?>&delete=<?php echo $row_activity['seno']; ?>" onClick="return confirm('Are you sure to delete this?')"><img src="../support/buttons/features/delete.fw.png" alt="" width="30" height="30"></a></td>
    </tr>
    <?php $i = $i + 1; ?>
    <?php } while ($row_activity = mysql_fetch_assoc($activity)); ?>
</table>
</body>

</html>
<?php
mysql_free_result($resort_getinfo);

mysql_free_result($activity);
?>
