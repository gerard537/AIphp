<?php

// Подключаем модель Coffee
require_once __DIR__ . '/../models/Coffee.php';

class CoffeeController {
    private $coffeeModel;

    // Конструктор: создает экземпляр модели Coffee
    public function __construct() {
        $this->coffeeModel = new Coffee();
    }

    // Метод для отображения списка кофе
    public function index() {
        // Получаем все записи кофе из модели
        $coffees = $this->coffeeModel->getAll();
        // Включаем представление, которое отобразит эти данные
       require_once __DIR__ . '/../views/coffee/index.php';
       return $coffees;
    }

    // Метод для отображения формы создания нового кофе и обработки её отправки
    public function create() {
        // Если форма была отправлена методом POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Пытаемся создать новое кофе, передавая данные из $_POST
            if ($this->coffeeModel->create($_POST)) {
                // Если успешно, перенаправляем пользователя на страницу списка кофе
                header('Location: coffees');
                exit; // Важно завершить выполнение скрипта после перенаправления
            } else {
                // В случае ошибки, можно отобразить сообщение или перенаправить на страницу ошибки
                echo "Ошибка при добавлении кофе.";
            }
        }
        // Если запрос был GET (или форма еще не отправлена), показываем форму
        require_once __DIR__ . '/../views/coffee/create.php';
    }

    // Метод для отображения формы редактирования существующего кофе и обработки её отправки
    public function edit($id) {
        // Сначала пытаемся получить данные о кофе по ID
        $coffee = new Coffee();
        if ($coffee->getById($id)) {
            // Если кофе найдено и форма отправлена методом POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Пытаемся обновить кофе
                if ($coffee->update($id, $_POST)) {
                    // Если успешно, перенаправляем на страницу списка
                    header('Location: CoffeeWebSite/coffees');
                    exit;
                } else {
                    echo "Ошибка при обновлении кофе.";
                }
            }
        $current_coffee = $coffee;
            // Если кофе найдено и запрос GET, показываем форму с текущими данными
            require_once __DIR__ . '/../views/coffee/edit.php';
        } else {
            // Если кофе не найдено
            echo "Кофе не найдено.";
        }
    }

    // Метод для удаления кофе
    public function delete($id) {
        // Проверяем, что запрос действительно POST (для безопасности, чтобы избежать случайных удалений по GET-запросу)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->coffeeModel->delete($id)) {
                header('Location: /coffees');
                exit;
            } else {
                echo "Ошибка при удалении кофе.";
            }
        } else {
            // Если запрос не POST, можно перенаправить или показать ошибку
            header('Location: /coffees'); // Или на страницу ошибки
            exit;
        }
    }
}
?>