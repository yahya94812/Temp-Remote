<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'getStudents':
        getStudents();
        break;
    case 'getStudent':
        getStudent($_GET['id']);
        break;
    case 'addStudent':
        addStudent();
        break;
    case 'updateStudent':
        updateStudent();
        break;
    case 'deleteStudent':
        deleteStudent($_POST['id']);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getStudents() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'students' => $students]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function getStudent($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($student) {
            echo json_encode(['success' => true, 'student' => $student]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function addStudent() {
    global $pdo;
    try {
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $cgpa = $_POST['cgpa'];

        // Validate email uniqueness
        $checkStmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
        $checkStmt->execute([$email]);
        if ($checkStmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }

        $stmt = $pdo->prepare("INSERT INTO students (name, gender, dob, phone, email, cgpa) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $gender, $dob, $phone, $email, $cgpa]);
        
        echo json_encode(['success' => true, 'message' => 'Student added successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function updateStudent() {
    global $pdo;
    try {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $cgpa = $_POST['cgpa'];

        // Validate email uniqueness (excluding current student)
        $checkStmt = $pdo->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
        $checkStmt->execute([$email, $id]);
        if ($checkStmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }

        $stmt = $pdo->prepare("UPDATE students SET name = ?, gender = ?, dob = ?, phone = ?, email = ?, cgpa = ? WHERE id = ?");
        $stmt->execute([$name, $gender, $dob, $phone, $email, $cgpa, $id]);
        
        echo json_encode(['success' => true, 'message' => 'Student updated successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function deleteStudent($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true, 'message' => 'Student deleted successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>