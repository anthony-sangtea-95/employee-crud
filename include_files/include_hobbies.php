<?php
$db_hobbies = [];

$select_hobby_qry = "SELECT id,name FROM `hobbies`";

$select_hobby     = $mysqli->query($select_hobby_qry);

while ($row = $select_hobby->fetch_assoc()) {
   $db_hobbies[$row["id"]] = $row["name"];
}
