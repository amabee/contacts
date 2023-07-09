<?php
require('config/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $cnumber = $_POST['cnumber'];

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $fileName = uniqid() . '_' . $_FILES['image']['name'];
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            // Delete the old image file if it exists
            $sql = "SELECT image FROM contactlist WHERE user_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $stmt->bind_result($oldImage);
            $stmt->fetch();
            $stmt->close();

            if (!empty($oldImage) && file_exists($oldImage)) {
                unlink($oldImage);
            }

            $image = $filePath;
        } else {
            $response = "Error uploading the image";
            echo "<script>alert('$response');</script>";
        }
    }

    // Update the contact information
    if (isset($image)) {
        $sql = "UPDATE contactlist SET firstname=?, lastname=?, contact_number=?, image=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $firstname, $lastname, $cnumber, $image, $user_id);
    } else {
        $sql = "UPDATE contactlist SET firstname=?, lastname=?, contact_number=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $firstname, $lastname, $cnumber, $user_id);
    }

    if ($stmt->execute()) {
        $response = "Contact updated successfully";
        echo "<script>alert('$response');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit();
    } else {
        $response = "Error updating contact: " . $stmt->error;
        echo "<script>alert('$response');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
