<?php
require("./layouts/header.php");
require("./include_files/include_townships.php");
require("./include_files/include_hobbies.php");
require("./common/include_functions.php");

$name       = "";
$phone      = "";
$dob        = "";
$gender     = 0;
$township   = "";
$hobbies    = [];
$img_name   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register"])) {
   $name          = $mysqli->real_escape_string($_POST["name"]);

   $img_name      = $_FILES["image"]["name"];
   $tmp_name      = $_FILES["image"]["tmp_name"];

   $phone         = $mysqli->real_escape_string($_POST["phone"]);
   $dob           = $mysqli->real_escape_string($_POST["dob"]);
   $gender        = (int)($_POST["gender"]);
   $township      = (int)($mysqli->real_escape_string($_POST["township"]));
   $hobbies       = (isset($_POST["hobbies"]) ? $_POST["hobbies"] : []);
   $today_date    = date("Y-m-d H:i:s");

   $errors         = [];
   $empty_err_msg  = "Please, Fill up ";
   $success        = "";

   if ($name === "") {
      $errors["name"] = $empty_err_msg . "Name";
   } else {
      $check_name_sql = "SELECT id FROM `employees` WHERE name='" . $name . "' AND deleted_at IS NULL";
      $check_name_res = $mysqli->query($check_name_sql);
      if (mysqli_num_rows($check_name_res) > 0) {
         $errors["check_name"] = "This name (" . $name . ") is already existed";
      }
   }
   if ($phone === "") {
      $errors["phone"] = $empty_err_msg . "Phone";
   } else {
      $check_phone_sql = "SELECT id FROM `employees` WHERE phone='" . $phone . "' AND deleted_at IS NULL";
      $check_phone_res = $mysqli->query($check_phone_sql);
      if (mysqli_num_rows($check_phone_res) > 0) {
         $errors["check_phone"] = "This phone (" . $phone . ") is already existed";
      }
   }
   if ($dob === "") {
      $errors["dob"] = $empty_err_msg . "Date of Birth";
   }
   if ($township === "") {
      $errors["township"] = "Please Select Township";
   }
   if (isset($hobbies) && count($hobbies) === 0) {
      $errors["hobby"]   = "Please Select Hobbies";
   }
   if (count($errors) === 0) {
      if ($img_name === "") {
         $dob                 = changeToYmdFormat($dob);
         $insert_employee_qry = "INSERT INTO `employees`
            (
                name,
                phone,
                dob,
                gender,
                township_id
            )
            VALUES
            (
                '" . $name . "',
                '" . $phone . "',
                '" . $dob . "',
                '" . $gender . "',
                '" . $township . "'
            )";
         $result_employee     = $mysqli->query($insert_employee_qry);
      } else {
         $explode            = explode(".", $img_name);
         $extension          = end($explode);
         $allow_extensions   = ["jpg", "jpeg", "png", "webp", "gif"];

         if (in_array($extension, $allow_extensions)) {
            if (getimagesize($tmp_name)) {
               $dob                     = changeToYmdFormat($dob);
               $unique_img_name         = date("YmdHis") . uniqid() . "." . $extension;
               $insert_employee_qry      = "INSERT INTO `employees`
               (
                  name,
                  image,
                  phone,
                  dob,
                  gender,
                  township_id
               )
               VALUES
               (
                  '" . $name . "',
                  '" . $unique_img_name . "',
                  '" . $phone . "',
                  '" . $dob . "',
                  '" . $gender . "',
                  '" . $township . "'
               )";
               $result_employee    = $mysqli->query($insert_employee_qry);
               $upload_path       = "upload/" . $mysqli->insert_id . "/";
               if (!file_exists($upload_path)) {
                  mkdir($upload_path, 0777, true);
               }
               move_uploaded_file($tmp_name, $upload_path . $unique_img_name);
            } else {
               $errors["image"]   = "Sorry, Invalid  Format of Upload Image";
            }
         } else {
            $errors["image"]      = "Sorry, Invalid  File of Upload Image";
         }
      }
      if (isset($result_employee) && !isset($errors["image"])) {
         $insert_id  = $mysqli->insert_id;
         foreach ($hobbies as $hobby_id) {
            $insert_employee_hobby_qry = "INSERT INTO `employee_hobby`
                                                (
                                                    employee_id,
                                                    hobby_id
                                                )
                                          VALUES
                                                (
                                                    '" . $insert_id . "',
                                                    '" . $hobby_id . "'
                                                )
                                                ";
            $insert_employee_hobby     = $mysqli->query($insert_employee_hobby_qry);
         }
         if ($insert_employee_hobby) {
            $success    = "Registration Success !!";
            $url        = $base_url . "index.php?msg=" . $success . "";
            header("Refresh:0;url=$url");
         }
      }
   }
}
?>
<form action="<?php echo $base_url; ?>create.php" method="post" enctype="multipart/form-data" class="p-4 my-4 mx-4 shadow">
   <div class="mb-3">
      <h3 class="text-center">Fill Up Form</h3>
   </div>
   <div class="mb-3 text-center" id="preview-img-wrapper" style="display: none;">
      <img src="" id="preview-img" alt="" style="width: 100px;height:100px;border-radius:50px;object-fit:cover;object-position:center">
   </div>
   <div class="mb-3">
      <label for="image" class="form-label">Upload Image</label> <br />
      <input type="file" name="image" id="image" class="bg-white w-100" <?php if (isset($errors["image"])) : ?> onclick="clearErrorMsg('err-msg-img')" <?php endif ?> onchange="changePhoto(event)" />
      <?php if (isset($errors["image"])) : ?>
         <p class="err-msg-img text-danger"> <?php echo $errors["image"] ?></p>
      <?php endif ?>
   </div>
   <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" name="name" id="name" class="form-control" value="<?php echo $name ?>" <?php if (isset($errors["name"]) || isset($errors["check_name"])) : ?> onclick="clearErrorMsg('err-msg-name')" <?php endif ?> />
      <?php if (isset($errors["name"])) : ?>
         <p class="err-msg-name text-danger"> <?php echo $errors["name"] ?></p>
      <?php endif ?>
      <?php if (isset($errors["check_name"])) : ?>
         <p class="err-msg-name text-danger"> <?php echo $errors["check_name"] ?></p>
      <?php endif ?>
   </div>
   <div class="mb-3">
      <label for="phone" class="form-label">Phone</label>
      <input type="tel" name="phone" id="phone" class="form-control" value="<?php echo $phone ?>" <?php if (isset($errors["phone"]) || isset($errors["check_phone"])) : ?>onclick="clearErrorMsg('err-msg-phone')" <?php endif ?> />
      <?php if (isset($errors["phone"])) : ?>
         <p class="err-msg-phone text-danger"> <?php echo $errors["phone"] ?></p>
      <?php endif ?>
      <?php if (isset($errors["check_phone"])) : ?>
         <p class="err-msg-phone text-danger"> <?php echo $errors["check_phone"] ?></p>
      <?php endif ?>
   </div>
   <div class="mb-3">
      <label for="gender" class="form-label d-block">Gender</label>
      <input type="radio" name="gender" id="male" value="0" <?php if ($gender === 0) echo "checked"; ?>>
      <label for="male">Male</label>
      &nbsp;&nbsp;&nbsp;
      <input type="radio" name="gender" id="female" value="1" <?php if ($gender !== 0) echo "checked"; ?>>
      <label for="female">Female</label>
   </div>
   <div class="mb-3">
      <label for="dob" class="form-label"> Date of Birth</label>
      <input type="text" name="dob" id="dob" class="form-control" value="<?php echo $dob; ?>" <?php if (isset($errors["dob"])) : ?>onclick="clearErrorMsg('err-msg-dob')" <?php endif ?> readonly />
      <?php if (isset($errors["dob"])) : ?>
         <p class="err-msg-dob text-danger"> <?php echo $errors["dob"] ?></p>
      <?php endif ?>
   </div>
   <div class="mb-3">
      <label for="township">Township</label>
      <select name="township" id="township" class="form-select" <?php if (isset($errors["township"])) : ?>onclick="clearErrorMsg('err-msg-township')" <?php endif ?>>
         <option value="" <?php if ($township === "") echo "selected"; ?>>Choose Township
         </option>
         <?php
         foreach ($db_townships as $id => $db_township) {
         ?>
            <option value="<?php echo $id; ?>" <?php if ($township === $id) echo "selected"; ?>>
               <?php echo $db_township; ?>
            </option>
         <?php
         }
         ?>
      </select>
      <?php if (isset($errors["township"])) : ?>
         <p class="err-msg-township text-danger"> <?php echo $errors["township"] ?></p>
      <?php endif ?>
   </div>
   <div class="mb-3">
      <label for="hobbies"> Hobbies </label><br />
      <?php
      foreach ($db_hobbies as $hobby_id => $hobby) {
      ?>
         <input type="checkbox" name="hobbies[]" value="<?php echo $hobby_id; ?>" id="<?php echo $hobby; ?>" <?php if (in_array($hobby_id, $hobbies)) echo "checked"; ?> <?php if (isset($errors["hobby"])) : ?>onclick="clearErrorMsg('err-msg-hobby')" <?php endif ?> />
         <label for="<?php echo $hobby; ?>"><?php echo $hobby; ?></label>
         &nbsp;&nbsp;
      <?php
      }
      if (isset($errors["hobby"])) {
      ?>
         <p class="err-msg-hobby text-danger"> <?php echo $errors["hobby"] ?></p>
      <?php
      }
      ?>
   </div>
   <div class="mb-3 text-center">
      <input type="submit" value="Register" name="register" class="btn btn-primary w-100">
   </div>
</form>
<script>
   function clearErrorMsg(className) {
      let err_msg = document.querySelector("." + className);
      err_msg.style.display = "none";
   }
   $(function() {
      $("#dob").datepicker({
         minDate: "-100y",
         maxDate: "-15y",
      });
   });
</script>
<script>
   function changePhoto(event) {
      const previewImageWrapper = document.querySelector("#preview-img-wrapper");
      const previewImage = document.querySelector("#preview-img");
      const file = event.target.files[0];
      if (file) {
         if ((file.type).startsWith("image/")) {
            const reader = new FileReader();
            console.log(file)
            // Set up the FileReader to display the image preview when it's loaded
            reader.onload = function(e) {
               previewImage.setAttribute('src', e.target.result);
               previewImageWrapper.style.display = 'block';
            };

            // Read the image file as a data URL (base64-encoded string)
            reader.readAsDataURL(file);
         } else {
            previewImageWrapper.style.display = "none";
            alert("Invalid Image");
         }
      } else {
         previewImageWrapper.style.display = "none";
      }
   }
</script>
<?php
require("./layouts/footer.php");
?>