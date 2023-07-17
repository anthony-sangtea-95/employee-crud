<?php

$employee_hobbies = [];
$select_employee_hobby_qry = "SELECT T01.name as hobby_name, T02.hobby_id FROM `hobbies` T01 LEFT JOIN `employee_hobby` T02 ON T01.id = T02.hobby_id WHERE T02.employee_id = '$id'";

$select_employee_hobby     = $mysqli->query($select_employee_hobby_qry);

while ($row = $select_employee_hobby->fetch_assoc()) {
   array_push($employee_hobbies, $row["hobby_name"]);
}
