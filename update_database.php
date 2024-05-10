<?php
// Retrieve transaction details and selected rows from the POST request
$request_body = file_get_contents('php://input');
$data = json_decode($request_body);

// Extract transaction details
$transactionId = $data->transactionId;
$registrationNumber = $data->registrationNumber;
$ethersSum = $data->ethersSum;

// Extract selected rows
$selectedRows = $data->selectedRows;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "students";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve student ID based on registration number
$sql = "SELECT Student_ID FROM students WHERE Registration_Number = '$registrationNumber'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentId = $row['Student_ID'];

    // Iterate through selected rows
    foreach ($selectedRows as $row) {
        $subjectCode = $row->Subject_Code;

        // Check if the subject code matches the student's subject code
        $check_subject_sql = "SELECT * FROM failed_subjects WHERE Student_ID = '$studentId' AND Subject_Code = '$subjectCode'";
        $check_subject_result = $conn->query($check_subject_sql);

        if ($check_subject_result->num_rows > 0) {
            // Update the paid column for the matching row
            $update_paid_sql = "UPDATE failed_subjects SET paid = 'yes', txn_id = (SELECT txn_id FROM transactions WHERE Transaction_ID = '$transactionId') WHERE Student_ID = '$studentId' AND Subject_Code = '$subjectCode'";
            if ($conn->query($update_paid_sql) !== TRUE) {
                echo "Error updating paid status: " . $conn->error;
            }
        } else {
            echo "No matching subject found for Student_ID: $studentId and Subject_Code: $subjectCode";
        }
    }

    // Insert transaction details into transactions table
    $insert_sql = "INSERT INTO transactions (Transaction_ID, Student_ID, Payment_Type, txn_amount) 
                    VALUES ('$transactionId', '$studentId', 'Ether', '$ethersSum')";

    if ($conn->query($insert_sql) === TRUE) {
        // Retrieve the auto-incremented txn_id
        $txnId = $conn->insert_id;

        // Update txn_id in failed_subjects table
        $update_txn_id_sql = "UPDATE failed_subjects SET txn_id = '$txnId' WHERE Student_ID = '$studentId'";
        if ($conn->query($update_txn_id_sql) !== TRUE) {
            echo "Error updating txn_id in failed_subjects table: " . $conn->error;
        }

        echo "Database updated successfully";
    } else {
        echo "Error updating database: " . $conn->error;
    }
} else {
    echo "No student found with the provided registration number";
}

$conn->close();
?>
