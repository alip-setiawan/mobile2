<?php
// ====== CORS ======
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

// ====== PRE-FLIGHT (OPTIONS) ======
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

require_once "config.php";

// ====== FUNCTION AMBIL INPUT JSON ======
function getInput() {
    return json_decode(file_get_contents("php://input"), true);
}

// ====== API KEY ======
$developer_mode = false;  // true = tidak perlu API key
$sandi_rahasia = "AnakPrabowo";

$headers = getallheaders();
$kunci_pengguna = $headers['X-API_KUNCI'] ?? $headers['X-API-KUNCI'] ?? "";

if (!$developer_mode && $kunci_pengguna !== $sandi_rahasia) {
    echo json_encode([
        "status" => "Gagal",
        "pesan"  => "API Key tidak valid!"
    ]);
    exit;
}

// ====== ROUTING BERDASARKAN METHOD ======
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'POST':

        $input = getInput();
        $email = $conn->real_escape_string($input['email'] ?? '');
        $password = $conn->real_escape_string($input['password'] ?? '');

        if (empty($email) || empty($password)) {
            echo json_encode([
                "status" => "Gagal",
                "pesan"  => "Email dan password tidak boleh kosong!"
            ]);
            exit;
        }

        // ====== CEK LOGIN ======
        $query = $conn->query(
            "SELECT * FROM users WHERE email='$email' AND password='$password'"
        );

        if ($query->num_rows > 0) {
            echo json_encode([
                "status" => "Berhasil",
                "pesan"  => "Berhasil Login!"
            ]);
        } else {
            echo json_encode([
                "status" => "Gagal",
                "pesan"  => "Email atau password salah!"
            ]);
        }
        exit;
}
?>