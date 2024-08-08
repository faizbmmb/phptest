<?php
$conn = new mysqli('localhost', 'root', '', 'feedback_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $stmt = $conn->prepare("UPDATE feedback SET name=?, email=?, age=?, document=?, feedback_type=?, service_used=?, additional_comment=? WHERE id=?");
    $stmt->bind_param("ssisissi", $name, $email, $age, $document, $feedback_type, $service_used, $additional_comment, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Feedback updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $stmt = $conn->prepare("SELECT * FROM feedback WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $feedback = $result->fetch_assoc();

    if (!$feedback) {
        echo "No record found.";
        exit;
    }
}
?>

<h1>Edit Feedback</h1>
<form action="edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($feedback['name']); ?>" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($feedback['email']); ?>" required><br><br>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($feedback['age']); ?>" required><br><br>

    <label for="document">Documents:</label>
    <input type="file" id="document" name="document"><br><br>

    <label for="feedback_type">Type of Feedback:</label>
    <select id="feedback_type" name="feedback_type" required>
        <option value="Positive" <?php if ($feedback['feedback_type'] === 'Positive') echo 'selected'; ?>>Positive</option>
        <option value="Negative" <?php if ($feedback['feedback_type'] === 'Negative') echo 'selected'; ?>>Negative</option>
    </select><br><br>

    <label>Service Used:</label><br>
    <?php
    $services = ['Service 1', 'Service 2', 'Service 3'];
    foreach ($services as $service) {
        $checked = strpos($feedback['service_used'], $service) !== false ? 'checked' : '';
        echo "<input type='checkbox' name='service_used[]' value='$service' $checked> $service<br>";
    }
    ?><br>

    <label for="additional_comment">Additional Comment:</label><br>
    <textarea id="additional_comment" name="additional_comment"><?php echo htmlspecialchars($feedback['additional_comment']); ?></textarea><br><br>

    <input type="submit" value="Update Feedback">
</form>