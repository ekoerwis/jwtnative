<?php


// Import script autoload agar bisa menggunakan library
require_once('./vendor/autoload.php');
// require_once('./cors.php');
// Import library
use Firebase\JWT\JWT;
use Dotenv\Dotenv;


// Load dotenv
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

date_default_timezone_set($_ENV['TIMEZONE']);

// Atur jenis response
header('Content-Type: application/json');

// Cek method request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

// Ambil data json yang dikirim user
$json = file_get_contents('php://input');
$input = json_decode($json);


// Jika tidak ada data email atau password
if (!isset($input->email) || !isset($input->password)) {
    http_response_code(400);
    exit();
}

// Cuma data mock/dummy, bisa diganti dengan data dari database
$user = [
    'email' => 'johndoe@example.com',
    'password' => 'qwerty123'
];


// Jika email atau password tidak sesuai
if ($input->email !== $user['email'] || $input->password !== $user['password']) {
    echo json_encode([
        'message' => 'Email atau password tidak sesuai'
    ]);
    exit();
}

// 15 * 60 (detik) = 15 menit
$expired_time = time() + (15 * 60);


// Buat payload dan access token
$payload = [
    'email' => $input->email,
    // Di library ini wajib menambah key exp untuk mengatur masa berlaku token
    'exp' => $expired_time
];


// Men-generate access token
$access_token = JWT::encode($payload, $_ENV['ACCESS_TOKEN_SECRET'], 'HS256');
echo json_encode([
  'accessToken' => $access_token,
  'expiry' => date(DATE_ISO8601, $expired_time)
]);

// Ubah waktu kadaluarsa lebih lama (1 jam)
$payload['exp'] = time() + (60 * 60);
$refresh_token = JWT::encode($payload, $_ENV['REFRESH_TOKEN_SECRET'],'HS256');
// Simpan refresh token di http-only cookie
setcookie('refreshToken', $refresh_token, $payload['exp'], '', '', false, true);

?>