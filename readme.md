Банк Василия Петровича

Установка приложения:

Скачать приложение
git clone git@github.com:alexxandr/bank.git

Установить зависимости используя composer:
composer install

Настроить конфигурацию приложения, скопировав файлы  config/~db-local.php и config/~params-local.php в config/db-local.php и config/params-local.php соотв.

Создать БД с именем, указанным в config/db-local.php

Применить миграции:
php yii migrate

Заполнить БД данными для демонстрации:
php yii data/fill

Начислить проценты по депозитам:
php yii cron/percent-accrual

Начислить комиссию:
php yii cron/commission-accrual 1000 1
(второй параметр для отключения проверки 1го числа месяца.)