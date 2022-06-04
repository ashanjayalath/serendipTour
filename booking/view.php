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

$colname_bookings_view = "-1";
if (isset($_GET['seno'])) {
  $colname_bookings_view = $_GET['seno'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_bookings_view = sprintf("SELECT * FROM booking WHERE seno = %s", GetSQLValueString($colname_bookings_view, "int"));
$bookings_view = mysql_query($query_bookings_view, $kdmcnensasala) or die(mysql_error());
$row_bookings_view = mysql_fetch_assoc($bookings_view);
$totalRows_bookings_view = mysql_num_rows($bookings_view);

$hotel = $row_bookings_view['hotel_id'];
$room = $row_bookings_view['room_id'];

mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_hotel_info = "SELECT * FROM resorts WHERE seno = $hotel";
$hotel_info = mysql_query($query_hotel_info, $kdmcnensasala) or die(mysql_error());
$row_hotel_info = mysql_fetch_assoc($hotel_info);
$totalRows_hotel_info = mysql_num_rows($hotel_info);

mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_room_info = "SELECT * FROM resorts_rooms WHERE seno = $room";
$room_info = mysql_query($query_room_info, $kdmcnensasala) or die(mysql_error());
$row_room_info = mysql_fetch_assoc($room_info);
$totalRows_room_info = mysql_num_rows($room_info);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
	<link rel="stylesheet" type="text/css" href="../support/styles.css">
</head>

<body class="body_min">
	
<h2 class="page_min_head">Bookings
  <input type="button" name="button" id="button" value="Print" onclick="myFunction()" style="float:right;clear:both;">
	<script>
function myFunction() {
    window.print();
}
</script>
</h2>
	
<table width="100%" border="0" class="viewList">
	  <tbody>
	    <tr>
	      <td width="100" bgcolor="#CCC"><strong>Name:</strong></td>
	      <td width="250"><?php echo $row_bookings_view['name']; ?></td>
	      <td width="100" bgcolor="#CCC"><strong>Phone:</strong></td>
	      <td width="250"><?php echo $row_bookings_view['phone']; ?></td>
        </tr>
	    <tr>
	      <td width="100" bgcolor="#CCC"><strong>e-mail:</strong></td>
	      <td width="250"><?php echo $row_bookings_view['email']; ?></td>
	      <td width="100" bgcolor="#CCC"><strong>Booked on:</strong></td>
	      <td width="250"><?php echo $row_bookings_view['time_date']; ?></td>
        </tr>
	    <tr>
	      <td width="100" bgcolor="#CCC"><strong>Remark:</strong></td>
	      <td colspan="3"><?php echo $row_bookings_view['remark']; ?></td>
        </tr>
	    <tr>
	      <td width="100" bgcolor="#CCC"><strong>Hotel:</strong></td>
	      <td width="250"><?php echo $row_hotel_info['name']; ?></td>
	      <td width="100" bgcolor="#CCC"><strong>Room:</strong></td>
	      <td width="250"><?php echo $row_room_info['title']; ?></td>
        </tr>
	    <tr>
	      <td bgcolor="#CCC"><strong>Check In:</strong></td>
	      <td><?php echo $row_bookings_view['check_in']; ?></td>
	      <td bgcolor="#CCC"><strong>Check Out:</strong></td>
	      <td><?php echo $row_bookings_view['check_out']; ?></td>
        </tr>
	    <tr>
	      <td bgcolor="#CCC"><strong>Adults:</strong></td>
	      <td><?php echo $row_bookings_view['adult']; ?></td>
	      <td bgcolor="#CCC"><strong>Children:</strong></td>
	      <td><?php echo $row_bookings_view['child']; ?></td>
        </tr>
	    <tr>
	      <td bgcolor="#CCC"><strong>Infants:</strong></td>
	      <td><?php echo $row_bookings_view['infant']; ?></td>
	      <td bgcolor="#CCC"><strong>Rooms:</strong></td>
	      <td><?php echo $row_bookings_view['rooms']; ?></td>
        </tr>
  </tbody>
</table>
</body>
</html>
<?php
mysql_free_result($bookings_view);

mysql_free_result($hotel_info);

mysql_free_result($room_info);
?>
