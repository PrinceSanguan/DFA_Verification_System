<?php
// Start The Session

session_start();

// Check if the user is logged in as an Admin or Programmer

if (!isset($_SESSION['user']) || ($_SESSION['user'] !== 'admin' && $_SESSION['user'] !== 'programmer')) {
  // Redirect to a different page or display an error message
  echo "Access denied. Only Admin and Programmer users can access this page.";
  exit();
}

// Get the user's role from the session
if (isset($_SESSION['role'])) {
  $userRole = $_SESSION['role'];
} else {
  // Default role if not set (you can customize this as needed)
  $userRole = "Unknown";
}

// Connect to the database
require 'config.php';

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define a SQL query to select all data from your table
$sql = "SELECT * FROM scanned_data";

// Execute the SQL query
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Query failed: " . $conn->error);
}

// Check if the button is clicked
if (isset($_POST['delete_button'])) {
  // Define the table name
  $tableName = "scanned_data"; // Replace with your table name

  // Delete all data from the table
  $deleteQuery = "DELETE FROM $tableName";
  if ($conn->query($deleteQuery) === true) {
      $alertMessage = "All data from tools is succesfully deleted.";
  } else {
      $alertMessage = "Error deleting data: " . $conn->error;
  }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Display Data</title>
  <style>

/* This style is for Body */
    
* {
    text-decoration: none;
    padding: 0;
    margin: 0;
    list-style: none;
  }

  body {
    background-color: #e0e0e0; /* Light gray background */
    height: 100vh;
    margin: 0;
  }

  /* This Style is for the Header */

  .container2 {
    width: 100px;
    height: 100px;
    position: absolute;
    top: 10px;
    left: 10px;
    display: block;
  }

  li {
    color: white;
  }

  a {
    text-decoration: none;
    font-size: 20px;
    display: inline;
    font-weight: bolder;
    color: blue;
  }

  .navlist {
    display: flex;
  }

  .navlist a {
    display: inline-block;
    color: var(--text-color);
    padding: 10px 51px;
    margin-top: 118px;
    animation: slideAnimation 1s ease forwards;
    animation-delay: calc(0.3s * var(--i));
  }

  .navlist a:hover {
    color: var(--hover-color);
    text-shadow: 0 0 10px rgba(18, 247, 255, 0.6),
      0 0 20px rgba(18, 247, 255, 0.6), 0 0 30px rgba(18, 247, 255, 0.6),
      0 0 40px rgba(18, 247, 255, 0.6), 0 0 70px rgba(18, 247, 255, 0.6),
      0 0 80px rgba(18, 247, 255, 0.6), 0 0 100px rgba(18, 247, 255, 0.6),
      0 0 150px rgba(18, 247, 255, 0.6);
  }

  .navlist a.active {
    color: var(--hover-color);
  }

  header {
    top: 0;
    left: 0;
    z-index: 1000;
    border-bottom: 1px solid transparent;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 65%;
    background-color: #555;
    padding-bottom: 50px;
  }

  /* Style for Banner */

  .banner {
    background-color: #2b78e4;
    width: 130%;
  }

  .image-banner {
    margin-left: 130px;
    vertical-align: middle;
    margin-top: 10px;
    margin-bottom: 10px;
    border: 0;
    max-width: 100%;
  }

  /* This style is for the Table */

  .container {
    text-align: center;
    margin-top: 20px;
  }

  table{
    margin-top: 10px;
    width: 130%;
  }

/* Styles for the floating element */
.floating-element {
      position: fixed;
      background-color: #007bff; /* Background color */
      color: #fff; /* Text color */
      padding: 10px; /* Padding around the content */
      border-radius: 5px; /* Rounded corners */
      top: 20px; /* Adjust the top position as needed */
      right: 20px; /* Adjust the right position as needed */
      z-index: 999; /* Ensure it's above other elements */
    }

    </style>
</head>
<body>
<div class="banner">
    <img class="image-banner" src="img/banner.png" alt="broken-image">
  </div>

  <header>
    <div class="container2">
        <ul class="navlist">
          <li><a href="frontpage.php">Insert</a></li>
          <li><a href="search.php">Search</a></li>
          <li><a href="update.php">Update</a></li>
          <li><a href="monitoring.php">Monitoring</a></li>
          <li><a href="reports.php">Reports</a></li>
          <li><a href="tools.php">Tools</a></li>
          <li><a href="logout.php">LogOut</a></li>
        </ul>
    </div>
  </header>

  <!-- Display the user role as a floating element -->
  <div class="floating-element">
    <p>Welcome, <?php echo $userRole ?></p>
  </div>

  <div class="container">
    <h1>Monitoring Page</h1>
   <!-- <form class="form_delete" method="post">
        
        <button class="buttonDelete" type="submit" name="delete_button">Delete All Data</button>
    </form>-->

    <!-- Display the data in an HTML table -->
    <table border='1'>
        <tr>
            <th>Appointment Code</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Gender Name</th>
            <th>Birth Date</th>
            <th>Scan Date Time</th>
            <th>Processor</th>
            <th>Transaction Type</th>
            <th>Type of Applicant</th>
            <th>Schedule</th>
            <th>Type of Form</th>
            <th>Site</th>
            <th>Approved/Disapproved</th>
            <th>Scan Processor</th>
            <!-- Add more columns as needed -->
        </tr>
        <?php
        // Loop through the data and display it in the table
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['appointmentCode'] . "</td>";
            echo "<td>" . $row['lastName'] . "</td>";
            echo "<td>" . $row['firstName'] . "</td>";
            echo "<td>" . $row['middleName'] . "</td>";
            echo "<td>" . $row['gender'] . "</td>";
            echo "<td>" . $row['birthDate'] . "</td>";
            echo "<td>" . $row['scan_datetime'] . "</td>";
            echo "<td>" . $row['processor'] . "</td>";
            echo "<td>" . $row['transactionType'] . "</td>";
            echo "<td>" . $row['typeOfApplicant'] . "</td>";
            echo "<td>" . $row['schedule'] . "</td>";
            echo "<td>" . $row['typeOfForm'] . "</td>";
            echo "<td>" . $row['site'] . "</td>";
            echo "<td>" . $row['approved'] . "</td>";
            echo "<td>" . $row['scan_processor'] . "</td>";
            // Add more columns as needed
            echo "</tr>";
        }
        ?>
    </table>
    </div>

   

    <?php
    // Close the database connection
    $conn->close();
    ?>

<script>
        var alertMessage = "<?php echo $alertMessage; ?>";
        if (alertMessage) {
            alert(alertMessage);
        }
    </script>
</body>
</html>

