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

if(isset($_GET['edit']) && !empty($_GET['edit'])){
	$updateSQL = sprintf("UPDATE feedback SET status=%s, time=time WHERE seno=%s",
                       GetSQLValueString($_GET['message'], "text"),
                       GetSQLValueString($_GET['edit'], "int"));

  mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
  $Result1 = mysql_query($updateSQL, $kdmcnensasala) or die(mysql_error());

  $updateGoTo = "feedback.php";
  header(sprintf("Location: %s", $updateGoTo));
}

$maxRows_feedback_view = 30;
$pageNum_feedback_view = 0;
if (isset($_GET['pageNum_feedback_view'])) {
  $pageNum_feedback_view = $_GET['pageNum_feedback_view'];
}
$startRow_feedback_view = $pageNum_feedback_view * $maxRows_feedback_view;

mysql_select_db($database_kdmcnensasala, $kdmcnensasala);
$query_feedback_view = "SELECT * FROM feedback ORDER BY seno DESC";
$query_limit_feedback_view = sprintf("%s LIMIT %d, %d", $query_feedback_view, $startRow_feedback_view, $maxRows_feedback_view);
$feedback_view = mysql_query($query_limit_feedback_view, $kdmcnensasala) or die(mysql_error());
$row_feedback_view = mysql_fetch_assoc($feedback_view);

if (isset($_GET['totalRows_feedback_view'])) {
  $totalRows_feedback_view = $_GET['totalRows_feedback_view'];
} else {
  $all_feedback_view = mysql_query($query_feedback_view);
  $totalRows_feedback_view = mysql_num_rows($all_feedback_view);
}
$totalPages_feedback_view = ceil($totalRows_feedback_view/$maxRows_feedback_view)-1;

$queryString_feedback_view = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_feedback_view") == false && 
        stristr($param, "totalRows_feedback_view") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_feedback_view = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_feedback_view = sprintf("&totalRows_feedback_view=%d%s", $totalRows_feedback_view, $queryString_feedback_view);
?>
<?php include('top.php'); ?>
		
<div style="margin-bottom:0px;">
	<div class="pageTitle">Feedbacks</div>
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
          <th scope="col">e-mail</th>
          <th scope="col">Feedback</th>
          <th width="80" scope="col">Rating</th>
          <th width="100" scope="col">Actions</th>
        </tr>
		  <?php $i = 1; ?>
  <?php do { ?>
        <tr>
          <td width="30" align="center"><?php echo $i; ?></td>
          <td><?php echo $row_feedback_view['name']; ?></td>
          <td><?php echo $row_feedback_view['email']; ?></td>
          <td><?php echo $row_feedback_view['message']; ?></td>
          <td width="80"><?php echo $row_feedback_view['rate']; ?> / 5</td>
          <td width="100" align="center" valign="middle">
            <?php if($row_feedback_view['status'] == 'hidden'){ ?>
            <a href="feedback.php?edit=<?php echo $row_feedback_view['seno']; ?>&message=publish" onClick="return confirm('Are you sure to publish this?')">Publish</a>
  <?php } else{ ?>
            <a href="feedback.php?edit=<?php echo $row_feedback_view['seno']; ?>&message=hidden" onClick="return confirm('Are you sure to unpublish this?')">Unpublish</a>
            <?php } ?>	  
            <a href="feedback.php?delete=<?php echo $row_feedback_view['seno']; ?>" onClick="return confirm('Are you sure to delete this?')"><img src="support/buttons/delete.fw.png" width="20" height="20" alt=""/></a></td>
        </tr>
		   <?php $i = $i + 1; ?>
    <?php } while ($row_feedback_view = mysql_fetch_assoc($feedback_view)); ?>
      </tbody>
    </table>
	
	
	<div class="pagenav">
      <a href="<?php printf("%s?pageNum_feedback_view=%d%s", $currentPage, 0, $queryString_feedback_view); ?>"><img src="support/buttons/first.fw.png" width="50" height="30" alt=""/></a>
		<a href="<?php printf("%s?pageNum_feedback_view=%d%s", $currentPage, max(0, $pageNum_feedback_view - 1), $queryString_feedback_view); ?>"><img src="support/buttons/previous.fw.png" width="50" height="30" alt=""/></a>
		<span class="showregion">Showing <?php echo ($startRow_feedback_view + 1) ?> to <?php echo min($startRow_feedback_view + $maxRows_feedback_view, $totalRows_feedback_view) ?> of <?php echo $totalRows_feedback_view ?> total feedbacks</span>
		<a href="<?php printf("%s?pageNum_feedback_view=%d%s", $currentPage, min($totalPages_feedback_view, $pageNum_feedback_view + 1), $queryString_feedback_view); ?>"><img src="support/buttons/next.fw.png" width="50" height="30" alt=""/></a>
		<a href="<?php printf("%s?pageNum_feedback_view=%d%s", $currentPage, $totalPages_feedback_view, $queryString_feedback_view); ?>"><img src="support/buttons/last.fw.png" width="50" height="30" alt=""/></a>
		
	</div>
	
	
</div>

	
<?php include('bottom.php'); ?>
<?php
mysql_free_result($feedback_view);
?>
