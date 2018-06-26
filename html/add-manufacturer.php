<?php
error_reporting(0);
include_once $_SERVER['DOCUMENT_ROOT'].'/cdmx/classes/CarInventory.php';
$manufacturer = CarInventory::getInstance();
if (isset($_POST) && !empty($_POST)) {
    $name = $_POST['name'];
    $post_data = array('name' => $name );
    $data = $manufacturer->insert_data('manufacturer',$post_data);
    if ($data == true) {
        header('location: index.php?status=manfacturer');
    }
        

}
?>
    <!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html">
    <title>Add Manufacturer</title>
    <meta name="author" content="Vinil Lakkavatri">
    <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">
    <link rel="stylesheet" type="text/css" media="all" href="css/switchery.min.css">
    <script type="text/javascript" src="js/switchery.min.js"></script>
</head>

<body>
    <div id="wrapper">

    <h1>Add Manufacturer</h1>
    <form action="" method="post" id="imageUploadForm">
    <div class="col-12">
      <label>
      Manufacturer Name
      <input placeholder="Manufacturer Name" id="name" name="name" tabindex="2">
      </label>
    </div>

    <div class="col-submit">
        <button class="submitbtn">Add</button>
    </div>

        </form>
    </div>

</body>

</html>