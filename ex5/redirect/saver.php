<?php

$data = $_POST['data'] ?? '';

$message = "Data saved!";


if (isset($_POST['save']) && $_POST['save']) {
    file_put_contents('data.txt', $data);
    $message = sprintf("\nSaved %s bytes\n", strlen($data));
}

$url = 'index.html?message=' . urlencode($message);

header('Location: ' . $url);
