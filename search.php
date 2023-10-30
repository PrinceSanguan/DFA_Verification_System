
<?php 

// Start The Session

session_start();

// Check if the user is DFA Employee

if (!isset($_SESSION['user']) || ($_SESSION['user'] !== 'processor' && $_SESSION['user'] !== 'admin' && $_SESSION['user'] !== 'programmer')) {
  // Redirect to a different page or display an error message
  echo "Access denied. Only DFA Employee can access here!.";
  exit();
}

// Get the user's role from the session
if (isset($_SESSION['role'])) {
  $userRole = $_SESSION['role'];
} else {
  // Default role if not set (you can customize this as needed)
  $userRole = "Unknown";
}


// Include the database connection
require 'config.php';

// Initialize variables for input fields
$appointmentCode = $lastName = $firstName = "";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $appointmentCode = $_POST['appointmentCode'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];

    // Check if at least one input field is filled
    if (empty($appointmentCode) && empty($lastName) && empty($firstName)) {
        // Display an alert message using JavaScript
        echo "<script>alert('Please input at least one parameter.');</script>";
    } else {
        // Build the SQL query dynamically based on input fields
        $sql = "SELECT * FROM scanned_data WHERE 1=1";

        if (!empty($appointmentCode)) {
            // Check if the input has exactly 6 digits (you can add more validation if needed)
            if (preg_match('/^\d{6}$/', $appointmentCode)) {
                // Build the SQL query to search by the last 6 digits
                $sql .= " AND RIGHT(appointmentCode, 6) = '$appointmentCode'";
            } else {
                $sql .= " AND 0"; // This will ensure no results are returned for an invalid input
            }
        }

        if (!empty($lastName)) {
            $sql .= " AND lastName = '$lastName'";
        }

        if (!empty($firstName)) {
            $sql .= " AND firstName = '$firstName'";
        }

        $result = $conn->query($sql);

      }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="./img/DFA.png">
  <title>NCR EAST VERIFICATION</title>
</head>
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
    padding: 8px 50%;
    background-color: #555;
    padding-bottom: 50px;
  }

  /* Style for Banner */

  .banner {
    background-color: #2b78e4;
  }

  .image-banner {
    margin-left: 130px;
    vertical-align: middle;
    margin-top: 10px;
    margin-bottom: 10px;
    border: 0;
    max-width: 100%;
  }

  /* Styles for the floating element */
  .floating-element {
    position: fixed;
    background-color: #007bff; /* Background color */
    color: #fff; /* Text color */
    padding: 10px; /* Padding around the content */
    border-radius: 5px; /* Rounded corners */
    top: 130px; /* Adjust the top position as needed */
    right: 20px; /* Adjust the right position as needed */
    z-index: 999; /* Ensure it's above other elements */
  }

  /* This Style is for the form and table */
    
  .container {
            max-width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            width: 20%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px inset #dedede;
            border-radius: 5px;
            text-align: center;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Cancel button */
        button[type="button"] {
            background-color: #ff0000; /* Red background color */
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="button"]:hover {
            background-color: lightcoral; /* Lighter red on hover */
        }

        /* Initially hide the update record section */
        #updateRecord {
        display: none;
        }

</style>
<body>
<div class="banner">
    <img class="image-banner" src="img/banner.png" alt="broken-image">
  </div>

  <header>
    <div class="container2">
        <ul class="navlist">
          <li><a href="frontpage.php">Insert</a></li>
          <li><a href="search.php">Search</a></li>
          <li><a href="search.php">Update</a></li>
          <li><a href="monitoring.php">Monitoring</a></li>
          <li><a href="reports.php">Reports</a></li>
          <li><a href="tools.php">Tools</a></li>
          <li><a href="logout.php">LogOut</a></li>
        </ul>
    </div>
  </header>

 <!-- Container for form and Table -->
 <div class="container">
        <form action="search.php" method="post">
            <label for="appointmentCode">Last 6 ARN</label>
            <input type="text" id="appointmentCode" name="appointmentCode" autocomplete="off" "><br>
            
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" autocomplete="off" ><br>
            
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" autocomplete="off" ><br>
            
            <button type="submit" name="submit" value="search">Search</button>
        </form>

        <!-- The Table -->
        <?php
        if (isset($result)) {
            if ($result->num_rows > 0) {
                echo "<h3>Data matching your search:</h3>";
                echo "<table border='1'>
                        <tr>
                            <th>Appointment Code</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Gender</th>
                            <th>Birth Date</th>
                            <th>Scan Date</th>
                            <th>Processor</th>
                        </tr>";

                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                  <td>" . $row['appointmentCode'] . "</td>
                  <td>" . $row['lastName'] . "</td>
                  <td>" . $row['firstName'] . "</td>
                  <td>" . $row['middleName'] . "</td>
                  <td>" . $row['gender'] . "</td>
                  <td>" . $row['birthDate'] . "</td>
                  <td>" . $row['scan_datetime'] . "</td>
                  <td>" . $row['processor'] . "</td>
                  <td><a href='update.php?appointmentCode=" . $row['appointmentCode'] . "&lastName=" . $row['lastName'] . "&firstName=" . $row['firstName'] . "&middleName=" . $row['middleName'] . "&gender=" . $row['gender'] . "'>Update</a></td>
              </tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No matching records found in the database.</p>";
            }
        }
        ?>
    </div>


<!-- Display the user role as a floating element -->
<div class="floating-element">
    <p>Welcome, <?php echo $userRole ?></p>
</div>



<script>



</script>
</body>
</html>