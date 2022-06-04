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
  $deleteSQL = sprintf("DELETE FROM gallery WHERE seno=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($deleteSQL, $kdmcnensasala) or die(mysql_error());

  $deleteGoTo = "gallery.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $deleteGoTo));
}

mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_gallery_view = "SELECT * FROM gallery ORDER BY seno DESC";
$gallery_view = mysql_query($query_gallery_view, $kdmcnensasala) or die(mysql_error());
$row_gallery_view = mysql_fetch_assoc($gallery_view);
$totalRows_gallery_view = mysql_num_rows($gallery_view);
?>
<?php include('top.php'); ?>
		
<div style="margin-bottom:0px;">
	<div class="pageTitle">Gallery</div>
	<div style="float:right;clear:both;">
	<a href="gallery/add.php" class="fancybox fancybox.iframe subnav">Add New Image</a>
	</div>
</div>

<div class="bodyArea" style="">
	
  <?php do { ?>
    <div class="repeatDiv" style="text-align: center">
	<img src="../<?php echo $row_gallery_view['image']; ?>" style="width:150px;"><br>
    <a href="gallery/edit.php?seno=<?php echo $row_gallery_view['seno']; ?>" class="fancybox fancybox.iframe"><img src="support/buttons/edit.png" width="20" height="20" alt=""/></a>
		<a href="gallery.php?delete=<?php echo $row_gallery_view['seno']; ?>" onClick="return confirm('Are you sure to delete this?')"><img src="support/buttons/delete.fw.png" width="20" height="20" alt=""/></a> </div>
    <?php } while ($row_gallery_view = mysql_fetch_assoc($gallery_view)); ?>
	
</div>

	
<?php include('bottom.php'); ?>
<?php
mysql_free_result($gallery_view);
?>
