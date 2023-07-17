<?php
require("require.php");
$id             = (int) $_GET["id"];
$id             = $mysqli->real_escape_string($id);
$today          = date("Y-m-d H:i:s");
$del_update_qry = "UPDATE `employees` SET deleted_at='" . $today . "' WHERE id = '" . $id . "'";
$result         = $mysqli->query($del_update_qry);

if ($result) {
   $success = "Delete Success ...";
   $url     = $base_url . "index.php?msg=" . $success . "";
   header("Refresh:0;url=$url");
}
