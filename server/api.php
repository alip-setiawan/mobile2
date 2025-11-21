<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

require_once "config.php";

// Ambil JSON Body
function getJSON() {
    return json_decode(file_get_contents("php://input"), true);
}

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    /* ============================================================
       GET → Ambil semua user atau user by ID
    ============================================================ */
    case "GET":
        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
            $result = $conn->query("SELECT * FROM users WHERE id = $id");

            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT * FROM users ORDER BY id ASC");

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            echo json_encode($data);
        }
        break;

    /* ============================================================
       POST → Tambah User
    ============================================================ */
    case "POST":
        $input = getJSON();

        $name     = $conn->real_escape_string($input["nama"]     ?? "");
        $email    = $conn->real_escape_string($input["email"]    ?? "");
        $password = $conn->real_escape_string($input["password"] ?? "");

        if (!$name || !$email) {
            echo json_encode(["error" => "Nama dan email wajib diisi"]);
            break;
        }

        $sql = "INSERT INTO users (nama, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql)) {
            echo json_encode(["message" => "Data berhasil ditambahkan"]);
        } else {
            echo json_encode(["error" => "Gagal menambahkan data"]);
        }
        break;

    /* ============================================================
       PUT → Update user
    ============================================================ */
    case "PUT":
        if (!isset($_GET["id"])) {
            echo json_encode(["error" => "ID wajib disertakan"]);
            break;
        }

        $id = intval($_GET["id"]);
        $input = getJSON();

        $name     = $conn->real_escape_string($input["nama"]     ?? "");
        $email    = $conn->real_escape_string($input["email"]    ?? "");
        $password = $conn->real_escape_string($input["password"] ?? "");

        $sql = "UPDATE users SET nama='$name', email='$email', password='$password' WHERE id=$id";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Data berhasil diupdate"]);
        } else {
            echo json_encode(["error" => "Gagal mengupdate data"]);
        }
        break;

    /* ============================================================
       DELETE → Hapus user
    ============================================================ */
    case "DELETE":
        if (!isset($_GET["id"])) {
            echo json_encode(["error" => "ID wajib disertakan"]);
            break;
        }

        $id = intval($_GET["id"]);
        $sql = "DELETE FROM users WHERE id=$id";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Data berhasil dihapus"]);
        } else {
            echo json_encode(["error" => "Gagal menghapus data"]);
        }
        break;

    /* ============================================================
       TRUNCATE → Hapus semua data (gunakan POST atau GET khusus)
    ============================================================ */
    case "TRUNCATE":  // Kalau server mendukung custom method
        if ($conn->query("TRUNCATE TABLE users")) {
            echo json_encode(["message" => "Semua data berhasil dihapus"]);
        } else {
            echo json_encode(["error" => "Gagal truncate tabel"]);
        }
        break;

    /* ============================================================
       DEFAULT
    ============================================================ */
    default:
        echo json_encode(["error" => "Metode tidak dikenali"]);
        break;
}
?>