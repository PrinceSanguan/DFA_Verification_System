<?php
session_start();

// Check if the user is logged in as a Programmer
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'programmer') {
  // Redirect to a different page or display an error message
  echo "Access denied. Only Programmer users can access this page.";
  exit();
}

// Get the user's role from the session
if (isset($_SESSION['role'])) {
  $userRole = $_SESSION['role'];
} else {
  // Default role if not set (you can customize this as needed)
  $userRole = "Unknown";
}

// Check if a appointment code was scanned and submitted
if (isset($_POST['appointmentCode'])) {
    // Store the scanned appointment code in a session variable
    $_SESSION['scanned_appointmentCode'] = $_POST['appointmentCode'];

    // Connect to the database
    require 'config.php';

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

     // Prepare a SQL statement to check if the scanned data exists in table 1
    $checkSql = "SELECT * FROM dfa_data1 WHERE appointmentCode = ?";
    $checkStmt = $conn->prepare($checkSql);

    if ($checkStmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind the parameter for the check statement
    $checkStmt->bind_param("s", $_SESSION['scanned_appointmentCode']);

 // Execute the check statement
 $checkStmt->execute();

// Fetch the result
$result = $checkStmt->get_result();

// Check if the scanned data exists in table 1
if ($result->num_rows > 0) {
    // Fetch the data from table 1
    $row = $result->fetch_assoc();
    $lastName = $row['lastName'];
    $firstName = $row['firstName'];
    $middleName = $row['middleName'];
    $gender = $row['gender'];
    $birthDate = $row['birthDate'];

     // Check if there is an entry for the same barcode on the same day
    $existingEntrySql = "SELECT * FROM scanned_data WHERE appointmentCode = ? AND DATE(scan_datetime) = CURDATE()";
    $existingEntryStmt = $conn->prepare($existingEntrySql);
    $existingEntryStmt->bind_param("s", $_SESSION['scanned_appointmentCode']);
    $existingEntryStmt->execute();
    $existingEntryResult = $existingEntryStmt->get_result();

  // Data exists in table 1, so insert it into table 2
  $insertSql = "INSERT INTO scanned_data (appointmentCode, lastName, firstName, middleName, gender, birthDate, scan_datetime) VALUES (?, '$lastName', '$firstName', '$middleName', '$gender', '$birthDate', NOW())";
  $insertStmt = $conn->prepare($insertSql);

  if ($insertStmt === false) {
      die("Prepare failed: " . $conn->error);
  }

  // Bind the parameter for the insert statement
  $insertStmt->bind_param("s", $_SESSION['scanned_appointmentCode']);

  // Execute the insert statement
  if ($insertStmt->execute()) {
    echo "success";
  } else {
    echo "Failed to store data in table 2: " . $insertStmt->error;
  }

  // Close the insert statement
  $insertStmt->close();
} else {
  echo "Data not found in table 1.";
}

// Close the check statement, the result, and the connection
$checkStmt->close();
$result->close();
$conn->close();
}

// Function to calculate the next queue number
function getNextQueueNumber($conn) {
  // Calculate the age of the applicant based on their birthDate
  // Replace this with your actual logic to calculate age from the birthDate
  $applicantAge = calculateAge($_SESSION['scanned_appointmentCode']);

  // Set the default queue number for regular applicants
  $queueNumber = 3001;

  // Set a special queue number for applicants aged 7 or below or 60 and above
  if ($applicantAge <= 7 || $applicantAge >= 60) {
      $queueNumber = 1001;
  }

  // Fetch the maximum queue number from the scanned_data table
  $sql = "SELECT MAX(queueNumber) as maxQueueNumber FROM scanned_data";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $maxQueueNumber = $row['maxQueueNumber'];

  // Increment the queue number
  $queueNumber += $maxQueueNumber;

  return $queueNumber;
}

// Function to calculate age from birthDate
function calculateAge($birthDate) {
  // Replace this with your logic to calculate age from birthDate
  // For example, you can use DateTime functions
  $birthDate = new DateTime($birthDate);
  $currentDate = new DateTime();
  $interval = $currentDate->diff($birthDate);
  return $interval->y;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="./img/DFA.png">
  <title>NCR EAST VERIFICATION</title>
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

/* This Style is for the form */
    
  form {
    padding-top: 50px;
  }

  .container {
    text-align: center;
    margin-top: 40px;
  }

  input {
    padding: 20px 70px; /* Adjust the padding as needed */
    margin-bottom: 10px;
    border: 2px solid black;
    border-radius: 5px;
    font-size: 20px;
    text-align: center;
    border-width: 5px;
  }

  label {
    display: block;
    font-weight: bold;
    font-size: 40px;
  }

  button {
    padding: 15px 25px; /* Adjust the padding as needed to make it larger */
    font-size: 24px; /* Increase font size if desired */
    border: none;
    background-color: #007bff; /* Change button color as desired */
    color: #fff; /* Text color */
    border-radius: 5px;
    cursor: pointer;
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
    position: absolute;
    background-color: #007bff; /* Background color */
    color: #fff; /* Text color */
    padding: 10px; /* Padding around the content */
    border-radius: 5px; /* Rounded corners */
    top: 130px; /* Adjust the top position as needed */
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
          <li><a href="frontpage.php">Update</a></li>
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
    <img src="img/DFA2.png" alt="broken-image" style="max-width: 100%; height: auto;">
    <form id="myForm" action="frontpage.php" method="post">
     <input type="text" id="appointmentCode" name="appointmentCode" onkeyup="checkAppointmentCodeLength()" required><br>
      <label id="appNumbers">Application Number</label>
      <button type="submit">Submit</button>
    </form>
  </div>
  <script>


    // JavaScript to change the text color every 2 seconds
const appNumber = document.getElementById("appNumbers"); // Updated variable name
const colors = ["red", "blue", "orange", "green", "yellow"];
let colorIndex = 0;

function changeColor() {
  appNumber.style.color = colors[colorIndex]; // Updated variable name
  colorIndex = (colorIndex + 1) % colors.length;
}

setInterval(changeColor, 2000); // Change color every 2 seconds

// Script for appointmentCode input
function checkAppointmentCodeLength() {
    // Get the value of the appointment code input
    var appointmentCodeInput = document.getElementById('appointmentCode').value;

    // Define the desired code length
    var desiredLength = 14;

    // Check if the input has the desired length
    if (appointmentCodeInput.length === desiredLength) {

        // Automatically submit the form
        document.getElementById('myForm').submit();
    }
}

  </script>
  

</body>
</html>