<?php
    
    $conn = mysqli_connect('localhost', 'root', '', 'skybank');

    if (!$conn) {
        echo 'Connection error: ' . mysqli_connect_error();
    } else {
        echo "bien";
    }
    
?>

<?php /*
$host = '127.0.0.1';  
$port = '3307';      
$dbname = 'skybank';
$username = 'root';  
$password = 'skybank';  
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa";
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}*/
?>