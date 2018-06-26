<?php
error_reporting(0);
//ini_set("display_errors", 1);
include_once $_SERVER['DOCUMENT_ROOT'].'/cdmx/classes/CarInventory.php';
$js_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cdmx/html/";
$manufacturer = CarInventory::getInstance();
$manufacturer_data = $manufacturer->fetch_table_data('manufacturer');
$colors = array('WHITE' => 'WHITE', 'BLACK' => 'BLACK', 'RED' => 'RED', 'BLUE' => 'BLUE'); 

if (isset($_POST) && !empty($_POST)) {
    $m_id = $_POST['m_id'];
    $car_name = $_POST['car_name'];
    $color = $_POST['color'];
    $quantity = $_POST['quantity'];
    $car_image = $_POST['car_image'];
    $status = $_POST['status'];
    $manufacturing_date = $_POST['manufacturing_date'];
    $serial_no = rand(1,9999);
    $post_data = array('m_id' => $m_id,'car_name' => $car_name,'car_image' => $car_image,'color' => $color,'registration_no' => $serial_no,'quantity' => $quantity, 'status' => $status, 'manufacturing_date' => $manufacturing_date );
    if (!empty($m_id) && !empty($car_name)) {
        $whr = '(m_id = '.$m_id.' AND car_name = "'.$car_name.'" AND color = "'.$color.'")';
        $model_da = $manufacturer->fetch_table_data('model',$whr);
        if (!empty($model_da)) {
            header('location: add-car.php?status=fail');
        }else{
            $data = $manufacturer->insert_data('model',$post_data);
            if ($data == true) {
                header('location: index.php?status=model');
            }
        }
    } 
}
?>
    <!doctype html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html">
    <title>ADD CAR MODEL</title>
    <meta name="author" content="Vinil Lakkavatri">
    <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">
    <link rel="stylesheet" type="text/css" media="all" href="css/switchery.min.css">
    <script type="text/javascript" src="js/switchery.min.js"></script>
</head>

<body>
    <div id="wrapper">

    <h1>ADD CAR MODEL <?php if(!empty($_GET['status'])){ ?><div class="alert alert-danger"><strong>Danger!</strong> Already Exist.</div> <?php } ?></h1>

    <form action="" method="post" id="imageUploadForm" enctype="multipart/form-data">
    <div class="col-2">
      <label>
      Manufacturer Name
      <select name="m_id" class="form-control" required="">
      <option value="">SELECT</option>
      <?php foreach ($manufacturer_data as $value) { ?>
          <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
      <?php } ?>
      </select>
      </label>
    </div>
    <div class="col-2">
      <label>
      Model Name
      <input placeholder="Model Name" id="car_name" name="car_name" tabindex="2">
      </label>
    </div>

    <div class="col-3">
      <label>
      Model Color
      <select name="color" class="form-control">
      <option value="">SELECT</option>
          <?php foreach ($colors as $value) { ?>
          <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
          <?php } ?>
      </select>
      </label>
    </div>
    <div class="col-3">
      <label>
      Model Quantity
      <input type="number" placeholder="" id="quantity" name="quantity" tabindex="4">
      </label>
    </div>
    <div class="col-3">
      <label>
      Model Availability
      <select class="form-control" name="status">
        <option value=""> SELECT </option>
        <option value="0">Yes</option>
        <option value="1">No</option>
      </select>
      </label>
    </div>

    <div class="col-6">
      <label>
      Car Image
      <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" required>
      <input type="hidden" name="car_image" id="car_image" >
      <br>
      <img id="loading" src="loading.gif" />
      <br>
      <div id="result"></div>
    </label>
    </div>
    <div class="col-4" id="show_image" style="display: none;">
        <img src="" style="width: 150px;height: 100px;">
    </div>
    <div class="col-4">
      <label>
      Manufacturing Year
      <input type="date" name="manufacturing_date" id="manufacturing_date" class="form-control" required>
      </label>
    </div>
    <div class="col-submit">
        <button class="submitbtn">Add</button>
    </div>

        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://malsup.github.com/min/jquery.form.min.js"></script>
    <script type="text/javascript">
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });

        var MYAPP = MYAPP || {};
        MYAPP.commonMethod = {
            file_browse: function(formData, url) {
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
                    beforeSend: function(xhr) {
                        $("#loading").show();
                    },
                    success: function(data) {
                        $("#loading").hide();
                        result = data;
                    },
                    error: function(data) {
                        result = data;
                    }
                });
                return result;
            }
        };

        $("#loading").hide();
        $('#fileToUpload').on('change', function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append('car_image', $('#wrapper input[type=file]')[0].files[0]);
            var image_data = MYAPP.commonMethod.file_browse(formData, '<?php echo $js_url.'image-upload.php '; ?>');
            //console.log(image_data);
            //console.log(image_data.status);
            if (image_data.status == 'success') {
                $('#result').html(image_data.message);
                $('#car_image').val(image_data.web_url);
                $('#show_image').show();
                $('#show_image img').attr("src", image_data.web_url);
            } else {
                err_d = 'Image is not Uploaded!';
                $('#result').html(err_d);
            }
        });
    </script>
</body>

</html>