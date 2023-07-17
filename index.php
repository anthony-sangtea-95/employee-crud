<?php
include("./layouts/header.php");
$select_all_qry = "SELECT T01.id,T01.name as employee_name,T01.image,T01.phone,T01.dob,T01.gender,T01.township_id,T02.name as township_name FROM `employees` T01 LEFT JOIN `townships` T02 ON T01.township_id = T02.id WHERE T01.deleted_at IS NULL ORDER BY T01.id DESC";
$result         = $mysqli->query($select_all_qry);
?>
<?php if (isset($_GET["msg"])) : ?>
   <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo $_GET["msg"]; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="closeMsg()">
      </button>
   </div>
<?php endif ?>

<div class="my-3 d-flex justify-content-end mr-3">
   <a href="<?php echo $base_url ?>create.php" class="btn btn-success me-3 p-2">Add Employee+</a>
</div>
<div class="mb-3 text-center">
   <h1 class="text-secondary">Employee Information</h1>
</div>
<table border="1" class="table table-bordered table-hover text-center">
   <thead class="table-dark">
      <tr>
         <th>Name</th>
         <th>Phone</th>
         <th>Date of Birth</th>
         <th>Gender</th>
         <th>Township</th>
         <th>Hobbies</th>
         <th>Action</th>
      </tr>
   </thead>
   <tbody style="cursor:pointer;">
      <?php if (mysqli_num_rows($result) > 0) {
         while ($row = mysqli_fetch_assoc($result)) {
            $id              = (int) $row["id"];
            $name            =  htmlspecialchars($row["employee_name"]);
            $image           =  htmlspecialchars($row["image"]);
            $phone           =  htmlspecialchars($row["phone"]);
            $dob             =  htmlspecialchars($row["dob"]);
            $gender          = (int)($row["gender"]);
            $township_name   = htmlspecialchars($row["township_name"]);
      ?>
            <tr>
               <td>
                  <div class="img-name">
                     <?php
                     if ($image !== "") {
                     ?>
                        <div>
                           <img src="<?php echo $base_url; ?>upload/<?php echo $id; ?>/<?php echo $image ?>" alt="" style="width:60px;height:60px;object-fit: cover;object-position: center; border-radius:30px" />
                        </div>
                     <?php
                     } else {
                     ?>
                        <div>
                           <img src="<?php echo $base_url; ?>assets/icons/no-image-person.png" alt="" style="width:60px;height:60px;object-fit: cover;object-position: center; border-radius:30px" />
                        </div>
                     <?php
                     }
                     ?>
                     <div class="ms-2"><?php echo $name; ?></div>
                  </div>
               </td>
               <td><?php echo $phone; ?></td>
               <td><?php echo $dob; ?></td>
               <td><?php echo $common_gender[$gender]; ?></td>
               <td><?php echo $township_name; ?></td>
               <td>
                  <?php
                  require("./include_files/include_employee_hobby.php");
                  $hobbies = join(",", $employee_hobbies);
                  echo $hobbies;
                  ?>
               </td>
               <td>
                  <a href="<?php echo $base_url ?>edit.php?id=<?php echo $id ?>" class="btn btn-warning text-dark">
                     Edit
                  </a>
                  <a href="<?php echo $base_url ?>delete.php?id=<?php echo $id ?>" class="btn btn-danger" onclick="confirm('Are You Sure ?')">
                     Delete
                  </a>
               </td>
            </tr>
      <?php
         }
      }
      ?>
   </tbody>
</table>
<script>
   function closeMsg() {
      let div = document.querySelector(".alert");
      div.style.border = "0";
      div.style.display = "none";
   }
</script>
<?php
include("./layouts/footer.php");
?>