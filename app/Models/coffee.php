<?php

// Включаем файл с классом Database.
// Предполагаем, что Coffee.php находится в app/models/
// и Database.php находится в app/core/
// Путь должен быть относительно текущего файла.
require_once __DIR__ . '/../core/Database.php';

class Coffee {
    private $conn;         // Объект соединения с базой данных
    private $table_name = "coffees"; // Имя таблицы в БД

    // Свойства объекта Coffee, соответствующие столбцам в таблице
    public $id;
    public $name;
    public $origin;
    public $roast_level; // Уровень обжарки (например, светлая, средняя, темная)
    public $price;
    public $description;
    public $created_at; // Дата создания записи

    // Конструктор: инициализирует соединение с БД при создании объекта Coffee
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        var_dump($database);
    }

    /**
     * Получает все записи кофе из базы данных.
     * @return PDOStatement Возвращает объект PDOStatement с результатами.
     */
    public function getAll() {
        $query = "SELECT * FROM  $this->table_name order by name ASC"; // Сортируем по названию
        var_dump($query);
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
         if (!$stmt->execute()) { // Если выполнение запроса не удалось
        print_r($stmt->errorInfo()); // Вывести информацию об ошибке PDO
        return false; // Или throw new Exception("...");
    }
        var_dump($stmt->execute());
        return $stmt;
    }

    /**
     * Получает одну запись кофе по её ID.
     * @param int $id ID кофе.
     * @return bool Возвращает true в случае успеха, false в случае ошибки.
     */
    public function getById($id) {
        $query = "SELECT id, name, origin, roast_level, price, description, created_at
                  FROM " . $this->table_name . "
                  WHERE id = ?
                  LIMIT 0,1"; // Ограничиваем одним результатом

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id); // Привязываем параметр

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Если запись найдена, присваиваем её свойствам объекта
        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->origin = $row['origin'];
            $this->roast_level = $row['roast_level'];
            $this->price = $row['price'];
            $this->description = $row['description'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    /**
     * Создает новую запись кофе в базе данных.
     * @param array $data Массив данных для вставки (например, $_POST).
     * @return bool Возвращает true в случае успеха, false в случае ошибки.
     */
    public function create($data) {
        // SQL-запрос для вставки
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                      name=:name,
                      origin=:origin,
                      roast_level=:roast_level,
                      price=:price,
                      description=:description,
                      created_at=:created_at";

        $stmt = $this->conn->prepare($query);

        // Очистка и привязка данных (защита от XSS и SQL-инъекций)
        $this->name = htmlspecialchars(strip_tags($data['name']));
        $this->origin = htmlspecialchars(strip_tags($data['origin']));
        $this->roast_level = htmlspecialchars(strip_tags($data['roast_level']));
        $this->price = htmlspecialchars(strip_tags($data['price']));
        $this->description = htmlspecialchars(strip_tags($data['description']));
        $this->created_at = date('Y-m-d H:i:s'); // Текущая дата и время

        // Привязка параметров
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":origin", $this->origin);
        $stmt->bindParam(":roast_level", $this->roast_level);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":created_at", $this->created_at);

        // Выполнение запроса
        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->errorInfo()[2]); // Для отладки
        return false;
    }

    /**
     * Обновляет существующую запись кофе в базе данных.
     * @param int $id ID кофе для обновления.
     * @param array $data Массив данных для обновления.
     * @return bool Возвращает true в случае успеха, false в случае ошибки.
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . "
                  SET
                      name=:name,
                      origin=:origin,
                      roast_level=:roast_level,
                      price=:price,
                      description=:description
                  WHERE
                      id = :id";

        $stmt = $this->conn->prepare($query);

        // Очистка и привязка данных
        $this->name = htmlspecialchars(strip_tags($data['name']));
        $this->origin = htmlspecialchars(strip_tags($data['origin']));
        $this->roast_level = htmlspecialchars(strip_tags($data['roast_level']));
        $this->price = htmlspecialchars(strip_tags($data['price']));
        $this->description = htmlspecialchars(strip_tags($data['description']));
        $this->id = htmlspecialchars(strip_tags($id)); // ID также очищаем

        // Привязка параметров
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':origin', $this->origin);
        $stmt->bindParam(':roast_level', $this->roast_level);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $this->id);

        // Выполнение запроса
        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->errorInfo()[2]); // Для отладки
        return false;
    }

    /**
     * Удаляет запись кофе из базы данных.
     * @param int $id ID кофе для удаления.
     * @return bool Возвращает true в случае успеха, false в случае ошибки.
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($id)); // Очищаем ID
        $stmt->bindParam(1, $this->id);

        // Выполнение запроса
        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->errorInfo()[2]); // Для отладки
        return false;
    }
}