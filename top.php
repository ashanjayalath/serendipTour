<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Serendib Tours</title>
<link rel="icon" type="img/x-icon" href="../images/logo1.png" />
<link rel="stylesheet" type="text/css" href="support/styles.css" />
<script type="text/javascript" src="support/ckeditor/ckeditor.js"></script>
<?php include('support/lightbox/include.php'); ?>
</head>

<body>
<div style="border-bottom: 1px solid #CCC;padding-bottom:5px;">
	<div style="display:inline-block;vertical-align:top;width:250px;height:120px;padding:5px;text-align:center">
		<img src="../images/logo1.png" style="height:100%;" />
	</div>
	
	<div style="display:inline-block;vertical-align:top;width:706px;height:130px;text-align:right;">
		<div style="height:85px">
			<div style="margin-top:15px;">
			<h3 style="margin:0">Content Management Casket for</h3>
			<h1 style="margin:0">Serendib Tours</h1>
			</div>
		</div>
		<div>
<!-- the menu area -->
<link rel="stylesheet" href="support/menufiles/mbcsmbrfzq.css" type="text/css" />
<div id="mbrfzqebul_wrapper">
  <ul id="mbrfzqebul_table" class="mbrfzqebul_menulist css_menu">
  <li><div class="buttonbg gradient_button gradient31"><a href="about.php">About</a></div></li>
  <li><div class="buttonbg gradient_button gradient31"><a href="resorts.php">Resorts</a></div></li>
  <li><div class="buttonbg gradient_button gradient31"><a href="services.php">Services</a></div></li>
  <li><div class="buttonbg gradient_button gradient31"><a href="offers.php">Offers</a></div></li>
  <li><div class="buttonbg gradient_button gradient31"><a href="contact.php">Contact</a></div></li>
  <li><div class="buttonbg gradient_button gradient31"><a href="booking.php">Booking</a></div></li>
  <li><div class="buttonbg gradient_button gradient31"><a href="feedback.php">Feedback</a></div></li>
  <li><div class="buttonbg gradient_button gradient31"><a href="gallery.php">Gallery</a></div></li>
  <li><div class="buttonbg gradient_button gradient31"><a href="users.php">Settings</a></div></li>
	  <li><div class="buttonbg gradient_button gradient31"><a href="<?php echo $logoutAction ?>">Logout</a></div></li>
  </ul></div><script type="text/javascript" src="support/menufiles/mbjsmbrfzq.js"></script>
<!-- the menu area -->
		</div>
	</div>
</div>

