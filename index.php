<?php
// URL API для поиска пациента
$apiUrl = 'https://api-developer.macdent.kz/patient/find/';

// Access token для аутентификации
$accessToken = '488:huOTWx5yovGFxDE50kmeLZiiLpU2G2Q6wJxo7b1DYIPB0nYzRXyByMdDGwTzWwdwW7XOYi33HYl7wBcg58UwdciFfVtjjM8ue8swyZKbrkeSdOxtkEyuR6g5';

// Получение данных из запроса Bitrix24
$request = file_get_contents('php://input');
$data = json_decode($request, true);

// Проверка наличия данных сделки
if (isset($data['data']['FIELDS']['CONTACT_ID'])) {
    $contactId = $data['data']['FIELDS']['CONTACT_ID'];

    // Получение данных контакта из Bitrix24
    $contactData = getContactData($contactId);

    if ($contactData && isset($contactData['PHONE'][0]['VALUE'])) {
        $phone = $contactData['PHONE'][0]['VALUE'];

        // Формирование URL для запроса
        $url = $apiUrl . '?phone=' . urlencode($phone) . '&access_token=' . urlencode($accessToken);

        // Отправка HTTP-запроса
        $response = file_get_contents($url);

        // Обработка ответа
        if ($response === FALSE) {
            // Обработка ошибки
            error_log("Ошибка при отправке данных контакта: " . print_r($contactData, true));
        } else {
            // Вывод ответа
            echo $response;
        }
    } else {
        // Обработка ошибки отсутствия телефона
        error_log("Телефон контакта не найден: " . print_r($contactData, true));
    }
} else {
    // Обработка ошибки отсутствия CONTACT_ID
    error_log("CONTACT_ID не найден в запросе: " . print_r($data, true));
}

// Функция для получения данных контакта из Bitrix24
function getContactData($contactId) {
    $webhookUrl = 'https://dentapro.bitrix24.kz/rest/15/uwz0c10ml2e632f8/crm.contact.get.json?ID=' . $contactId;
    $response = file_get_contents($webhookUrl);
    $contactData = json_decode($response, true);

    if (isset($contactData['result'])) {
        return $contactData['result'];
    }

    return null;
}
?>
