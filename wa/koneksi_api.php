<?php
// ==============================================================
// FILE: kirim_api.php — Endpoint API Kirim Chat via Pusher
// ==============================================================
header('Content-Type: application/json');

// Ambil input JSON/POST
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$nama  = trim($input['nama'] ?? '');
$pesan = trim($input['pesan'] ?? '');

if (empty($nama) || empty($pesan)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'pesan' => 'Nama dan pesan wajib diisi']);
    exit();
}

// Konfigurasi Kunci API Pusher Anda
$app_id  = '2210427';
$app_key = 'be2ed99ed409c7762088';
$secret  = 'a2f49a5a9911b88bc8a4';
$cluster = 'ap1';

// Format Payload API Pusher
$payload = json_encode([
    'name' => 'pesan-baru',
    'channels' => ['ruang-chat'],
    'data' => json_encode(['nama' => $nama, 'pesan' => $pesan, 'waktu' => date('H:i')])
]);

// Kirim ke API Pusher via cURL
$auth_timestamp = time();
$auth_version   = '1.0';
$body_md5       = md5($payload);
$string_to_sign = "POST\n/apps/{$app_id}/events\nauth_key={$app_key}&auth_timestamp={$auth_timestamp}&auth_version={$auth_version}&body_md5={$body_md5}";
$auth_signature = hash_hmac('sha256', $string_to_sign, $secret);

$url = "https://api-{$cluster}.pusher.com/apps/{$app_id}/events?auth_key={$app_key}&auth_timestamp={$auth_timestamp}&auth_version={$auth_version}&body_md5={$body_md5}&auth_signature={$auth_signature}";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true
]);
$response = curl_exec($ch);
curl_close($ch);

echo json_encode(['status' => 'success', 'pesan' => 'Pesan terkirim']);
?>