<?php
class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "root";
    private $dbname = "phptest";
    private $connection;

    // Constructor to establish the database connection
    public function __construct() {
        $this->connection = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    // Create a new user
    // public function createUser($id, $name, $role) {
    //     $create = "INSERT INTO user (id, name, role) VALUES ('$id','$name', '$role')";

    //     if ($this->connection->query($create) === TRUE) {
    //         return "New User Created Successfully";
    //     } else {
    //         return "Error: " . $create . "<br>" . $this->connection->error;
    //     }
    // }


    //User will Get Created only if Name is Unique
    public function createUser($id, $name, $role) {
        $checkName = "SELECT * FROM user WHERE name='$name'";
        $result = $this->connection->query($checkName);

        if ($result->num_rows > 0) {
            return "Name already exists";
        } else {
            $create = "INSERT INTO user (id, name, role) VALUES ('$id','$name', '$role')";

            if ($this->connection->query($create) === TRUE) {
                return "New User Created Successfully";
            } else {
                return "Error: " . $create . "<br>" . $this->connection->error;
            }
        }
    }

    // Read all users
    public function viewUsers() {
        $view = "SELECT * FROM user";
        $result = $this->connection->query($view);

        if ($result->num_rows > 0) {
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return $users;
        } else {
            return "0 results";
        }
    }

    // Update a user
    // public function updateUser($id, $newName, $newRole) {
    //     $update = "UPDATE user SET name='$newName', role='$newRole' WHERE id=$id";

    //     if ($this->connection->query($update) === TRUE) {
    //         return "User Updated Successfully";
    //     } else {
    //         return "Error Updating User: " . $this->connection->error;
    //     }
    // }


    //User will get updated only if Id is found in database
    public function updateUser($id, $newName, $newRole) {
        $checkId = "SELECT * FROM user WHERE id=$id";
        $result = $this->connection->query($checkId);

        if ($result->num_rows === 0) {
            return "User ID not found";
        } else {
            $update = "UPDATE user SET name='$newName', role='$newRole' WHERE id=$id";

            if ($this->connection->query($update) === TRUE) {
                return "User Updated Successfully";
            } else {
                return "Error Updating User: " . $this->connection->error;
            }
        }
    }


    // Delete a user
    // public function deleteUser($id) {
    //     $delete = "DELETE FROM user WHERE id=$id";

    //     if ($this->connection->query($delete) === TRUE) {
    //         return "User Deleted Successfully";
    //     } else {
    //         return "Error Deleting User: " . $this->connection->error;
    //     }
    // }


    //User will get Deleted only if Id is found in database
    public function deleteUser($id) {
        $checkId = "SELECT * FROM user WHERE id=$id";
        $result = $this->connection->query($checkId);

        if ($result->num_rows === 0) {
            return "User ID not found";
        } else {
            $delete = "DELETE FROM user WHERE id=$id";

            if ($this->connection->query($delete) === TRUE) {
                return "User Deleted Successfully";
            } else {
                return "Error Deleting User: " . $this->connection->error;
            }
        }
    }



    //Filters to fetch User with it's ID, Name and Role
    public function searchEntries($searchTerm) {
        $query = "SELECT * FROM user WHERE id = '$searchTerm' OR name LIKE '%$searchTerm%' OR role LIKE '%$searchTerm%'";
        $result = $this->connection->query($query);

        if ($result->num_rows > 0) {
            $entries = [];
            while ($row = $result->fetch_assoc()) {
                $entries[] = $row;
            }
            return $entries;
        } else {
            return "No entries found matching: $searchTerm";
        }
    }

    // Close the database connection
    public function closeConnection() {
        $this->connection->close();
    }
}

$db = new Database();
$users = $db->viewUsers();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create'])) {
        echo $db->createUser($_POST['id'], $_POST['name'], $_POST['role']);
    } elseif (isset($_POST['update'])) {
        echo $db->updateUser($_POST['id'], $_POST['new_name'], $_POST['new_role']);
    } elseif (isset($_POST['delete'])) {
        echo $db->deleteUser($_POST['id']);
    }elseif (isset($_POST['find'])) {
        $searchResults = $db->searchEntries($_POST['searchTerm']);
    }
}

// $users = $db->viewUsers();
// foreach ($users as $user) {
// }

$db->closeConnection();
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

      table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
        }

        th,
        td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
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


  <h2>Users</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Role</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($users as $user) {
        echo '<tr>';
        echo '<td>' . $user["id"] . '</td>';
        echo '<td>' . $user["name"] . '</td>';
        echo '<td>' . $user["role"] . '</td>';
        echo '</tr>';
      }
      ?>
    </tbody>
  </table>

  
    <h2>Create User</h2>
    <form action="sampleCRUDoops.php" method="POST">
      <label for="id">Id:</label>
      <input type="text" id="id" name="id" required />
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required />
      <label for="role">role:</label>
      <input type="role" id="role" name="role" required />
      <input type="submit" name="create" value="Create" />
    </form>

    <h2>Update User</h2>
    <form action="sampleCRUDoops.php" method="POST">
      <label for="update_id">Id</label>
      <input type="text" id="update_id" name="id" required />
      <label for="new_name">New Name:</label>
      <input type="text" id="new_name" name="new_name" required />
      <label for="new_role">New role:</label>
      <input type="role" id="new_role" name="new_role" required />
      <input type="submit" name="update" value="Update" />
    </form>

    <h2>Delete User</h2>
    <form action="sampleCRUDoops.php" method="POST">
      <label for="delete_id">Id</label>
      <input type="text" id="delete_id" name="id" required />
      <input type="submit" name="delete" value="Delete" />
    </form>

    <h2>Search User</h2>
    <form action="sampleCRUDoops.php" method="POST">
      <label for="searchTerm">Searchby</label>
      <input type="text" id="searchTerm" name="searchTerm" required />
      <input type="submit" name="find" value="Find" />
    </form>

     <?php if (isset($searchResults)) : ?>
        <h2>Search Results</h2>
        <?php
        if (is_array($searchResults) && count($searchResults) > 0) {
            echo '<ul>';
            foreach ($searchResults as $result) {
                echo 'ID: ' . $result['id'] . ', Name: ' . $result['name'] . ', Role: ' . $result['role'];
            }
            echo '</ul>';
        } else {
            echo '<p>No entries found.</p>';
        }
        ?>
    <?php endif; ?>

  </body>
</html>
