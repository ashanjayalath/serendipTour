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

if ((isset($_GET['delete'])) && ($_GET['delete'] != "")) {
  $deleteSQL = sprintf("DELETE FROM resorts WHERE seno=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($deleteSQL, $kdmcnensasala) or die(mysql_error());

  $deleteGoTo = "resorts.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $deleteGoTo));
}

mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_resorts_view = "SELECT * FROM resorts";
$resorts_view = mysql_query($query_resorts_view, $kdmcnensasala) or die(mysql_error());
$row_resorts_view = mysql_fetch_assoc($resorts_view);
$totalRows_resorts_view = mysql_num_rows($resorts_view);
?>
<?php include('top.php'); ?>
		
<div style="margin-bottom:0px;">
	<div class="pageTitle">Resorts</div>
	<div style="float:right;clear:both;">
	<a href="resorts/resorts_add.php" class="fancybox fancybox.iframe subnav">Add New Resort</a>
	</div>
</div>

<div class="bodyArea" style="">
	
  <table width="100%" border="0" class="viewList">
    <tr>
        <th width="30" scope="col">SN</th>
        <th width="186" scope="col">Name</th>
        <th width="202" scope="col">Country</th>
        <th width="164" scope="col">Place</th>
        <th width="352" scope="col">Action</th>
      </tr>
      <?php $i = 1; ?>
      <?php do { ?>
        <tr>
          <td width="30" align="center"><?php echo $i; ?></td>
          <td><?php echo $row_resorts_view['name']; ?></td>
          <td><?php echo $row_resorts_view['country']; ?></td>
          <td><?php echo $row_resorts_view['place']; ?></td>
          <td align="center">
          <a href="resorts/resorts_gallery.php?resort=<?php echo $row_resorts_view['seno']; ?>" class="fancybox fancybox.iframe" title="Gallery"><img src="support/buttons/features/gallery.fw.png" width="30" height="30"></a>
          <a href="resorts/resorts_facilities.php?resort=<?php echo $row_resorts_view['seno']; ?>" class="fancybox fancybox.iframe" title="Facilities"><img src="support/buttons/features/facility.fw.png" width="30" height="30"></a>
          <a href="resorts/resorts_rooms.php?resort=<?php echo $row_resorts_view['seno']; ?>" class="fancybox fancybox.iframe" title="Rooms"><img src="support/buttons/features/stay.fw.png" width="30" height="30"></a>
          <a href="resorts/resorts_packages.php?resort=<?php echo $row_resorts_view['seno']; ?>" class="fancybox fancybox.iframe" title="Packages"><img src="support/buttons/features/package.fw.png" width="30" height="30"></a>
          <a href="resorts/resorts_dine.php?resort=<?php echo $row_resorts_view['seno']; ?>" class="fancybox fancybox.iframe" title="Dine"><img src="support/buttons/features/dine.fw.png" width="30" height="30"></a>
          <a href="resorts/resorts_activity.php?resort=<?php echo $row_resorts_view['seno']; ?>" class="fancybox fancybox.iframe" title="Activity"><img src="support/buttons/features/activity.fw.png" width="30" height="30"></a> 
          <a href="resorts/resorts_spa.php?resort=<?php echo $row_resorts_view['seno']; ?>" class="fancybox fancybox.iframe" title="Spa"><img src="support/buttons/features/spa.fw.png" width="30" height="30"></a>
          <a href="resorts/resorts_business.php?resort=<?php echo $row_resorts_view['seno']; ?>" class="fancybox fancybox.iframe" title="Business"><img src="support/buttons/features/business.fw.png" alt="" width="30" height="30"></a>
          
          <a href="resorts/resorts_edit.php?resort=<?php echo $row_resorts_view['seno']; ?>" class="fancybox fancybox.iframe"><img src="support/buttons/features/edit.fw.png" alt="" width="30" height="30"></a>
          
          <a href="resorts.php?delete=<?php echo $row_resorts_view['seno']; ?>" onclick="return confirm('Are you sure to delete this?')"><img src="support/buttons/features/delete.fw.png" alt="" width="30" height="30"></a>
          
              </td>
        </tr>
        <?php $i = $i + 1; ?>
        <?php } while ($row_resorts_view = mysql_fetch_assoc($resorts_view)); ?>
    </table>
</div>

	
<?php include('bottom.php'); ?>
<?php
mysql_free_result($resorts_view);
?>