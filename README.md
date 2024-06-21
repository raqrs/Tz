# Система управления правами пользователей
## Требования

- PHP 5.6 или выше
- MySQL
- Composer

Импортировать dump.sql в базу данных Mysql
Внести изменения для подключения к бд в .env

Выполнить composer install

### Добавить пользователя в группу
- **URL:** `/addUserToGroup`
- **Метод:** `POST`
- **Тело запроса:**
  ```json
  {
      "userId": 1,
      "groupId": 1
  }
- **Ответ:**
  ```json
  {
    "success": true
  }
### Удалить пользователя из группы
- **URL:** `/removeUserFromGroup`
- **Метод:** `POST`
- **Тело запроса:**
  ```json
  {
    "userId": 1,
    "groupId": 1
  }

- **Ответ:**
  ```json
  {
    "success": true
  }
### Список групп
- **URL:** `/listGroups`
- **Метод:** `GET`
- **Ответ:**
  ```json
  [
    {
        "id": 1,
        "name": "admin"
    },
    {
        "id": 2,
        "name": "moderator"
    },
    {
        "id": 3,
        "name": "user"
    }
  ]
### Получить права пользователя
- **URL:** `/getUserRights/{userId}`
- **Метод:** `GET`
- **Ответ:**
  ```json
  {
    "send_messages": true,
    "service_api": false,
    "debug": true
  }

### Добавить право к группе
- **URL:** `/addRightToGroup`
- **Метод:** `POST`
- **Тело запроса:**
  ```json
  {
    "groupId": 1,
    "rightId": 1
  }
- **Ответ:**
  ```json
  {
    "success": true
  }

### Удалить право из группы
- **URL:** `/removeRightFromGroup`
- **Метод:** `POST`
- **Тело запроса:**
  ```json
  {
    "groupId": 1,
    "rightId": 1
  }
- **Ответ:**
  ```json
  {
    "success": true
  }

### Запуск тестов
tests/UserRightsSystemTest.php