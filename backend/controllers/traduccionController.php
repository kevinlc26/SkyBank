<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
require_once(__DIR__ . '/../config/conn.php');


class TraduccionController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function traducir($data) {
        
    $texto = substr(trim($data['texto']), 0, 5000);
    $idioma_origen = in_array(strtoupper($data['idioma_origen']), ['ES', 'EN', 'FR', 'DE']) ? strtoupper($data['idioma_origen']) : 'ES';
    $idioma_destino = in_array(strtoupper($data['idioma_destino']), ['ES', 'EN', 'FR', 'DE']) ? strtoupper($data['idioma_destino']) : 'EN';

    if (empty($texto) || $idioma_origen === $idioma_destino) {
        echo json_encode(['status' => 'success', 'traduccion' => $texto]);
        return;
    }

    $stmt = $this->conn->prepare("SELECT texto_traducido FROM textos WHERE texto_original = ? AND idioma_original = ? AND idioma_destino = ?");
    $stmt->execute([$texto, $idioma_origen, $idioma_destino]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && isset($result['texto_traducido'])) {
        echo json_encode(['status' => 'success', 'traduccion' => $result['texto_traducido']]);
        return;
    }

    $deeplUrl = "https://api-free.deepl.com/v2/translate";
    $auth_key = 'de1133df-45a5-4437-a9cf-1d8c6164ae76:fx';
    $postData = [
        'auth_key'    => $auth_key,
        'text'        => $texto,
        'target_lang' => $idioma_destino,
        'source_lang' => $idioma_origen
    ];

    $ch = curl_init($deeplUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);
    curl_close($ch);

    $decodedResponse = json_decode($response, true);

    if (!isset($decodedResponse['translations'][0]['text'])) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Respuesta inesperada de la API.']);
        return;
    }

    $traduccion = $decodedResponse['translations'][0]['text'];

    $stmt = $this->conn->prepare("INSERT INTO textos (texto_original, idioma_original, idioma_destino, texto_traducido) VALUES (?, ?, ?, ?)");
    $stmt->execute([$texto, $idioma_origen, $idioma_destino, $traduccion]);

    echo json_encode(['status' => 'success', 'traduccion' => $traduccion]);
}




}
?>