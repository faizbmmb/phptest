<?php
$conn = new mysqli('localhost', 'root', '', 'feedback_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$age = $_POST['age'];
$feedback_type = $_POST['feedback_type'];
$additional_comment = $_POST['additional_comment'];
$service_used = isset($_POST['service_used']) ? implode(", ", $_POST['service_used']) : '';

$document = null;
if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
    $document = file_get_contents($_FILES['document']['tmp_name']);
}

$stmt = $conn->prepare("INSERT INTO feedback (name, email, age, document, feedback_type, service_used, additional_comment) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisiss", $name, $email, $age, $document, $feedback_type, $service_used, $additional_comment);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Feedback submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>