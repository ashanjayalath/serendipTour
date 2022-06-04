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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
if(!empty($_FILES["image"]["name"])){
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


if (!file_exists('../../images/resorts/'.$year)) {
    mkdir('../../images/resorts/'.$year, 0777, true);
}
if (!file_exists('../../images/resorts/'.$year.'/'.$month)) {
    mkdir('../../images/resorts/'.$year.'/'.$month, 0777, true);
}
if (!file_exists('../../images/resorts/'.$year.'/'.$month.'/'.$date)) {
    mkdir('../../images/resorts/'.$year.'/'.$month.'/'.$date, 0777, true);

}
	
$moveResult = move_uploaded_file($fileTmpLoc, '../../images/resorts/'.$year.'/'.$month.'/'.$date.'/'.$fileName);
// Check to make sure the move result is true before continuing
if ($moveResult != true) {
    echo "ERROR: File not uploaded. Try again.";
    exit();
}
// Include the file that houses all of our custom image functions
include_once("../support/ak_php_img_lib_1.1.php");
// ---------- Start Universal Image Resizing Function --------
$target_file = '../../images/resorts/'.$year.'/'.$month.'/'.$date.'/'.$fileName;
$resized_file = '../../images/resorts/'.$year.'/'.$month.'/'.$date.'/'.'resized_'.$fileName;
$wmax = 720;
$hmax = 405;
ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
// ----------- End Universal Image Resizing Function ----------
// ---------- Start Convert to JPG Function --------
if (strtolower($fileExt) != "jpg") {
    $target_file = '../../images/resorts/'.$year.'/'.$month.'/'.$date.'/'.'resized_'.$fileName;
    $new_jpg = '../../images/resorts/'.$year.'/'.$month.'/'.$date.'/'.'resized_'.$kaboom[0].'.jpg';
    ak_img_convert_to_jpg($target_file, $new_jpg, $fileExt);
}
// ----------- End Convert to JPG Function -----------
// ---------- Start Image Watermark Function --------
$timeStamp = date('Ymdhis');
$target_file = '../../images/resorts/'.$year.'/'.$month.'/'.$date.'/'.'resized_'.$kaboom[0].'.jpg';
$wtrmrk_file = '../support/wm.png';//"watermark/wm.png";
$new_file = '../../images/resorts/'.$year.'/'.$month.'/'.$date.'/'.$timeStamp.'.jpg'; //"uploads/protected_".$kaboom[0].".jpg";
ak_img_watermark($target_file, $wtrmrk_file, $new_file);
// ----------- End Image Watermark Function -----------
//Delete Uploaded File
if (file_exists('../../images/resorts/'.$year.'/'.$month.'/'.$date.'/'.$fileName)){
	unlink('../../images/resorts/'.$year.'/'.$month.'/'.$date.'/'.$fileName);
}
if (file_exists('../../images/resorts/'.$year.'/'.$month.'/'.$date.'/resized_'.$fileName)){
	unlink('../../images/resorts/'.$year.'/'.$month.'/'.$date.'/resized_'.$fileName);;
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


$imgName = 'images/resorts/'.$year.'/'.$month.'/'.$date.'/'.$timeStamp.'.jpg';
	

//$imgName = basename( $_FILES["newsImg"]["name"]);	
} else{
	$imgName = $_POST['image_old'];
}
	
	
  $updateSQL = sprintf("UPDATE resorts_activity SET resort_id=%s, heading=%s, description=%s, image=%s WHERE seno=%s",
                       GetSQLValueString($_POST['resort_id'], "int"),
                       GetSQLValueString($_POST['heading'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($imgName, "text"),
                       GetSQLValueString($_POST['seno'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($updateSQL, $kdmcnensasala) or die(mysql_error());

  $updateGoTo = "resorts_activity.php?resort=".$_POST['resort_id'];
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_activity_edit = "-1";
if (isset($_GET['activity'])) {
  $colname_activity_edit = $_GET['activity'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_activity_edit = sprintf("SELECT * FROM resorts_activity WHERE seno = %s", GetSQLValueString($colname_activity_edit, "int"));
$activity_edit = mysql_query($query_activity_edit, $kdmcnensasala) or die(mysql_error());
$row_activity_edit = mysql_fetch_assoc($activity_edit);
$totalRows_activity_edit = mysql_num_rows($activity_edit);
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/styles.css">
<script type="text/javascript" src="../support/ckeditor/ckeditor.js"></script>
</head>

<body class="body_min">
<h2 class="page_min_head">Activity - <?php echo $row_resort_getinfo['name']; ?></h2>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="image" id="image">
  <table width="100%" border="0">
    <tr>
      <td width="100">Name:</td>
      <td><label for="heading2"></label>
      <input name="heading" type="text" id="heading2" value="<?php echo $row_activity_edit['heading']; ?>"></td>
      <td align="right">Image:</td>
      <td><input type="file" name="image" id="image">
      <input name="image_old" type="hidden" id="image_old" value="<?php echo $row_activity_edit['image']; ?>"></td>
    </tr>
    <tr>
      <td width="100">Description:</td>
      <td width="250">&nbsp;</td>
      <td width="100">&nbsp;</td>
      <td width="250">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"><label for="description"></label>
      <textarea name="description" id="description"><?php echo $row_activity_edit['description']; ?></textarea>
      <script type="text/javascript">CKEDITOR.replace('description');</script>
      </td>
    </tr>
    <tr>
      <td width="100">&nbsp;</td>
      <td width="250">&nbsp;</td>
      <td width="100">&nbsp;</td>
      <td width="250" align="right"><input name="seno" type="hidden" id="seno" value="<?php echo $row_activity_edit['seno']; ?>">
      <input name="resort_id" type="hidden" id="resort_id" value="<?php echo $row_activity_edit['resort_id']; ?>">        <input type="submit" name="button" id="button" value="Save"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>

</html>
<?php
mysql_free_result($resort_getinfo);

mysql_free_result($activity_edit);
?>
