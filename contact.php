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
  $deleteSQL = sprintf("DELETE FROM contact WHERE seno=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($deleteSQL, $kdmcnensasala) or die(mysql_error());

  $deleteGoTo = "contact.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $deleteGoTo));
}

mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_contact_view = "SELECT * FROM contact";
$contact_view = mysql_query($query_contact_view, $kdmcnensasala) or die(mysql_error());
$row_contact_view = mysql_fetch_assoc($contact_view);
$totalRows_contact_view = mysql_num_rows($contact_view);
?>
<?php include('top.php'); ?>
		
<div style="margin-bottom:0px;">
	<div class="pageTitle">Contact</div>
	<div style="float:right;clear:both;">
	<a href="contact/add.php" class="fancybox fancybox.iframe subnav">Add More Contact Info</a>
	</div>
</div>

<div class="bodyArea" style="">
	
    <table width="100%" border="0" class="viewList">
      <tbody>
        <tr>
          <th width="30" scope="col">SN</th>
          <th width="419" scope="col">Description</th>
          <th width="419" scope="col">Content</th>
          <th width="60" scope="col">Actions</th>
        </tr>
		  <?php $i = 1; ?>
  <?php do { ?>
        <tr>
          <td width="30" align="center"><?php echo $i; ?></td>
          <td align="center"><?php echo $row_contact_view['description']; ?></td>
          <td align="center"><?php echo $row_contact_view['content']; ?></td>
          <td width="60" align="center"><a href="contact.php?delete=<?php echo $row_contact_view['seno']; ?>" onClick="return confirm('Are you sure to delete this?')"><img src="support/buttons/features/delete.fw.png" width="30" height="30" alt=""/></a></td>
        </tr>
		  <?php $i = $i + 1; ?>
    <?php } while ($row_contact_view = mysql_fetch_assoc($contact_view)); ?>
      </tbody>
    </table>
</div>

	
<?php include('bottom.php'); ?>
<?php
mysql_free_result($contact_view);
?>
