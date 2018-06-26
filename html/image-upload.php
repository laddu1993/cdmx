<?php 
$save_path = $_SERVER['DOCUMENT_ROOT']."/cdmx/uploads/";
$actual_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cdmx/uploads/";
if (!empty($_FILES) && isset($_FILES)) {
    $rand = rand();
    $image_name = $rand.'_'.$_FILES['car_image']['name'];
    $tmp_image_name = $_FILES['car_image']['tmp_name'];
    $image_type = $_FILES['car_image']['type'];
    $image_size = $_FILES['car_image']['size'];
    $status = move_uploaded_file($tmp_image_name, $save_path.$image_name);
    $file_arr = array('web_url' => $actual_url.$image_name, 'status' => 'success', 'message' => 'Image uploaded successfully', 'file_name' => $image_name);
    echo json_encode($file_arr);
    exit();
}

?>