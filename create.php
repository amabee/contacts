<?php
include('config/conn.php');

$message = "";

if (isset($_POST['button'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['cnumber'];


    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $fileName = uniqid() . '_' . $_FILES['image']['name']; 
        $filePath = $uploadDir . $fileName;

       
        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            
            $sql = "INSERT INTO contactlist (firstname, lastname, contact_number, image) 
                    VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $firstname, $lastname, $phone, $filePath);

            if ($stmt->execute()) {
                echo "<script>alert('Contact created successfully.');</script>";
            } else {
                $msg = "Error: " . $sql . "<br>" . $conn->error;
                echo "<script>alert('$msg');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error uploading the image.');</script>";
        }
    } else {
        $filePath = null;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
    <link rel="stylesheet" href="css/create_custom_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="https://ka-f.fontawesome.com/releases/v6.4.0/css/free.min.css?token=af9f472305">
    <script src="https://kit.fontawesome.com/af9f472305.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <title>Create New Contact</title>
</head>
<body>
    <div class="center-div col-md-5">

        <h1 class="text-center">Create New Contact</h1>
        <form action="" method="POST" class="needs-validation" enctype="multipart/form-data">
           
            <div class="form-group">
                <label for="fname" class="form-label">Firstname</label>
                <input type="text" name="firstname" id="firstname" placeholder="Enter Firstname" class="form-control form-control-lg " required>
            </div>

            <div class="form-group">
                <label for="lname" class="form-label">Lastname</label>
                <input type="text" name="lastname" id="lastname" placeholder="Enter Lastname" class="form-control form-control-lg"  required>
            </div>

            <div class="form-group">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" name="cnumber" id="contact" placeholder="Enter Phone Number" class="form-control form-control-lg"  onkeyup="onlyFans(event)" required>
                <div class="invalid-feedback">Please input numbers only!</div>
            </div>

            <div class="form-group">
                <input type="file" name="image" id="image" class="form-control" placeholder="Select Image" onchange="preview()">
            </div>

            <input type="submit" class="btn btn-primary w-100" name="button" id="button" value="Submit">

            <img id="frame" src="" class="img-fluid img-thumbnail mx-auto d-block" style="margin-top: 10px;" />
        </form>
    </div>
   
</body>
</html>

<script type="text/javascript">
    

    function onlyFans(event){
       var input = event.target.value;
       var onlyNumberRegigas = /^[0-9]+$/;
    
       if(!onlyNumberRegigas.test(input)){
        event.target.value = input.replace(/[^0-9]/g, '');
       }

    }

    function preview() {
                frame.src = URL.createObjectURL(event.target.files[0]);
            }
</script>

