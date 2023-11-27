<?php
//DataBase Connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "phptest";

//Create connection
$connection = new mysqli($servername, $username, $password, $dbname);

//Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

//CreateUser
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    
    $create = "INSERT INTO user (id, name, role) VALUES ('$id','$name', '$role')";
    
    
    if ($connection->query($create) === TRUE) {
        echo "New User Created Successfully";
    } else {
        echo "Error: " . $create . "<br>" . $connection->error;
    }
}

//ViewUsers
$view = "SELECT * FROM user";
$result = $connection->query($view);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " - Name: " . $row["name"] . " - Role: " . $row["role"] . "<br>";
    }
} else {
    echo "0 results";
}

//UpdateUser
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $newName = $_POST['new_name'];
    $newrole = $_POST['new_role'];
    
    $update = "UPDATE user SET name='$newName', role='$newrole' WHERE id=$id";
    
    if ($connection->query($update) === TRUE) {
        echo "User Updated Successfully";
    } else {
        echo "Error Updating User: " . $connection->error;
    }
}

//DeleteUser
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = $_POST['id'];
    
    $delete = "DELETE FROM user WHERE id=$id";
    
    if ($connection->query($delete) === TRUE) {
        echo "User Deleted Successfully";
    } else {
        echo "Error Deleting User: " . $connection->error;
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>CRUD Operations</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 20px;
      }

      form {
        margin-bottom: 20px;
        border: 1px solid #ccc;
        padding: 20px;
        width: 300px;
      }

      label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
      }

      input[type="text"],
      input[type="role"],
      input[type="submit"] {
        width: 100%;
        margin-bottom: 10px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
      }

      input[type="submit"] {
        background-color: #4caf50;
        color: white;
        cursor: pointer;
      }

      input[type="submit"]:hover {
        background-color: #45a049;
      }
    </style>
  </head>
  <body>
    <h2>Create User</h2>
    <form action="sampleCRUD.php" method="POST">
      <label for="id">Id:</label>
      <input type="text" id="id" name="id" required />
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required />
      <label for="role">role:</label>
      <input type="role" id="role" name="role" required />
      <input type="submit" name="create" value="Create" />
    </form>

    <h2>Update User</h2>
    <form action="sampleCRUD.php" method="POST">
      <label for="update_id">Id</label>
      <input type="text" id="update_id" name="id" required />
      <label for="new_name">New Name:</label>
      <input type="text" id="new_name" name="new_name" required />
      <label for="new_role">New role:</label>
      <input type="role" id="new_role" name="new_role" required />
      <input type="submit" name="update" value="Update" />
    </form>

    <h2>Delete User</h2>
    <form action="sampleCRUD.php" method="POST">
      <label for="delete_id">Id</label>
      <input type="text" id="delete_id" name="id" required />
      <input type="submit" name="delete" value="Delete" />
    </form>
  </body>
</html>