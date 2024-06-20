<?php
// URL API для поиска пациента
$apiUrl = 'https://api-developer.macdent.kz/patient/find/';

// Access token для аутентификации
$accessToken = '488:huOTWx5yovGFxDE50kmeLZiiLpU2G2Q6wJxo7b1DYIPB0nYzRXyByMdDGwTzWwdwW7XOYi33HYl7wBcg58UwdciFfVtjjM8ue8swyZKbrkeSdOxtkEyuR6g5';

// Получение данных из запроса
$phone = $_GET['phone'];

// Формирование URL для запроса
$url = $apiUrl . '?phone=' . urlencode($phone) . '&access_token=' . urlencode($accessToken);

// Отправка HTTP-запроса
$response = file_get_contents($url);

// Обработка ответа
if ($response === FALSE) {
    // Обработка ошибки
    error_log("Ошибка при отправке данных контакта: " . print_r($phone, true));
} else {
    // Вывод ответа
    echo $response;
}
?>
