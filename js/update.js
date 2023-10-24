// Function to show the "Update Record" section and populate its fields
function showUpdateRecord(
  appointmentCode,
  lastName,
  firstName,
  middleName,
  gender
) {
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
