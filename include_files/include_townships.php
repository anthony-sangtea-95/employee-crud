<?php
$db_townships             = [];
$select_townships_qry  = "SELECT id,name FROM `townships`";
$select_townships      = $mysqli->query($select_townships_qry);

while ($row = $select_townships->fetch_assoc()) {
   $db_townships[$row["id"]] = $row["name"];
}
