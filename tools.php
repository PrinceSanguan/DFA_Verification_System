<?php 

session_start();

// Check if the user is logged in as a programmer
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

// Database connection
require 'config.php'; 

// Check if the button is clicked
if (isset($_POST['delete_button'])) {
  // Define the table name
  $tableName = "dfa_data1"; // Replace with your table name

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
<html lang="en" dir="ltr">
	<head> 
		<meta charset="utf-8">
		<title>Import Excel To MySQL</title>
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

/* Style for Table */

  table {
  width: 200%;
  }
  
/* Style for Title */

  h1 {
  text-align: center;
  }

/* Style for Form */

  form {
  text-align: center;
  }

/* Style for Button */

  .form_delete {
  margin-top: 20px;
  }

  .buttonDelete {
  font-size: 20px;
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

    <h1>Import Data</h1>
		<form class="" action="tools.php" method="post" enctype="multipart/form-data">
			<input type="file" name="excel" required value="">
			<button type="submit" name="import">Import</button>
		</form>

    <form class="form_delete" method="post">
        <!-- Create a button to delete data -->
        <button class="buttonDelete" type="submit" name="delete_button">Delete All Data</button>
    </form>
		<hr>
		<table border = 1>
			<tr>
      <td>#</td>
				<td>Appointment Code</td>
				<td>LOL</td>
				<td>Last Name</td>
        <td>First Name</td>
				<td>Middle Name</td>
				<td>Old Passport Number</td>
        <td>Gender</td>
				<td>Civil Status</td>
				<td>Birth Date</td>
        <td>Birth Place</td>
				<td>Birth Country</td>
				<td>Email</td>
        <td>Mobile Number</td>
        <td>address</td>
				<td>City</td>
        <td>Province</td>
        <td>Time</td>
        <td>Receipt Number</td>
        <td>Reference Number</td>
        <td>Agency</td>
        <td>Status</td>
        <td>Site Name</td>
        <td>Created Data</td>
        <td>Last Update Date</td>
        <td>Courier</td>
			</tr>
			<?php
			$i = 1;
			$rows = mysqli_query($conn, "SELECT *, address FROM dfa_data1");
			foreach($rows as $row) :
			?>
			<tr>
      <td> <?php echo $i++ ?> </td>
				<td> <?php echo $row["appointmentCode"]; ?> </td>
				<td> <?php echo $row["lol"]; ?> </td>
				<td> <?php echo $row["lastName"]; ?> </td>
        <td> <?php echo $row["firstName"]; ?> </td>
				<td> <?php echo $row["middleName"]; ?> </td>
				<td> <?php echo $row["oldPassportNumber"]; ?> </td>
        <td> <?php echo $row["gender"]; ?> </td>
				<td> <?php echo $row["civilStatus"]; ?> </td>
				<td> <?php echo $row["birthDate"]; ?> </td>
        <td> <?php echo $row["birthPlace"]; ?> </td>
        <td> <?php echo $row["birthCountry"]; ?> </td>
        <td> <?php echo $row["email"]; ?> </td>
        <td> <?php echo $row["mobileNumber"]; ?> </td>
        <td> <?php echo $row["address"]; ?> </td>
        <td> <?php echo $row["city"]; ?> </td>
        <td> <?php echo $row["province"]; ?> </td>
        <td> <?php echo $row["schedule"]; ?> </td>
        <td> <?php echo $row["time"]; ?> </td>
        <td> <?php echo $row["receiptNumber"]; ?> </td>
        <td> <?php echo $row["referenceNumber"]; ?> </td>
        <td> <?php echo $row["agency"]; ?> </td>
        <td> <?php echo $row["status"]; ?> </td>
        <td> <?php echo $row["siteName"]; ?> </td>
        <td> <?php echo $row["createdData"]; ?> </td>
        <td> <?php echo $row["lastUpdateDate"]; ?> </td>
        <td> <?php echo $row["courier"]; ?> </td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php
		if(isset($_POST["import"])){
			$fileName = $_FILES["excel"]["name"];
			$fileExtension = explode('.', $fileName);
      $fileExtension = strtolower(end($fileExtension));
			$newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;

			$targetDirectory = "uploads/" . $newFileName;
			move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

			require 'excelReader/excel_reader2.php';
			require 'excelReader/SpreadsheetReader.php';

			$reader = new SpreadsheetReader($targetDirectory);
			foreach($reader as $key => $row){
				$appointmentCode = $row[0];
				$lol = $row[1];
				$lastName = $row[2];
        $firstName = $row[3];
        $middleName = $row[4];
        $oldPassportNumber = $row[5];
        $gender = $row[6];
        $civilStatus = $row[7];
        $birthDate = $row[8];
        $birthPlace = $row[9];
        $birthCountry = $row[10];
        $email = $row[11];
        $mobileNumber = $row[12];
        $address = $row[13];
        $city = $row[14];
        $province = $row[15];
        $schedule = $row[16];
        $time = $row[17];
        $receiptNumber = $row[18];
        $referenceNumber = $row[19];
        $agency = $row[20];
        $status = $row[21];
        $siteName = $row[22];
        $createdData = $row[23];
        $lastUpdateDate = $row[24];
        $courier = $row[25];


				if (mysqli_query($conn, "INSERT INTO dfa_data1 (appointmentCode, lol, lastName, firstName, middleName, oldPassportNumber, gender, civilStatus, birthDate, birthPlace, birthCountry, email, mobileNumber, address, city, province, schedule, time,
         receiptNumber, referenceNumber, agency, status, sitename, createdData,
          lastUpdateDate, courier) VALUES ('$appointmentCode', '$lol', '$lastName', '$firstName', '$middleName', '$oldPassportNumber', '$gender', '$civilStatus', '$birthDate',
           '$birthPlace', '$birthCountry', '$email', '$mobileNumber', '$address', '$city', '$province', '$schedule', '$time',
            '$receiptNumber', '$referenceNumber', '$agency', '$status', '$siteName', '$createdData', '$lastUpdateDate', '$courier')")) {
          echo "Data inserted successfully";
      } else {
          echo "Error: " . mysqli_error($conn);
      }
      
			}

			echo
			"
			<script>
			alert('Succesfully Imported');
			document.location.href = '';
			</script>
			";
		}
		?>

    <script>
        var alertMessage = "<?php echo $alertMessage; ?>";
        if (alertMessage) {
            alert(alertMessage);
        }
    </script>
	</body>
</html>
