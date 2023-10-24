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
  // Redirect to search.php after updating the record
  echo '<script>window.location = "search.php";</script>';
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
  <link rel="stylesheet" href="./css/update.css">
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
    <!-- Add values to the hidden fields -->
    <input type="hidden" id="updateAppointmentCode" name="updateAppointmentCode" value="<?php echo $updateAppointmentCode; ?>">
    <input type="hidden" id="updateLastName" name="updateLastName" value="<?php echo $updateLastName; ?>">
    <input type="hidden" id="updateFirstName" name="updateFirstName" value="<?php echo $updateFirstName; ?>">
    <input type="hidden" id="updateMiddleName" name="updateMiddleName" value="<?php echo $updateMiddleName; ?>">
    <label for="updateGender">Gender:</label>
    <input type="text" id="updateGender" name="updateGender" value="<?php echo $updateGender; ?>" autocomplete="off">
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
  

    <!-- Modify the Submit button -->
    <button type="submit" name="updateSubmit">Submit</button>

    <button type="button" onclick="hideUpdateRecord()">Cancel</button>
  </form>
</div>
</body>
</html>
