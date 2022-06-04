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

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_GET['delete'])) && ($_GET['delete'] != "")) {
  $deleteSQL = sprintf("DELETE FROM booking WHERE seno=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($deleteSQL, $kdmcnensasala) or die(mysql_error());

  $deleteGoTo = "booking.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_bookings_view = 30;
$pageNum_bookings_view = 0;
if (isset($_GET['pageNum_bookings_view'])) {
  $pageNum_bookings_view = $_GET['pageNum_bookings_view'];
}
$startRow_bookings_view = $pageNum_bookings_view * $maxRows_bookings_view;

mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_bookings_view = "SELECT * FROM booking ORDER BY seno DESC";
$query_limit_bookings_view = sprintf("%s LIMIT %d, %d", $query_bookings_view, $startRow_bookings_view, $maxRows_bookings_view);
$bookings_view = mysql_query($query_limit_bookings_view, $kdmcnensasala) or die(mysql_error());
$row_bookings_view = mysql_fetch_assoc($bookings_view);

if (isset($_GET['totalRows_bookings_view'])) {
  $totalRows_bookings_view = $_GET['totalRows_bookings_view'];
} else {
  $all_bookings_view = mysql_query($query_bookings_view);
  $totalRows_bookings_view = mysql_num_rows($all_bookings_view);
}
$totalPages_bookings_view = ceil($totalRows_bookings_view/$maxRows_bookings_view)-1;


$queryString_bookings_view = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_bookings_view") == false && 
        stristr($param, "totalRows_bookings_view") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_bookings_view = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_bookings_view = sprintf("&totalRows_bookings_view=%d%s", $totalRows_bookings_view, $queryString_bookings_view);
?>
<?php include('top.php'); ?>
		
<div style="margin-bottom:0px;">
  <div class="pageTitle">Bookings</div>
	<div style="float:right;clear:both;">
	<a href="http://www.google.com/" class="fancybox fancybox.iframe subnav">Kamis</a>
	<a href="http://www.google.com/" class="fancybox fancybox.iframe subnav">Kalees</a>
	</div>
</div>

<div class="bodyArea" style="">
	
    <table width="100%" border="0" class="viewList">
      <tbody>
        <tr>
          <th width="30" scope="col">SN</th>
          <th scope="col">Name</th>
          <th width="80" scope="col">Phone</th>
          <th width="150" scope="col">e-mail</th>
          <th width="120" scope="col">Resort</th>
          <th width="120" align="center" scope="col">Room</th>
          <th width="80" align="center" scope="col">Check In</th>
          <th width="80" align="center" scope="col">Check Out</th>
          <th width="60" scope="col">Actions</th>
        </tr>
		  <?php $i = 1; ?>
  <?php do { ?>
<?php
$resort = $row_bookings_view['hotel_id'];
$room = $row_bookings_view['room_id'];
	
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_resort_info = "SELECT * FROM resorts WHERE seno = '$resort'";
$resort_info = mysql_query($query_resort_info, $kdmcnensasala) or die(mysql_error());
$row_resort_info = mysql_fetch_assoc($resort_info);
$totalRows_resort_info = mysql_num_rows($resort_info);

mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_room_info = "SELECT * FROM resorts_rooms WHERE seno = '$room'";
$room_info = mysql_query($query_room_info, $kdmcnensasala) or die(mysql_error());
$row_room_info = mysql_fetch_assoc($room_info);
$totalRows_room_info = mysql_num_rows($room_info);
?>
        <tr>
          <td width="30" align="center"><?php echo $i; ?></td>
          <td width="150"><?php echo $row_bookings_view['name']; ?></td>
          <td width="80"><?php echo $row_bookings_view['phone']; ?></td>
          <td width="150"><?php echo $row_bookings_view['email']; ?></td>
          <td width="120"><?php echo $row_resort_info['name']; ?></td>
          <td width="120" align="center"><?php echo $row_room_info['title']; ?></td>
          <td width="80" align="center"><?php echo $row_bookings_view['check_in']; ?></td>
          <td width="80" align="center"><?php echo $row_bookings_view['check_out']; ?></td>
          <td width="60" align="center">
			  <a href="booking/view.php?seno=<?php echo $row_bookings_view['seno']; ?>" class="fancybox fancybox.iframe"><img src="support/buttons/view.fw.png" width="20" height="20" alt=""/></a>
			  <a href="booking.php?delete=<?php echo $row_bookings_view['seno']; ?>" onclick="return confirm('Are you sure to delete this?')"><img src="support/buttons/delete.fw.png" width="20" height="20" alt=""/></a>
			</td>
        </tr>
		  <?php $i = $i + 1; ?>
    <?php } while ($row_bookings_view = mysql_fetch_assoc($bookings_view)); ?>
      </tbody>
    </table>
	
	<div class="pagenav">
      <a href="<?php printf("%s?pageNum_bookings_view=%d%s", $currentPage, 0, $queryString_bookings_view); ?>"><img src="support/buttons/first.fw.png" width="50" height="30" alt=""/></a>
		<a href="<?php printf("%s?pageNum_bookings_view=%d%s", $currentPage, max(0, $pageNum_bookings_view - 1), $queryString_bookings_view); ?>"><img src="support/buttons/previous.fw.png" width="50" height="30" alt=""/></a>
		<span class="showregion">Showing <?php echo ($startRow_bookings_view + 1) ?> to <?php echo min($startRow_bookings_view + $maxRows_bookings_view, $totalRows_bookings_view) ?> of <?php echo $totalRows_bookings_view ?> total bookings</span>
		<a href="<?php printf("%s?pageNum_bookings_view=%d%s", $currentPage, min($totalPages_bookings_view, $pageNum_bookings_view + 1), $queryString_bookings_view); ?>"><img src="support/buttons/next.fw.png" width="50" height="30" alt=""/></a>
		<a href="<?php printf("%s?pageNum_bookings_view=%d%s", $currentPage, $totalPages_bookings_view, $queryString_bookings_view); ?>"><img src="support/buttons/last.fw.png" width="50" height="30" alt=""/></a> </div>
	
</div>

	
<?php include('bottom.php'); ?>
<?php
mysql_free_result($bookings_view);

mysql_free_result($resort_info);

mysql_free_result($room_info);
?>
