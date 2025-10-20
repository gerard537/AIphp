<?php

// app/core/Database.php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $charset;
    private $conn; // Переменная для хранения объекта соединения PDO

    /**
     * Конструктор класса Database.
     * Загружает конфигурацию из файла database.php и инициализирует свойства.
     */
    public function __construct() {
        $configPath = __DIR__ .'/../config/database.php';
    echo "Попытка загрузить конфигурацию из: " . $configPath . "<br>";
    if (!file_exists($configPath)) {
        echo "Ошибка: Файл конфигурации не найден по пути: " . $configPath . "<br>";
        die(); // Останавливаем выполнение, если файл не найден
    }
        // Подключаем файл конфигурации базы данных.
        // __DIR__ гарантирует, что путь будет абсолютным относительно текущего файла.
        $config =
        [
        'host' => 'localhost', 
        'db_name' => 'coffee_db', 
        'username' => 'root', 
        'password' => '123',    
        'charset' => 'utf8mb4', 
        ];
    if (!is_array($config)) {
        echo "Ошибка: Файл конфигурации вернул не массив. Тип: " . gettype($config) . "<br>";
        var_dump($config);
        die(); // Останавливаем выполнение
    }
        $this->host = $config['host'];
        $this->db_name = $config['db_name'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->charset = $config['charset'];
    }

    /**
     * Метод для получения активного соединения с базой данных.
     * Если соединение еще не установлено, он создает его.
     *
     * @return PDO|null Объект PDO при успешном соединении, иначе null.
     */
    public function getConnection() {
        $this->conn = null; // Обнуляем соединение перед попыткой подключения

        try {
            // Формируем DSN (Data Source Name) для PDO
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;

            // Опции PDO для лучшей обработки ошибок и режимов выборки
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Бросать исключения при ошибках
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // По умолчанию возвращать ассоциативные массивы
                PDO::ATTR_EMULATE_PREPARES   => false,                // Отключить эмуляцию подготовленных запросов для повышения безопасности
            ];

            // Создаем новый объект PDO (устанавливаем соединение)
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);

        } catch (PDOException $exception) {
            // В случае ошибки соединения, выводим сообщение об ошибке.
            // В продакшене лучше логировать ошибку, а не выводить её пользователю.
            echo "Ошибка подключения к базе данных: " . $exception->getMessage();
            // Можно также выбросить исключение или вернуть false
        }

        return $this->conn;
    }
}