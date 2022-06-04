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

if ((isset($_GET['image'])) && ($_GET['image'] != "")) {
  $deleteSQL = sprintf("DELETE FROM offers_gallery WHERE seno=%s",
                       GetSQLValueString($_GET['image'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($deleteSQL, $kdmcnensasala) or die(mysql_error());

  $deleteGoTo = "offers_gallery.php?service=".$_GET['service'];
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $deleteGoTo));
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

$time = time();	
$date = date("Y-m-d");
$month = date("M");
$year = date("Y");

// Access the $_FILES global variable for this specific file being uploaded
// and create local PHP variables from the $_FILES array of information
$fileName = $_FILES["image"]["name"]; // The file name
$fileTmpLoc = $_FILES["image"]["tmp_name"]; // File in the PHP tmp folder
$fileType = $_FILES["image"]["type"]; // The type of file it is
$fileSize = $_FILES["image"]["size"]; // File size in bytes
$fileErrorMsg = $_FILES["image"]["error"]; // 0 for false... and 1 for true
$fileName = preg_replace('#[^a-z.0-9]#i', '', $fileName); // filter
$kaboom = explode(".", $fileName); // Split file name into an array using the dot
$fileExt = end($kaboom); // Now target the last array element to get the file extension

// START PHP Image Upload Error Handling -------------------------------
if (!$fileTmpLoc) { // if file not chosen
    echo "ERROR: Please browse for a file before clicking the upload button.";
    exit();
} else if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
    echo "ERROR: Your file was larger than 5 Megabytes in size.";
    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
    exit();
} else if (!preg_match("/.(gif|jpg|png)$/i", $fileName) ) {
     // This condition is only if you wish to allow uploading of specific file types    
     echo "ERROR: Your image was not .gif, .jpg, or .png.";
     unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
     exit();
} else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
    echo "ERROR: An error occured while processing the file. Try again.";
    exit();
}
// END PHP Image Upload Error Handling ---------------------------------
// Place it into your "uploads" folder mow using the move_uploaded_file() function


if (!file_exists('../../images/offers/'.$year)) {
    mkdir('../../images/offers/'.$year, 0777, true);
}
if (!file_exists('../../images/offers/'.$year.'/'.$month)) {
    mkdir('../../images/offers/'.$year.'/'.$month, 0777, true);
}
if (!file_exists('../../images/offers/'.$year.'/'.$month.'/'.$date)) {
    mkdir('../../images/offers/'.$year.'/'.$month.'/'.$date, 0777, true);

}
	
$moveResult = move_uploaded_file($fileTmpLoc, '../../images/offers/'.$year.'/'.$month.'/'.$date.'/'.$fileName);
// Check to make sure the move result is true before continuing
if ($moveResult != true) {
    echo "ERROR: File not uploaded. Try again.";
    exit();
}
// Include the file that houses all of our custom image functions
include_once("../support/ak_php_img_lib_1.1.php");
// ---------- Start Universal Image Resizing Function --------
$target_file = '../../images/offers/'.$year.'/'.$month.'/'.$date.'/'.$fileName;
$resized_file = '../../images/offers/'.$year.'/'.$month.'/'.$date.'/'.'resized_'.$fileName;
$wmax = 720;
$hmax = 405;
ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
// ----------- End Universal Image Resizing Function ----------
// ---------- Start Convert to JPG Function --------
if (strtolower($fileExt) != "jpg") {
    $target_file = '../../images/offers/'.$year.'/'.$month.'/'.$date.'/'.'resized_'.$fileName;
    $new_jpg = '../../images/offers/'.$year.'/'.$month.'/'.$date.'/'.'resized_'.$kaboom[0].'.jpg';
    ak_img_convert_to_jpg($target_file, $new_jpg, $fileExt);
}
// ----------- End Convert to JPG Function -----------
// ---------- Start Image Watermark Function --------
$timeStamp = date('Ymdhis');
$target_file = '../../images/offers/'.$year.'/'.$month.'/'.$date.'/'.'resized_'.$kaboom[0].'.jpg';
$wtrmrk_file = '../support/wm.png';//"watermark/wm.png";
$new_file = '../../images/offers/'.$year.'/'.$month.'/'.$date.'/'.$timeStamp.'.jpg'; //"uploads/protected_".$kaboom[0].".jpg";
ak_img_watermark($target_file, $wtrmrk_file, $new_file);
// ----------- End Image Watermark Function -----------
//Delete Uploaded File
if (file_exists('../../images/offers/'.$year.'/'.$month.'/'.$date.'/'.$fileName)){
	unlink('../../images/offers/'.$year.'/'.$month.'/'.$date.'/'.$fileName);
}
if (file_exists('../../images/offers/'.$year.'/'.$month.'/'.$date.'/resized_'.$fileName)){
	unlink('../../images/offers/'.$year.'/'.$month.'/'.$date.'/resized_'.$fileName);;
}
if (file_exists($target_file)){
	unlink($target_file);
}

// Display things to the page so you can see what is happening for testing purposes
/*echo "The file named <strong>$fileName</strong> uploaded successfuly.<br /><br />";
echo "It is <strong>$fileSize</strong> bytes in size.<br /><br />";
echo "It is an <strong>$fileType</strong> type of file.<br /><br />";
echo "The file extension is <strong>$fileExt</strong><br /><br />";
echo "The Error Message output for this upload is: $fileErrorMsg";*/


$imgName = 'images/offers/'.$year.'/'.$month.'/'.$date.'/'.$timeStamp.'.jpg';
	

//$imgName = basename( $_FILES["newsImg"]["name"]);

  $insertSQL = sprintf("INSERT INTO offers_gallery (offer_id, image) VALUES (%s, %s)",
                       GetSQLValueString($_POST['offer_id'], "int"),
                       GetSQLValueString($imgName, "text"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($insertSQL, $kdmcnensasala) or die(mysql_error());

  $insertGoTo = "offers_gallery.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_service_getinfo = "-1";
if (isset($_GET['service'])) {
  $colname_service_getinfo = $_GET['service'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_service_getinfo = sprintf("SELECT * FROM offers WHERE seno = %s", GetSQLValueString($colname_service_getinfo, "int"));
$service_getinfo = mysql_query($query_service_getinfo, $kdmcnensasala) or die(mysql_error());
$row_service_getinfo = mysql_fetch_assoc($service_getinfo);
$totalRows_service_getinfo = mysql_num_rows($service_getinfo);

$colname_service_gallery = "-1";
if (isset($_GET['service'])) {
  $colname_service_gallery = $_GET['service'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_service_gallery = sprintf("SELECT * FROM offers_gallery WHERE offer_id = %s", GetSQLValueString($colname_service_gallery, "int"));
$service_gallery = mysql_query($query_service_gallery, $kdmcnensasala) or die(mysql_error());
$row_service_gallery = mysql_fetch_assoc($service_gallery);
$totalRows_service_gallery = mysql_num_rows($service_gallery);


?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/styles.css">
</head>

<body class="body_min">
<h2 class="page_min_head">Offers - <?php echo $row_service_getinfo['heading']; ?></h2>



<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">
  <table width="100%" border="0">
    <tr>
      <td width="100">Image:</td>
      <td><label>
        <input type="file" name="image" id="image">
      </label></td>
      <td width="250" align="right"><label>
        <input name="offer_id" type="hidden" id="offer_id" value="<?php echo $row_service_getinfo['seno']; ?>">
        <input type="submit" name="button" id="button" value="Save">
      </label></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<hr>

<?php do { ?>
<div style="width:90px; display:inline-block; vertical-align:top; border:2px solid #CCC; margin:0 5px 5px 0;text-align:center;">
  <img src="../../<?php echo $row_service_gallery['image']; ?>" style="width:100%;"><br>
  <a href="offers_gallery.php?service=<?php echo $row_service_getinfo['seno']; ?>&image=<?php echo $row_service_gallery['seno']; ?>" onClick="return confirm('Are you sure to delete this?')"><img src="../support/buttons/delete.fw.png"></a></div>
  <?php } while ($row_service_gallery = mysql_fetch_assoc($service_gallery)); ?>

</body>


</html>
<?php
mysql_free_result($service_getinfo);

mysql_free_result($service_gallery);
?>
