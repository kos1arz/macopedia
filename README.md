#### Aby uruchomić aplikację:

- 1 . docker-compose up -d
- 2 . docker exec -it php82-container bash
- 3 . composer install
- 4 . npm install
- 5 . Uzupełnić trzeba .env
- 6 . php bin/console doctrine:database:create
- 7 . php bin/console doctrine:migrations:migrate
- 8 . php bin/console doctrine:fixtures:load

#### Logowanie:

Login: test@test.pl
Hasło: qwerty

#### Po wgraniu csv:

php bin/console import:csv
