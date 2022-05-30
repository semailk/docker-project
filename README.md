1. Делаем копию файла .env.example в .env (cp .env.example .env)
2. docker-compose up -d (Поднимаем контейнеры)
3. docker-compose exec app bash (Заходим в php контейнер)
4. composer update (Обновляем наши пакеты) 
5. Настраиваем db .env файл по нашему docker-compose.yml файлу.
6. php artisan migrate --seed (делаем миграции и тестовые данные)
7. chmod -R 777 storage/ и chmod -R 777 vendor/
8. php artisan key:generate
9. php artisan optimize
