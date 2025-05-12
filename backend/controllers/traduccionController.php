<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
require_once  __DIR__ . '/../config/conn.php';

class TraduccionController {
    private $conn;
    private $apiKey = 'de1133df-45a5-4437-a9cf-1d8c6164ae76:fx'; 

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

      // Función para traducir un solo texto
      /*public function traducir($text, $lang) {
        
        $stmt = $this->conn->prepare("SELECT texto_traducido FROM textos WHERE texto_original = ? AND idioma_destino = ?");
        $stmt->execute([$text, $lang]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existing && !empty($existing['texto_traducido'])) {
            echo json_encode(['translatedText' => $existing['texto_traducido']]);
            return;
        }
    
        // Si no existe, traducir
        $translatedText = $this->traducirConDeepL($text, $lang);
    
        if ($translatedText) {
            // Guardar nueva traducción
            $insert = $this->conn->prepare("INSERT INTO textos (texto_original, texto_traducido, idioma_original, idioma_destino) VALUES (?, ?, ?, ?)");
            $insert->execute([$text, $translatedText, 'ES', $lang]);
    
            echo json_encode(['translatedText' => $translatedText]);
        } else {
            echo json_encode(['error' => 'Error al traducir el texto']);
        }
    }
    

     // Función para traducir todos los textos de la base de datos
     public function traducirTodosLosTextos($lang) {

        $sql = "SELECT id, clave, texto_original FROM textos WHERE texto_traducido IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $translatedText = $this->traducirConDeepL($row['texto_original'], $lang);

            if ($translatedText) {
                $this->guardarTraduccion($row['id'], $translatedText);
            }
        }

        echo json_encode(['message' => 'Todos los textos han sido traducidos.']);
    }

    // Función para traducir usando la API de DeepL
    private function traducirConDeepL($text, $targetLang) {
        $url = 'https://api-free.deepl.com/v2/translate';

        $postData = [
            'auth_key' => $this->apiKey, 
            'text' => $text,
            'target_lang' => strtoupper($targetLang) 
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        $response = curl_exec($ch);

        if(curl_errno($ch)) {
            echo json_encode(['error' => 'Error en la solicitud a la API']);
            exit;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['translations'][0]['text'])) {
            return $result['translations'][0]['text'];
        } else {
            return null; 
        }
    }

    private function guardarTraduccion($id, $translatedText) {
        $sql = "UPDATE textos SET texto_traducido = :texto_traducido WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':texto_traducido', $translatedText);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }*/


    function traducir($data) {
        global $conn;
        
        $texto = substr(trim($data['texto']), 0, 5000);
        $idioma_origen = in_array(strtoupper($data['idioma_origen']), ['ES', 'EN', 'FR', 'DE']) ? strtoupper($data['idioma_origen']) : 'ES';
        $idioma_destino = in_array(strtoupper($data['idioma_destino']), ['ES', 'EN', 'FR', 'DE']) ? strtoupper($data['idioma_destino']) : 'EN';
        
        if(empty($texto) || $idioma_origen === $idioma_destino) {
            echo json_encode(['status' => 'success', 'traduccion' => $texto]);
            return;
        }
        
        $stmt = $conn->prepare("SELECT texto_traducido FROM textos WHERE texto_original = ? AND idioma_original = ? AND idioma_destino = ?");
        if($stmt) {
            $stmt->bind_param("sss", $texto, $idioma_origen, $idioma_destino);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode(['status' => 'success', 'traduccion' => $row['texto_traducido']]);
                return;
            }
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
        
        $stmt = $conn->prepare("INSERT INTO textos (texto_original, idioma_original, idioma_destino, texto_traducido) VALUES (?, ?, ?, ?)");
        if($stmt) {
            $stmt->bind_param("ssss", $texto, $idioma_origen, $idioma_destino, $traduccion);
            $stmt->execute();
        }
        
        echo json_encode(['status' => 'success', 'traduccion' => $traduccion]);
    }



}
?>