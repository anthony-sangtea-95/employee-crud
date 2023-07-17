<?php
$mysqli->query("DELETE FROM `employee_hobby` WHERE employee_id= '" . $id . "'");
foreach ($hobbies as $hobby) {
    $upd_ins_hobby = "INSERT INTO `employee_hobby`
           (
               employee_id,
               hobby_id
           )
           VALUES
           (
               '" . $id . "',
               '" . $hobby . "'
           )";
    $upd_ins_res = $mysqli->query($upd_ins_hobby);
}
