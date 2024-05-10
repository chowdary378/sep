<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $registration_number = $_POST['registration_number'];
    $password = $_POST['password'];

    // Check if the credentials match admin
    if ($registration_number === "admin" && $password === "admin") {
        // Redirect to adminPage.php
        header("Location: adminPage.php");
        exit(); // Make sure nothing else gets executed after redirection
    }

    // Establish database connection
    $conn = mysqli_connect("localhost", "root", "", "students");
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if login credentials are valid
    $login_query = "SELECT students.student_ID, students.Name, failed_subjects.Subject_Name, failed_subjects.Subject_Code, failed_subjects.amountRupees, failed_subjects.amountEthers
    FROM students
    INNER JOIN student_credentials ON students.Student_ID = student_credentials.Student_ID
    INNER JOIN failed_subjects ON students.Student_ID = failed_subjects.Student_ID
    WHERE student_credentials.Registration_Number='$registration_number' AND student_credentials.Password='$password'";
    
    
    $login_result = mysqli_query($conn, $login_query);

    if (mysqli_num_rows($login_result) > 0) {
        // Store data in session
        $_SESSION['failed_subjects'] = mysqli_fetch_all($login_result, MYSQLI_ASSOC);
        
        // Redirect to failed_subjects.php with registration number in URL
        header("Location: failed_subjects.php?registration_number=$registration_number");
        exit(); // Make sure nothing else gets executed after redirection
    } else {
        echo "<p>Login failed. Invalid registration number or password.</p>";
    }

    // Close database connection
    mysqli_close($conn);
}
?>
