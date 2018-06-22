<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/cdmx/classes/Manufacturer.php';
$manufacturer = Manufacturer::getInstance();
$manufacturer_data = $manufacturer->fetch_table_data('manufacturer');
$colors = array('red' => 'red', 'blue' => 'blue', 'white' => 'white');
if (isset($_POST) && !empty($_POST)) {
    $m_id = $_POST['m_id'];
    $car_name = $_POST['car_name'];
    $color = $_POST['color'];
    $quantity = $_POST['quantity'];
    $serial_no = rand(1,9999);
    $post_data = array('m_id' => $m_id,'car_name' => $car_name,'color' => $color,'serial_no' => $serial_no,'quantity' => $quantity );
    if (!empty($m_id) && !empty($car_name)) {
        $whr = '(m_id = '.$m_id.' AND car_name = "'.$car_name.'")';
        $model_da = $manufacturer->fetch_table_data('model',$whr);
        if (!empty($model_da)) {
            header('location: car-model.php?status=fail');
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
    <title>MARKS</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <div class="container" style="margin-top: 120px;">
        <?php if(!empty($_GET['status'])){ ?><div class="alert alert-danger"><strong>Danger!</strong> Already Exist.</div> <?php } ?>
        <form action="" method="post">
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
            <br>
            <button type="submit" class="registerbtn btn btn-primary">Add</button>
        </form>
    </div>
</body>
</html>