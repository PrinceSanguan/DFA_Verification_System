<?php

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
$updateAppointmentCode = $updateLastName = $updateFirstName = $updateMiddleName = $updateGender = "";

// Check if the "Update" link was clicked in the search results
if (isset($_GET['appointmentCode']) && isset($_GET['lastName']) && isset($_GET['firstName']) && isset($_GET['middleName']) && isset($_GET['gender'])) {
  // Retrieve the parameters from the URL
  $updateAppointmentCode = $_GET['appointmentCode'];
  $updateLastName = $_GET['lastName'];
  $updateFirstName = $_GET['firstName'];
  $updateMiddleName = $_GET['middleName'];
  $updateGender = $_GET['gender'];
}


// Check if the update form is submitted
if (isset($_POST['updateSubmit'])) {
  // Get the values from the form
  $updateAppointmentCode = $_POST['updateAppointmentCode'];
  $updateGender = $_POST['updateGender'];
  $updateProcessor = $_POST['updateProcessor'];
  $updateNewRenewal = $_POST['updateNewRenewal'];
  $updateTypeOfApplicant = $_POST['updateTypeOfApplicant'];
  $updateSchedule = $_POST['updateSchedule'];
  $updateTypeOfForm = $_POST['updateTypeOfForm'];
  $updateSite = $_POST['updateSite'];
  $updateApproved = $_POST['updateApproved'];

  // Perform the database update with NOW()
  $sql = "UPDATE scanned_data SET gender = '$updateGender', processor = '$updateProcessor', transactionType = '$updateNewRenewal',
   typeOfApplicant = '$updateTypeOfApplicant', schedule = '$updateSchedule', typeOfForm = '$updateTypeOfForm', site = '$updateSite', approved = '$updateApproved', scan_processor = NOW() WHERE appointmentCode = '$updateAppointmentCode'";

  if ($conn->query($sql) === TRUE) {
      echo '<script>alert("Record updated successfully.");</script>';
  } else {
      echo "Error updating record: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report Page</title>
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

   <!-- Display the details on the update page -->
   <h2>Update Record</h2>
  <p>Appointment Code: <?php echo $updateAppointmentCode; ?></p>
  <p>Last Name: <?php echo $updateLastName; ?></p>
  <p>First Name: <?php echo $updateFirstName; ?></p>
  <p>Middle Name: <?php echo $updateMiddleName; ?></p>
  <p>Gender: <?php echo $updateGender; ?></p>

  <!-- Update Record Section -->
<div id="updateRecord">
  <h2>Update Record</h2>
  <form action="update.php" method="post">
    <input type="hidden" id="updateAppointmentCode" name="updateAppointmentCode" value="">
    <label for="updateLastName">Last Name:</label>
    <input type="text" id="updateLastName" name="updateLastName" autocomplete="off" readonly> <!-- Lock this field -->
    <label for="updateFirstName">First Name:</label>
    <input type="text" id="updateFirstName" name="updateFirstName" autocomplete="off" readonly> <!-- Lock this field -->
    <label for="updateMiddleName">Middle Name:</label>
    <input type="text" id="updateMiddleName" name="updateMiddleName" autocomplete="off" readonly> <!-- Lock this field -->
    <label for="updateGender">Gender:</label>
    <input type="text" id="updateGender" name="updateGender" autocomplete="off">
    <label for="updateProcessor">Processor:</label>
    <select id="updateProcessor" name="updateProcessor">
      <option value="Chito">Chito</option>
      <option value="Mark">Mark</option>
      <option value="Richmond">Richmond</option>
      <option value="Kenneth">Kenneth</option>
    </select>

    <label for="updateNewRenewal">Transaction Type:</label>
    <select id="updateNewRenewal" name="updateNewRenewal">
      <option value="New">New</option>
      <option value="Renewal">Renewal</option>
    </select>

    <label for="updateTypeOfApplicant">Type of Applicant:</label>
    <select id="updateTypeOfApplicant" name="updateTypeOfApplicant">
      <option value="CL">CL</option>
      <option value="Regular">Regular</option>
      <option value="Returning">Returning</option>
    </select>

    <label for="updateSchedule">Schedule:</label>
    <select id="updateSchedule" name="updateSchedule">
      <option value="Today">Today</option>
      <option value="Resched">Resched</option>
    </select>

    <label for="updateTypeOfForm">Type of Form:</label>
    <select id="updateTypeOfForm" name="updateTypeOfForm">
      <option value="OAS">OAS</option>
      <option value="Manual">Manual</option>
    </select>

    <label for="updateSite">Site:</label>
    <select id="updateSite" name="updateSite">
      <option value="EAST">EAST</option>
      <option value="NORTH">NORTH</option>
    </select>

    <label for="updateApproved">Approved/Disapproved:</label>
    <select id="updateApproved" name="updateApproved">
      <option value="Approved">Approved</option>
      <option value="Disapproved">Disapproved</option>
    </select>

  </form>

    <!-- Rest of your form fields -->

    <!-- Modify the Submit button -->
    <button type="submit" name="updateSubmit">Submit</button>

    <button type="button" onclick="hideUpdateRecord()">Cancel</button>
  </form>
</div>
</div>

<!-- Include this JavaScript code within your <script> tags in update.php -->
  <script>



  // Function to show the "Update Record" section and populate its fields
  function showUpdateRecord(appointmentCode, lastName, firstName, middleName, gender) {
    // Populate the fields
    document.getElementById("updateAppointmentCode").value = appointmentCode;
    document.getElementById("updateLastName").value = lastName;
    document.getElementById("updateFirstName").value = firstName;
    document.getElementById("updateMiddleName").value = middleName;
    document.getElementById("updateGender").value = gender;

    // Show the "Update Record" section
    document.getElementById("updateRecord").style.display = "block";
  }

  // Function to hide the "Update Record" section
  function hideUpdateRecord() {
    document.getElementById("updateRecord").style.display = "none";
  }

  // Add an event listener to hide the "Update Record" section when the page loads
  window.addEventListener("load", function () {
    hideUpdateRecord();
  });

  </script>
</body>
</html>