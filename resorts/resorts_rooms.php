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
  $deleteSQL = sprintf("DELETE FROM resorts_rooms WHERE seno=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($deleteSQL, $kdmcnensasala) or die(mysql_error());

  $deleteGoTo = "resorts_rooms.php?resort=".$_GET['resort'];
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

$colname_rooms = "-1";
if (isset($_GET['resort'])) {
  $colname_rooms = $_GET['resort'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_rooms = sprintf("SELECT * FROM resorts_rooms WHERE resort_id = %s", GetSQLValueString($colname_rooms, "int"));
$rooms = mysql_query($query_rooms, $kdmcnensasala) or die(mysql_error());
$row_rooms = mysql_fetch_assoc($rooms);
$totalRows_rooms = mysql_num_rows($rooms);
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/styles.css">
</head>

<body class="body_min">
<h2 class="page_min_head">Rooms - <?php echo $row_resort_getinfo['name']; ?></h2>

<div class="topBttn">
      <a href="resorts_rooms_add.php?resort=<?php echo $row_resort_getinfo['seno']; ?>"><input type="submit" name="button" id="button" value="Add New Room"></a>
</div>

<form name="form1" method="post" action="">
  <table width="100%" border="0" class="viewList">
    <tr>
      <th width="28" scope="col">SN</th>
      <th width="520" scope="col">Title</th>
      <th width="136" scope="col">Actions</th>
    </tr>
    <?php $i = 1; ?>
    <?php do { ?>
      <tr>
          <td width="28" align="center"><?php echo $i; ?></td>
        <td><?php echo $row_rooms['title']; ?></td>
        <td align="center">
        <a href="resorts_rooms_gallery.php?resort=<?php echo $row_rooms['resort_id']; ?>&room=<?php echo $row_rooms['seno']; ?>"><img src="../support/buttons/features/gallery.fw.png" width="30" height="30"></a>
        <a href="resorts_rooms_facilities.php?resort=<?php echo $row_rooms['resort_id']; ?>&room=<?php echo $row_rooms['seno']; ?>"><img src="../support/buttons/features/facility.fw.png" width="30" height="30"></a>
        <a href="resorts_rooms_edit.php?resort=<?php echo $row_rooms['resort_id']; ?>&room=<?php echo $row_rooms['seno']; ?>"><img src="../support/buttons/features/edit.fw.png" width="30" height="30"></a>
        <a href="resorts_rooms.php?resort=<?php echo $row_rooms['resort_id']; ?>&delete=<?php echo $row_rooms['seno']; ?>" onClick="return confirm('Are you sure to delete this?')"><img src="../support/buttons/features/delete.fw.png" width="30" height="30"></a></td>
      </tr>
      <?php $i = $i + 1; ?>
      <?php } while ($row_rooms = mysql_fetch_assoc($rooms)); ?>
  </table>
</form>
</body>

</html>
<?php
mysql_free_result($resort_getinfo);

mysql_free_result($rooms);
?>
