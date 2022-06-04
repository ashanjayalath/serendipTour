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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE ``user`` SET name=%s, username=%s, password=%s WHERE seno=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['seno'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($updateSQL, $kdmcnensasala) or die(mysql_error());

  $updateGoTo = "users.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_user_edit = "SELECT * FROM `user`";
$user_edit = mysql_query($query_user_edit, $kdmcnensasala) or die(mysql_error());
$row_user_edit = mysql_fetch_assoc($user_edit);
$totalRows_user_edit = mysql_num_rows($user_edit);
?>
<?php include('top.php'); ?>
		
<div style="margin-bottom:0px;">
	<div class="pageTitle">User Settings</div>
	<div style="float:right;clear:both;"><!--
	<a href="http://www.google.com/" class="fancybox fancybox.iframe subnav">Kamis</a>
	<a href="http://www.google.com/" class="fancybox fancybox.iframe subnav">Kalees</a>-->
	</div>
</div>

<div class="bodyArea" style="">
	
	<div class="repeatDiv">
	  <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
	    <table width="400" border="0">
	      <tbody>
	        <tr>
	          <td width="75">Name:</td>
	          <td width="315"><input name="name" type="text" id="name" value="<?php echo $row_user_edit['name']; ?>"></td>
            </tr>
	        <tr>
	          <td>Username:</td>
	          <td><input name="username" type="text" id="username" value="<?php echo $row_user_edit['username']; ?>"></td>
            </tr>
	        <tr>
	          <td>Password:</td>
	          <td><input name="password" type="text" id="password" value="<?php echo $row_user_edit['password']; ?>"></td>
            </tr>
	        <tr>
	          <td>&nbsp;</td>
	          <td align="right"><input name="seno" type="hidden" id="seno" value="<?php echo $row_user_edit['seno']; ?>">	            <input type="submit" name="submit" id="submit" value="Save Changes"></td>
            </tr>
          </tbody>
        </table>
	    <input type="hidden" name="MM_update" value="form1">
      </form>
    </div>
	
</div>

	
<?php include('bottom.php'); ?>
<?php
mysql_free_result($user_edit);
?>
