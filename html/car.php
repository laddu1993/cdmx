<?php
error_reporting(0);
//ini_set("display_errors", 1);
include_once $_SERVER['DOCUMENT_ROOT'].'/cdmx/classes/CarInventory.php';
$js_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cdmx/html/";
$manufacturer = CarInventory::getInstance();
$manufacturer_data = $manufacturer->fetch_table_data('manufacturer');
$colors = array('red' => 'red', 'blue' => 'blue', 'white' => 'white'); 

if (isset($_POST) && !empty($_POST)) {
    $m_id = $_POST['m_id'];
    $car_name = $_POST['car_name'];
    $color = $_POST['color'];
    $quantity = $_POST['quantity'];
    $car_image = $_POST['car_image'];
    $serial_no = rand(1,9999);
    $post_data = array('m_id' => $m_id,'car_name' => $car_name,'car_image' => $car_image,'color' => $color,'registration_no' => $serial_no,'quantity' => $quantity );
    if (!empty($m_id) && !empty($car_name)) {
        $whr = '(m_id = '.$m_id.' AND car_name = "'.$car_name.'")';
        $model_da = $manufacturer->fetch_table_data('model',$whr);
        if (!empty($model_da)) {
            header('location: car.php?status=fail');
        }else{
            $data = $manufacturer->insert_data('model',$post_data);
            if ($data == true) {
                header('location: index.php');
            }
        }
    } 
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>MARKS</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <div class="container" style="margin-top: 120px;">
        <?php if(!empty($_GET['status'])){ ?><div class="alert alert-danger"><strong>Danger!</strong> Already Exist.</div> <?php } ?>
        <form action="" method="post" id="imageUploadForm" enctype="multipart/form-data">
            <label>Manufacturer Name</label>
            <select name="m_id" class="form-control" required="">
            <option value="">SELECT</option>
            <?php foreach ($manufacturer_data as $value) { ?>
                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
            <?php } ?>
            </select>
            <label>Model Name</label>
            <input type="text" name="car_name" placeholder="Enter Model Name" class="form-control">
            <label>Color</label>
            <select name="color" class="form-control">
            <option value="">SELECT</option>
                <?php foreach ($colors as $value) { ?>
                <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                <?php } ?>
            </select>
            <label>Quantity</label>
            <input type="number" name="quantity" placeholder="Enter Quantity" class="form-control">
            <label>Car Image</label>
            <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" required>
            <input type="hidden" name="car_image" id="car_image" >
            <div id="show_image" style="display: none;">
              <img src="" style="width: 150px;height: 100px;">
            </div>
            <br>
            <img id="loading" src="loading.gif" />
            <div id="result"></div>
            <div class="text-muted" id="message"></div>
            <button type="submit" class="registerbtn btn btn-primary">Add</button>
        </form>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="http://malsup.github.com/min/jquery.form.min.js"></script>
<script type="text/javascript">
var MYAPP = MYAPP || {};
MYAPP.commonMethod = {
  file_browse: function (formData, url){
      var result;
      $.ajax({
          type: 'post',
          dataType: 'json',
          data: formData,
          enctype: 'multipart/form-data',
          contentType: false,
          cache: false,
          processData: false,
          async: false,
          url: url,
          beforeSend: function (xhr) {
              $("#loading").show();
          },
          success: function (data) {
            $("#loading").hide();
            result = data;
          },
          error: function (data) {
              result = data;
          }
      });
      return result;
  }
};

$("#loading").hide();
$('#fileToUpload').on('change', function (e){
    e.preventDefault();
    var formData = new FormData();
    formData.append('car_image', $('.container input[type=file]')[0].files[0]);
    var image_data = MYAPP.commonMethod.file_browse(formData, '<?php echo $js_url.'image-upload.php'; ?>');
    //console.log(image_data);
    //console.log(image_data.status);
    if (image_data.status == 'success'){
      $('#result').html(image_data.message);
      $('#car_image').val(image_data.web_url);
      $('#show_image').show();
      $('#show_image img').attr("src", image_data.web_url);
    }else{
      err_d = 'Image is not Uploaded!';
      $('#result').html(err_d);
    }
});
</script>
</html>