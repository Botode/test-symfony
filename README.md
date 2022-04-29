# Приложение для расчета скоринга клиентов

Для запуска:
```bash
    docker-compose up --build -d
    docker exec -it test-symfony-php /bin/bash
    composer install
    php bin/console doctrine:migrations:migrate
```

Адрес web-сервиса: http://localhost:8890

Для запуска перерасчета скоринга:
```bash
    php bin/console app:scoring-refresh [id]
```

Для тестирования:
```bash
    php bin/console --env=test doctrine:database:create
    php bin/console --env=test doctrine:schema:create
    php bin/console --env=test doctrine:fixtures:load
    php bin/phpunit
```
