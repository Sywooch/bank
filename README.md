# Банк Василия Петровича

## Установка приложения:

Скачать приложение
```shell
git clone git@github.com:alexxandr/bank.git
```
Установить зависимости используя composer:
```shell
composer install
```

## Настройка

Настроить конфигурацию приложения, скопировав файлы  ```config/~db-local.php``` и ```config/~params-local.php``` в ```config/db-local.php``` и ```config/params-local.php``` соответственно.

Создать БД с именем, указанным в ```config/db-local.php```

Применить миграции:
```shell
php yii migrate
```

## Демонстрация

Заполнить БД данными для демонстрации:
```shell
php yii data/fill
```

Начислить проценты по депозитам:
```shell
php yii cron/percent-accrual
```

Начислить комиссию:
```shell
php yii cron/commission-accrual 1000 1
```
(второй параметр для отключения проверки 1го числа месяца.)