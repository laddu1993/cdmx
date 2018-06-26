<?php
error_reporting(0);
include_once $_SERVER['DOCUMENT_ROOT'].'/cdmx/classes/CarInventory.php';
$manufacturer = CarInventory::getInstance();
$table_data = $manufacturer->fetch_data();
if (isset($_POST['reqtype_del']) && !empty($_POST['reqtype_del'])) {
    $model_id = $_POST['model_id'];
    $whr = '(id = '.$model_id.')';
    $data = $manufacturer->delete_data('model',$whr);
    echo "success";
    exit();
}  
?>
<!DOCTYPE html>
<html>
<head>
	<title>Mini Car Inventory System</title>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<h1>Mini Car Inventory System</h1>
<div style="float: right;margin-top: -35px;margin-right: 12px;"><a href="add-manufacturer.php" class="btn btn-primary">ADD MANUFACTURER</a> &nbsp;<a href="add-car.php" class="btn btn-primary">ADD MODEL</a> &nbsp; </div>
<?php if(!empty($_GET['status'] == 'manfacturer')){ ?>
<div class="alert alert-success"><strong>Successfully!</strong> Added Manufacturer.</div> 
<?php }elseif (!empty($_GET['status'] == 'model')) { ?>
<div class="alert alert-success"><strong>Successfully!</strong> Added Model.</div>
<?php } ?>
<br><br>
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Manufacture Name</th>
                <th>Model Name</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        	<?php if(!empty($table_data)){ $i = 1; foreach ($table_data as $value) { ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['car_name']; ?></td>
                <td><?php echo $value['quantity']; ?></td>
                <td><a onclick="del_stud(this.id,'<?php echo $value['id']; ?>')" id="del_stud<?php echo $i; ?>" style="cursor: pointer;">Delete</a> </td> </td>
            </tr>
        <?php $i++; } } ?>

    </table>
</body>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable();
});

function del_stud(e,model_id){
   swal({
     title: "Are you sure?",
     text: "Once deleted, you will not be able to recover this Model!",
     icon: "warning",
     buttons: true,
     dangerMode: true,
   })
   .then((willDelete) => {
     if (willDelete) {
       $.ajax({
       type: "POST",
            url: "<?php echo $_SERVER['PHP_SELF']; ?>",
            async : false,
            data:{'model_id':model_id,'reqtype_del':'delete'},
            success : function(data){
             console.log(data);
           }
       });
       swal("Poof! Your Model has been deleted!", {
         icon: "success", 
       });
       myVar = setTimeout(alertFunc, 2000);
       
     } else {
       swal("Your Model is safe!"); 
     }
   });
}
function alertFunc() {
   window.location.reload(true);
}
</script>
</html>