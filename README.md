# ProblemBook
ProblemBook - это веб-приложение для управления проектами и задачами. Проект написан на php 8.3.0, фреймворк symfony 7.1.2. Добавление новых сущностей (пользователи, задачи и т.д.) осуществялется через панель менеджера.

<p align="center">
 <img width="820px" src="https://drive.google.com/uc?export=view&id=1PuTm5clKtkOoD3BcvXxUUAohIu7rYPs4" alt="manager"/>
</p>

После разворачивания приложения будет создан тестовый пользователь с правами менеджера:<br>
**Логин:** test@test.com<br>
**Пароль:** pass

## Установка:
1. Клонирование и окружение:
```
git clone https://github.com/violetorange/ProblemBook.git
composer install
yarn install
yarn build
symfony server:start
```
2. БД:
```
docker-compose up -d
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```

## Схема БД:

<p align="center">
 <img width="820px" src="https://drive.google.com/uc?export=view&id=1EUfILfgQ9sZbJ8dXViGDshBV6IjhAXEm" alt="db"/>
</p>
