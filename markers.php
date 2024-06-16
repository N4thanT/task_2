<?php
$host = '';  // Adjust as necessary
$dbname = '';
$username = '';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

header('Content-Type: application/json');

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $stmt = $conn->query("SELECT * FROM markers");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $conn->prepare("INSERT INTO markers (name, description, latitude, longitude) VALUES (?, ?, ?, ?)");
            $success = $stmt->execute([$data['name'], $data['description'], $data['latitude'], $data['longitude']]);
            $id = $conn->lastInsertId(); // Capture this right after the execute statement
            echo json_encode(['success' => $success, 'id' => $id]);
            break;
        
        default:
            echo json_encode(['error' => 'Method not supported']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
