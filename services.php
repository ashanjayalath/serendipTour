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
  $deleteSQL = sprintf("DELETE FROM services WHERE seno=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($deleteSQL, $kdmcnensasala) or die(mysql_error());

  $deleteGoTo = "services.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $deleteGoTo));
}

mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_services = "SELECT * FROM services";
$services = mysql_query($query_services, $kdmcnensasala) or die(mysql_error());
$row_services = mysql_fetch_assoc($services);
$totalRows_services = mysql_num_rows($services);
?>
<?php include('top.php'); ?>
		
<div style="margin-bottom:0px;">
	<div class="pageTitle">Services</div>
	<div style="float:right;clear:both;">
	<a href="services/add.php" class="fancybox fancybox.iframe subnav">Add New Service</a>
	</div>
</div>

<div class="bodyArea" style="">
	
    <table width="100%" border="0" class="viewList">
      <tbody>
        <tr>
          <th width="30" scope="col">SN</th>
          <th scope="col">Heading</th>
          <th scope="col">Phone</th>
          <th scope="col">e-mail</th>
          <th scope="col">Reservation</th>
          <th width="100" scope="col">Actions</th>
        </tr>
<?php $i = 1; ?>
  <?php do { ?>
        <tr>
          <td width="30" align="center"><?php echo $i; ?></td>
          <td><?php echo $row_services['heading']; ?></td>
          <td align="center"><?php echo $row_services['phone']; ?></td>
          <td align="center"><?php echo $row_services['email']; ?></td>
          <td align="center"><?php echo $row_services['reservation']; ?></td>
          <td width="100" align="center"><a href="services/services_gallery.php?service=<?php echo $row_services['seno']; ?>" class="fancybox fancybox.iframe"><img src="support/buttons/features/gallery.fw.png" width="30" height="30" alt=""/></a>
			  <a href="services/edit.php?service=<?php echo $row_services['seno']; ?>" class="fancybox fancybox.iframe"><img src="support/buttons/features/edit.fw.png" width="30" height="30" alt=""/></a> <a href="services.php?delete=<?php echo $row_services['seno']; ?>" onClick="return confirm('Are you sure to delete this?')"><img src="support/buttons/features/delete.fw.png" width="30" height="30" alt=""/></a></td>
        </tr>
		  <?php $i = $i + 1; ?>
    <?php } while ($row_services = mysql_fetch_assoc($services)); ?>
      </tbody>
    </table>
</div>

	
<?php include('bottom.php'); ?>
<?php
mysql_free_result($services);
?>
