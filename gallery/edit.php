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
} else{
	$imgName = $_POST['image_old'];
}
	
  $updateSQL = sprintf("UPDATE gallery SET title=%s, descr=%s, image=%s WHERE seno=%s",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['descr'], "text"),
                       GetSQLValueString($imgName, "text"),
                       GetSQLValueString($_POST['seno'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($updateSQL, $kdmcnensasala) or die(mysql_error());

  $updateGoTo = "../support/crude/updated.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_gallery_edit = "-1";
if (isset($_GET['seno'])) {
  $colname_gallery_edit = $_GET['seno'];
}
mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_gallery_edit = sprintf("SELECT * FROM gallery WHERE seno = %s", GetSQLValueString($colname_gallery_edit, "int"));
$gallery_edit = mysql_query($query_gallery_edit, $kdmcnensasala) or die(mysql_error());
$row_gallery_edit = mysql_fetch_assoc($gallery_edit);
$totalRows_gallery_edit = mysql_num_rows($gallery_edit);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/styles.css">
</head>

<body class="body_min">
	
<h2 class="page_min_head">Edit Gallery Image</h2>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" id="form1">
  <table width="100%" border="0">
    <tbody>
      <tr>
        <td width="100">Title:</td>
        <td width="250"><input name="title" type="text" id="title" value="<?php echo $row_gallery_edit['title']; ?>"></td>
        <td width="100" align="right">Image:</td>
        <td width="250"><p>
          <input type="file" name="image" id="image">
        </p></td>
      </tr>
      <tr>
        <td width="100">Description:</td>
        <td width="250">&nbsp;</td>
        <td width="100" align="right">&nbsp;</td>
        <td width="250">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><textarea name="descr" rows="3" id="descr" style="height:auto;"><?php echo $row_gallery_edit['descr']; ?></textarea></td>
      </tr>
      <tr>
        <td width="100">&nbsp;</td>
        <td width="250">&nbsp;</td>
        <td width="100" align="right">&nbsp;</td>
        <td width="250" align="right"><input name="image_old" type="hidden" id="image_old" value="<?php echo $row_gallery_edit['image']; ?>">
        <input name="seno" type="hidden" id="seno" value="<?php echo $row_gallery_edit['seno']; ?>">          <input type="submit" name="submit" id="submit" value="Save"></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($gallery_edit);
?>
