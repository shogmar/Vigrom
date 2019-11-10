Задание https://docs.google.com/document/d/1_WnOlZmY2hugSQrb5FMUbxK8PRr7FO_JZpt94eD4ZgE
- Symfony 4.3
- composer update
- В .env настраиваем БД 
- Создаём таблицы `symfony console doctrine:schema:update --force` или `php bin\console doctrine:schema:update --force`
- заполняем БД Тестовыми данными `symfony console doctrine:fixtures:load` или `php bin\console doctrine:fixtures:load`
- Запускаем сервер `symfony serve`

- "/transaction_days/{uuid}/{lastdays}/{reason_for_change}" - Get запрос для получения данных за промежуток времени
    - uuid - id кошелька
    - lastdays - кол дней
    - reason_for_change - причина

- "/balance_status/{uuid}" - Get запрос получения баланса кошелька
    - uuid - id кошелька

- "/add_transaction" - Post запрос для изменения баланса кошелька. Обменивает по курсу валюту, если это требуется.
    - uuid - id кошелька
    - type_transaction - Тип транзакции
    - reason_for_change - Причина изменения счета
    - summa - Сумма
    - corrency - Валюта
    