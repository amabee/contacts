<?php
require('config/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        $sql = "DELETE FROM contactlist WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_id);

        if ($stmt->execute()) {
            $response = "Contact deleted successfully";
            echo $response;
        } else {
            $response = "Error deleting contact: " . $stmt->error;
            echo $response;
        }

        $stmt->close();
    } else {
        $response = "Invalid request";
        echo $response;
    }
}

$conn->close();
?>
