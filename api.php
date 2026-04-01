<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Ide írd be a saját adatbázis adataidat a feladatleírás alapján!
$host = 'localhost';
$dbname = 'bpjfbl'; 
$username = 'bpjfbl'; 
$password = 'NJE123...'; 

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    die(json_encode(["error" => "Adatbázis hiba: " . $e->getMessage()]));
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $stmt = $dbh->query("SELECT * FROM mozi");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;
        
    case 'POST':
        $stmt = $dbh->prepare("INSERT INTO mozi (moziazon, mozinev, irszam, cim, telefon) VALUES (:moziazon, :mozinev, :irszam, :cim, :telefon)");
        $stmt->execute([
            ':moziazon' => $input['moziazon'],
            ':mozinev' => $input['mozinev'],
            ':irszam' => $input['irszam'],
            ':cim' => $input['cim'],
            ':telefon' => $input['telefon']
        ]);
        echo json_encode(["message" => "Sikeres mentés"]);
        break;
        
    case 'PUT':
        $stmt = $dbh->prepare("UPDATE mozi SET mozinev=:mozinev, irszam=:irszam, cim=:cim, telefon=:telefon WHERE moziazon=:moziazon");
        $stmt->execute([
            ':moziazon' => $input['moziazon'],
            ':mozinev' => $input['mozinev'],
            ':irszam' => $input['irszam'],
            ':cim' => $input['cim'],
            ':telefon' => $input['telefon']
        ]);
        echo json_encode(["message" => "Sikeres frissítés"]);
        break;
        
    case 'DELETE':
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $stmt = $dbh->prepare("DELETE FROM mozi WHERE moziazon=:moziazon");
        $stmt->execute([':moziazon' => $id]);
        echo json_encode(["message" => "Sikeres törlés"]);
        break;
}
?>