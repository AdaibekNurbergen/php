<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitrix24 Webhook</title>
</head>
<body>
    <script>
        // Получаем ID сделки из URL параметра
        const urlParams = new URLSearchParams(window.location.search);
        const dealId = urlParams.get('deal_id');

        if (dealId) {
            // URL вебхука для получения данных сделки
            const dealUrl = `https://dentapro.bitrix24.kz/rest/15/y4pt5in154pwzctu/crm.deal.get.json?id=${dealId}`;

            fetch(dealUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(dealData => {
                if (dealData.result && dealData.result.CONTACT_ID) {
                    const contactId = dealData.result.CONTACT_ID;

                    // URL вебхука для получения данных контакта
                    const contactUrl = `https://dentapro.bitrix24.kz/rest/15/y4pt5in154pwzctu/crm.contact.get.json?id=${contactId}`;

                    return fetch(contactUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                } else {
                    throw new Error('Контакт не найден.');
                }
            })
            .then(response => response.json())
            .then(contactData => {
                if (contactData.result && contactData.result.PHONE && contactData.result.PHONE[0] && contactData.result.PHONE[0].VALUE) {
                    const phoneNumber = contactData.result.PHONE[0].VALUE;

                    // URL для поиска в базе macdent
                    const macdentUrl = `https://api-developer.macdent.kz/patient/find/?phone=${encodeURIComponent(phoneNumber)}&access_token=488:huOTWx5yovGFxDE50kmeLZiiLpU2G2Q6wJxo7b1DYIPB0nYzRXyByMdDGwTzWwdwW7XOYi33HYl7wBcg58UwdciFfVtjjM8ue8swyZKbrkeSdOxtkEyuR6g5`;

                    return fetch(macdentUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                } else {
                    throw new Error('Номер телефона не найден.');
                }
            })
            .then(response => response.json())
            .then(macdentData => {
                // Ваши дальнейшие действия с данными из macdent
                console.log('Данные из macdent:', macdentData);
            })
            .catch(error => {
                console.error('Ошибка:', error.message);
            });
        } else {
            console.error('Некорректный ID сделки.');
        }
    </script>
</body>
</html>
