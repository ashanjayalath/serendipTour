<?php require_once('../../Connections/kdmcnensasala.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
$time = time();	
$date = date("Y-m-d");
$month = date("M");
$year = date("Y");

// Access the $_FILES global variable for this specific file being uploaded
// and create local PHP variables from the $_FILES array of information
$fileName = $_FILES["logo"]["name"]; // The file name
$fileTmpLoc = $_FILES["logo"]["tmp_name"]; // File in the PHP tmp folder
$fileType = $_FILES["logo"]["type"]; // The type of file it is
$fileSize = $_FILES["logo"]["size"]; // File size in bytes
$fileErrorMsg = $_FILES["logo"]["error"]; // 0 for false... and 1 for true
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
include_once("../support/ak_php_img_lib_1.0.php");
// ---------- Start Universal Image Resizing Function --------
$target_file = '../../images/resorts/'.$year.'/'.$month.'/'.$date.'/'.$fileName;
$resized_file = '../../images/resorts/'.$year.'/'.$month.'/'.$date.'/'.'resized_'.$fileName;
$wmax = 350;
$hmax = 150;
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

  $insertSQL = sprintf("INSERT INTO resorts (name, `variable`, logo, `description`, country, place, comfort, airport, airplane, area, location) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['variable'], "text"),
                       GetSQLValueString($imgName, "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['country'], "text"),
                       GetSQLValueString($_POST['place'], "text"),
                       GetSQLValueString($_POST['comfort'], "text"),
                       GetSQLValueString($_POST['airport'], "text"),
                       GetSQLValueString($_POST['airplane'], "text"),
                       GetSQLValueString($_POST['area'], "text"),
                       GetSQLValueString($_POST['location'], "text"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($insertSQL, $kdmcnensasala) or die(mysql_error());

  $insertGoTo = "resorts_add.php?added=1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?><!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../support/styles.css">
<script src="../support/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="../support/ckeditor/ckeditor.js"></script>
</head>

<body class="body_min">

<h2 class="page_min_head">Add New Resort</h2>


<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">
  <table width="100%" border="0">
  <?php if(isset($_GET['added'])){ ?>
    <tr>
      <td colspan="4" align="center" bgcolor="#00FF33">Successfully Added!</td>
    </tr>
    <?php } ?>
    <tr>
      <td width="100">Name:</td>
      <td width="250"><label>
        <input type="text" name="name" id="name" required>
      </label></td>
      <td width="100" align="right">Logo:</td>
      <td width="250"><label>
        <input type="file" name="logo" id="logo" required>
      </label></td>
    </tr>
    <tr>
      <td width="100">Slogan:</td>
      <td colspan="3"><label>
        <input type="text" name="variable" id="variable" required>
      </label></td>
    </tr>
    <tr>
      <td width="100">Country:</td>
      <td width="250"><label>
        <input type="text" name="country" id="country" required>
      </label></td>
      <td width="100" align="right">Place:</td>
      <td width="250"><label>
        <input type="text" name="place" id="place" required>
      </label></td>
    </tr>
    <tr>
      <td>Comfort:</td>
      <td><label>
        <input type="text" name="comfort" id="comfort" required>
      </label></td>
      <td align="right">Area:</td>
      <td><label>
        <input type="text" name="area" id="area" required>
      </label></td>
    </tr>
    <tr>
      <td>Airport:</td>
      <td><label>
        <input type="text" name="airport" id="airport" required>
      </label></td>
      <td align="right">Airplane:</td>
      <td><label>
        <input type="text" name="airplane" id="airplane" required>
      </label></td>
    </tr>
    <tr>
      <td>Location:</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
		<td colspan="4"><textarea name="location" rows="2" required id="location" style="height:auto;"></textarea></td>
    </tr>
    <tr>
      <td>Description:</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"><label>
        <textarea name="description" id="description" required></textarea>
        <script type="text/javascript">CKEDITOR.replace('description');</script>
      </label></td>
    </tr>
    <tr>
      <td width="100">&nbsp;</td>
      <td width="250">&nbsp;</td>
      <td width="100" align="right">&nbsp;</td>
      <td width="250" align="right"><label>
        <input type="submit" name="button" id="button" value="Save">
      </label></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>
