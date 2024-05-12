## Установка и запуск проекта

### 1. Выполнение миграции базы данных

Перед началом использования проекта необходимо применить миграции базы данных. Для этого выполните следующую команду:

```
php bin/console doctrine:migrations:migrate
```

### 2. Загрузка фикстур

Для добавления тестовых данных в базу данных можно загрузить фикстуры. Выполните команду:

```
php bin/console doctrine:fixtures:load
```

### 3. Запуск Docker

Для развертывания проекта с помощью Docker выполните следующие шаги:

1. Перейдите в каталог проекта.
2. Выполните команду:

```
docker-compose up --build
```

После выполнения этих шагов проект будет доступен по указанному адресу.