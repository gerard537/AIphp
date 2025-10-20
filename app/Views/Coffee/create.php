<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить Новое Кофе</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        form { background-color: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 500px; margin-top: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Учитываем padding и border в ширине */
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover { background-color: #0056b3; }
        .back-link { display: block; margin-top: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Добавить Новое Кофе</h1>

    <form action="/CoffeeWebSite/coffees/create/" method="POST">
        <label for="name">Название:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="origin">Происхождение:</label>
        <input type="text" id="origin" name="origin" required><br>

        <label for="roast_level">Уровень обжарки:</label>
        <select id="roast_level" name="roast_level" required>
            <option value="">Выберите уровень</option>
            <option value="Light">Светлая</option>
            <option value="Medium">Средняя</option>
            <option value="Dark">Темная</option>
        </select><br>

        <label for="price">Цена:</label>
        <input type="number" id="price" name="price" step="0.01" required><br>

        <label for="description">Описание:</label>
        <textarea id="description" name="description" rows="5"></textarea><br>

        <button type="submit">Добавить Кофе</button>
    </form>

    <a href="/CoffeeWebSite/coffees/" class="back-link">Вернуться к списку</a>
</body>
</html>