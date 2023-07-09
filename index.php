<?php
require('config/conn.php');

$sql = "SELECT * FROM contactlist";
$result = $conn->query($sql);
$rows = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
    <link rel="stylesheet" href="css/custom_style.css">
    <script src="https://kit.fontawesome.com/af9f472305.js" crossorigin="anonymous"></script>
    <title>Contact List</title>

</head>

<body>
    <div class="table-class">
        <h1 class="form-label text-center">My Contacts</h1>
        <table class="table table-responsive text-center">
            <thead>
                <tr>
                    <th scope="col">
                        User ID
                    </th>
                    <th scope="col">
                        Firstname
                    </th>
                    <th scope="col">
                        Lastname
                    </th>
                    <th scope="col">
                        Contact Number
                    </th>
                    <th scope="col">
                        Image
                    </th>
                    <th scope="col">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $row) { ?>
                    <tr class="table-primary" data-contact-id="<?php echo $row['user_id']; ?>">
                        <th scope="row">
                            <?php echo $row['user_id']; ?>
                        </th>
                        <td>
                            <?php echo $row['firstname']; ?>
                        </td>
                        <td>
                            <?php echo $row['lastname']; ?>
                        </td>
                        <td>
                            <?php echo $row['contact_number']; ?>
                        </td>
                        <td><img src="<?php echo $row['image']; ?>" alt="Contact Image"
                                class="img img-thumbnail img-fluid rounded-2"></td>
                        <td>
                            <i class="fa-solid fa-pen-to-square edit-icon" data-bs-toggle="modal"
                                data-bs-target="#modal_sa_akong_beshy" data-contact-id="<?php echo $row['user_id']; ?>"></i>
                            <i class="fa-solid fa-trash" data-bs-toggle="modal" data-bs-target="#delete_modal" data-contact-id="<?php echo $row['user_id']; ?>"></i>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <a href="create.php"><button class="btn btn-primary btn-lg float-end">Add a person</button></a>
        </table>
    </div>

    <!-- Update Contact Modal Start -->
    <div class="modal fade" id="modal_sa_akong_beshy" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog w-75">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update-form" action="update.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="user_id" id="edit-user-id">
                        <div class="form-group">
                            <label for="firstname">Firstname:</label>
                            <input type="text" name="firstname" id="edit-firstname" class="form-control form-control-lg"
                                placeholder="Firstname">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Lastname:</label>
                            <input type="text" name="lastname" id="edit-lastname" class="form-control form-control-lg"
                                placeholder="Lastname">
                        </div>
                        <div class="form-group">
                            <label for="cnumber">Contact Number:</label>
                            <input type="text" name="cnumber" id="edit-cnumber" class="form-control form-control-lg"
                                placeholder="Contact Number">
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <input type="file" name="image" id="edit-image" class="form-control form-control-lg">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Update Contact Modal End -->


<!-- Delete Modal Start -->
<div class="modal" id="delete_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this person from your contact list?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-button">Delete</button>
            </div>
        </div>
    </div>
</div>
<!-- Delete Modal End -->

</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editIcons = document.querySelectorAll('.edit-icon');
        const form = document.getElementById('update-form');
        const userIdField = document.getElementById('edit-user-id');

        editIcons.forEach(function (icon) {
            icon.addEventListener('click', function () {
                const userId = this.getAttribute('data-contact-id');
                const row = document.querySelector(`tr[data-contact-id="${userId}"]`);
                const firstname = row.querySelector('td:nth-child(2)').innerText;
                const lastname = row.querySelector('td:nth-child(3)').innerText;
                const cnumber = row.querySelector('td:nth-child(4)').innerText;
                const imageSrc = row.querySelector('td:nth-child(5) img').src;

                userIdField.value = userId;
                document.getElementById('edit-firstname').value = firstname;
                document.getElementById('edit-lastname').value = lastname;
                document.getElementById('edit-cnumber').value = cnumber;

                document.getElementById('edit-image').value = imageSrc;
            });
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteContactIcons = document.getElementsByClassName('fa-trash');
    const deleteConfirmationModal = document.getElementById('delete_modal');
    const confirmDeleteButton = document.getElementById('confirm-delete-button');

    let userIdToDelete = null;

    for (let i = 0; i < deleteContactIcons.length; i++) {
        const deleteContactIcon = deleteContactIcons[i];
        deleteContactIcon.addEventListener('click', function () {
            userIdToDelete = this.getAttribute('data-contact-id');
        });
    }


    confirmDeleteButton.addEventListener('click', function () {
        if (userIdToDelete) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete.php');
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = xhr.responseText;
                    alert(response);
                    window.location.href = 'index.php';
                } else {
                    alert('Error deleting contact');
                }
            };
            xhr.send('user_id=' + userIdToDelete);
        }
    });

    deleteConfirmationModal.addEventListener('hidden.bs.modal', function () {
        userIdToDelete = null;
    });
});


</script>
