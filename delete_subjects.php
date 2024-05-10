<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the selected subjects array from the request
    if(isset($_POST["selected_subjects"])) {
        $selectedSubjectsArray = json_decode($_POST["selected_subjects"], true);

        // Print the received array for debugging
        echo "Received selected subjects array:<br>";
        print_r($selectedSubjectsArray);
        
        // Establish connection to the database
        $conn = mysqli_connect("localhost", "root", "", "students");

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Loop through the selected subjects array and delete each subject
        foreach ($selectedSubjectsArray as $subjectID) {
            // Perform SQL query to delete subject by ID
            $delete_query = "DELETE FROM Failed_Subjects WHERE Failed_Subject_ID = $subjectID";
            if (mysqli_query($conn, $delete_query)) {
                // Subject deleted successfully
                // You can log this or perform any additional actions
            } else {
                // Error occurred while deleting subject
                // You can log this or handle the error as needed
                echo "Error: " . mysqli_error($conn);
            }
        }

        // Close database connection
        mysqli_close($conn);

        // Send response indicating success
        echo "success";
    } else {
        // Handle missing or invalid data in the request
        echo "Invalid data received.";
    }
} else {
    // Handle invalid request method
    echo "Invalid request method.";
}
?>
