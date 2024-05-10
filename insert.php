<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Student Details</title>
    <link rel="stylesheet" href="./css/insert.css">    
</head>
<body>
    <h2>Insert Student Details</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="registration_number">Registration Number:</label>
        <input type="text" id="registration_number" name="registration_number" required>
        
        <label for="name">Student Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="branch">Student Branch:</label>
        <input type="text" id="branch" name="branch" required>

        <label for="course">Student Course:</label>
        <input type="text" id="course" name="course" required>
        
        <label for="subject_name">Subject Name:</label>
        <input type="text" id="subject_name" name="subject_name" required>
        
        <label for="subject_code">Subject Code:</label>
        <input type="text" id="subject_code" name="subject_code" required>

        <!-- New input fields for rupees and ethers -->
        <label for="amount_rupees">Amount in Rupees:</label>
        <input type="text" id="amount_rupees" name="amount_rupees">
        
        <label for="amount_ethers">Amount in Ethers:</label>
        <input type="text" id="amount_ethers" name="amount_ethers">
        
        <input type="submit" value="Submit">
    </form>

    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $registration_number = $_POST['registration_number'];
        $name = $_POST['name'];
        $branch = $_POST['branch'];
        $course = $_POST['course'];
        $subject_name = $_POST['subject_name'];
        $subject_code = $_POST['subject_code'];
        $amount_rupees = $_POST['amount_rupees'];
        
        $amount_ethers = $_POST['amount_ethers'];

        // Establish database connection
        $conn = mysqli_connect("localhost", "root", "", "students");

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if the student already exists based on registration number
        $check_student_query = "SELECT * FROM Students WHERE Registration_Number = '$registration_number'";
        $check_student_result = mysqli_query($conn, $check_student_query);

        if (mysqli_num_rows($check_student_result) > 0) {
            // Student already exists, check if the name matches
            $existing_student = mysqli_fetch_assoc($check_student_result);
            if ($existing_student['Name'] !== $name) {
                // Different name for existing registration number, show error
                echo "<p>Error: A student with the same registration number already exists but with a different name.</p>";
                exit; // Stop further execution
            }
            // Get the Student_ID of the existing student
            $student_id = $existing_student['Student_ID'];
        } else {
            // Student doesn't exist, insert new student
            $insert_student_query = "INSERT INTO Students (Registration_Number, Name,branch,course) VALUES ('$registration_number', '$name','$branch','$course')";
            mysqli_query($conn, $insert_student_query);
            // Get the Student_ID of the newly inserted student
            $student_id = mysqli_insert_id($conn);
        }

        // Check if the subject already exists for this student
        $check_subject_query = "SELECT * FROM Failed_Subjects WHERE Student_ID = $student_id AND Subject_Code = '$subject_code'";
        $check_subject_result = mysqli_query($conn, $check_subject_query);

        if (mysqli_num_rows($check_subject_result) > 0) {
            // Subject already exists for this student, show error
            echo "<p>Error: This subject already exists for this student.</p>";
            exit; // Stop further execution
        }

        // Insert failed subject details into Failed_Subjects table
        $insert_failed_subject_query = "INSERT INTO Failed_Subjects (Student_ID, Subject_Name, Subject_Code, amountRupees, amountEthers) VALUES ($student_id, '$subject_name', '$subject_code', '$amount_rupees', '$amount_ethers')";
        mysqli_query($conn, $insert_failed_subject_query);

        // Close database connection
        mysqli_close($conn);

        echo "<p class='success'>Student details inserted successfully.</p>";
    }
    ?>


</body>
</html>
