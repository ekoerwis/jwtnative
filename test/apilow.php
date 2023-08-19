<?php


// date_default_timezone_set($_ENV['TIMEZONE']);


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit();
}

$headers = getallheaders();
if (!isset($headers['entityReq'])) {

    $response = array(
        'status' => 401,
        'message' => 'Header is Missing'
    );
    echo json_encode($response);
    http_response_code(401);
    exit();
}

try {


    // Data game yang akan dikirim jika token valid
    $games = [
        [
            'title' => 'Dota 2',
            'genre' => 'Strategy'
        ],
        [
            'title' => 'Ragnarok',
            'genre' => 'Role Playing Game'
        ]
    ];
    echo json_encode($games);
    // echo json_encode($headers);
} catch (Exception $e) {
    // Bagian ini akan jalan jika terdapat error saat JWT diverifikasi atau di-decode
    http_response_code(401);
    exit();
}
