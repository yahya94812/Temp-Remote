<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    // Create database
    $pdo = new PDO("mysql:host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS student_management");
    echo "Database created successfully<br>";
    
    // Use the database
    $pdo->exec("USE student_management");
    
    // Create students table
    $sql = "CREATE TABLE IF NOT EXISTS students (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        gender ENUM('Male', 'Female', 'Other') NOT NULL,
        dob DATE NOT NULL,
        phone VARCHAR(15) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        cgpa DECIMAL(3,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "Students table created successfully<br>";
    
    // Insert sample data
    $sampleData = [
        ['John Doe', 'Male', '2000-05-15', '9876543210', 'john.doe@email.com', 8.75],
        ['Jane Smith', 'Female', '1999-12-20', '9876543211', 'jane.smith@email.com', 9.25],
        ['Alex Johnson', 'Other', '2001-03-10', '9876543212', 'alex.johnson@email.com', 7.80]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO students (name, gender, dob, phone, email, cgpa) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($sampleData as $student) {
        $stmt->execute($student);
    }
    
    echo "Sample data inserted successfully<br>";
    echo "<br><strong>Setup completed! You can now use the Student Management System.</strong><br>";
    echo "<a href='index.html'>Go to Student Management System</a>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>