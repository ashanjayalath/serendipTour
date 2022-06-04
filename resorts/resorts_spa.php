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
  $deleteSQL = sprintf("DELETE FROM resorts_spa WHERE seno=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($deleteSQL, $kdmcnensasala) or die(mysql_error());

  $deleteGoTo = "resorts_spa.php?resort=".$_GET['resort'];
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

$colname_spa = "-1";
if (isset($_GET['resort'])) {
  $colname_spa = $_GET['resort'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_spa = sprintf("SELECT * FROM resorts_spa WHERE resort_id = %s", GetSQLValueString($colname_spa, "int"));
$spa = mysql_query($query_spa, $kdmcnensasala) or die(mysql_error());
$row_spa = mysql_fetch_assoc($spa);
$totalRows_spa = mysql_num_rows($spa);
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/styles.css">
</head>

<body class="body_min">
<h2 class="page_min_head">Spa - <?php echo $row_resort_getinfo['name']; ?></h2>
	
<div class="topBttn">
      <a href="resorts_spa_add.php?resort=<?php echo $_GET['resort']; ?>"><input type="submit" name="button" id="button" value="Add New Spa"></a>
</div>
	
<form id="form1" name="form1" method="post">
    <table width="100%" border="0" class="viewList">
      <tbody>
        <tr>
          <th width="40">SN</th>
          <th width="566">Heading</th>
          <th width="78">Actions</th>
        </tr>
		  <?php $i = 1; ?>
  <?php do { ?>
        <tr>
          <td width="40" align="center"><?php echo $i; ?></td>
          <td><?php echo $row_spa['heading']; ?></td>
          <td width="78" align="center"><a href="resorts_spa_edit.php?spa=<?php echo $row_spa['seno']; ?>&resort=<?php echo $row_spa['resort_id']; ?>"><img src="../support/buttons/features/edit.fw.png" width="30" height="30" alt=""/></a> <a href="resorts_spa.php?delete=<?php echo $row_spa['seno']; ?>&resort=<?php echo $row_spa['resort_id']; ?>" onClick="return confirm('Are you sure to delete this?')"><img src="../support/buttons/features/delete.fw.png" width="30" height="30" alt=""/></a></td>
        </tr>
		  <?php $i = $i + 1; ?>
    <?php } while ($row_spa = mysql_fetch_assoc($spa)); ?>
      </tbody>
    </table>
</form>
</body>

</html>
<?php
mysql_free_result($resort_getinfo);

mysql_free_result($spa);
?>
