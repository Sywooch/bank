# Банк Василия Петровича

## Требования:

- PHP 7.1+
- MySQL 5.7+

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


## Cron

Для начисления процентов и комиссии нужно настроить 2 задачи в crontab'e

Для начисления процентов надо создать ежедневную задачу, например (каждый день в 3 часа ночи):
```shell
0 3 * * * /var/www/bank/yii cron/percent-accrual
```

Для начисления комиссии надо создать ежемесячную задачу, например (каждое первое число месяца в 7:30):
```shell
30 7 1 * * /var/www/bank/yii cron/percent-accrual
```