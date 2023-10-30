<?php

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
  /* This style is for the Table */

  .container {
    text-align: center;
    margin-top: 30px;
  }

  table {
    width: 30%;
    border-collapse: collapse;
    margin-top: 10px;
  }

  td {
    padding: 7px;
    text-align: center;
    border-bottom: 6px solid #f2f2f2;
    font-size: 30px;
  }

  th {
    background-color: #f2f2f2;
    text-align: lcenter;
    font-size: 30px;
  }


h1 {
  text-align: center;
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
  <h1>Report Page</h1>
  <form action="reports.php" method="post" onsubmit="return confirmGenerate()">
    <label for="selectedDate">Select Date:</label>
    <input type="date" id="selectedDate" name="selectedDate" required>

    <button type="submit" name="generateReport">Generate Report</button>
</form>

    <?php
  // Connect to the database
require 'config.php';

// Initialize counters
$boysCount = 0;
$girlsCount = 0;
$ageGroups = [
    '0-15' => 0,
    '16-30' => 0,
    '31-45' => 0,
    '46-60' => 0,
    '61+' => 0,
];
$processorCounts = [];
$typeOfApplicantCounts = [];
$transactionTypeCounts = [];

// Check if the form is submitted
if (isset($_POST['generateReport'])) {
    // Get the selected date
    $selectedDate = $_POST['selectedDate'];

    // Fetch data from the database for the selected date
    $sql = "SELECT gender, birthDate, processor, typeOfApplicant, transactionType FROM scanned_data WHERE DATE(scan_processor) = '$selectedDate'";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Query failed: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        // Calculate age
        $birthdate = $row['birthDate'];
        $age = date_diff(date_create($birthdate), date_create('today'))->y;

        // Count boys and girls
        if ($row['gender'] === 'M') {
            $boysCount++;
        } elseif ($row['gender'] === 'F') {
            $girlsCount++;
        }

        // Categorize age groups
        if ($age <= 15) {
            $ageGroups['0-15']++;
        } elseif ($age <= 30) {
            $ageGroups['16-30']++;
        } elseif ($age <= 45) {
            $ageGroups['31-45']++;
        } elseif ($age <= 60) {
            $ageGroups['46-60']++;
        } else {
            $ageGroups['61+']++;
        }

        // Count records based on processor
        if (!isset($processorCounts[$row['processor']])) {
            $processorCounts[$row['processor']] = 1;
        } else {
            $processorCounts[$row['processor']]++;
        }

        // Count records based on type of applicant
        if (!isset($typeOfApplicantCounts[$row['typeOfApplicant']])) {
            $typeOfApplicantCounts[$row['typeOfApplicant']] = 1;
        } else {
            $typeOfApplicantCounts[$row['typeOfApplicant']]++;
        }

        // Count records based on transaction type
        if (!isset($transactionTypeCounts[$row['transactionType']])) {
            $transactionTypeCounts[$row['transactionType']] = 1;
        } else {
            $transactionTypeCounts[$row['transactionType']]++;
        }
    }
}

// Close the database connection
$conn->close();
   ?>
   
   <!-- Display the counts in your HTML -->
   <div class="container">
       <h1>Report Page</h1>
       <table>
           <tr>
               <th>Category</th>
               <th>Count</th>
           </tr>
           <tr>
               <td>Total Boys</td>
               <td><?php echo $boysCount; ?></td>
           </tr>
           <tr>
               <td>Total Girls</td>
               <td><?php echo $girlsCount; ?></td>
           </tr>
           <?php
           foreach ($ageGroups as $group => $count) {
               echo "<tr>";
               echo "<td>Total $group years old</td>";
               echo "<td>$count</td>";
               echo "</tr>";
           }
           ?>
       </table>
       <table>
           <tr>
               <th>Processor</th>
               <th>Count</th>
           </tr>
           <?php
           foreach ($processorCounts as $processor => $count) {
               echo "<tr>";
               echo "<td>$processor</td>";
               echo "<td>$count</td>";
               echo "</tr>";
           }
           ?>
       </table>
       <table>
           <tr>
               <th>Type of Applicant</th>
               <th>Count</th>
           </tr>
           <?php
           foreach ($typeOfApplicantCounts as $typeOfApplicant => $count) {
               echo "<tr>";
               echo "<td>$typeOfApplicant</td>";
               echo "<td>$count</td>";
               echo "</tr>";
           }
           ?>
       </table>
       <table>
           <tr>
               <th>Transaction Type</th>
               <th>Count</th>
           </tr>
           <?php
           foreach ($transactionTypeCounts as $transactionType => $count) {
               echo "<tr>";
               echo "<td>$transactionType</td>";
               echo "<td>$count</td>";
               echo "</tr>";
           }
           ?>
       </table>
   </div>

   <script>
    function confirmGenerate() {
        var selectedDate = document.getElementById("selectedDate").value;
        return confirm("Generate report for the selected date: " + selectedDate + "?");
    }
</script>
</body>
</html>