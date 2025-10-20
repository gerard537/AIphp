<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список Кофе</title>
    <style>
        /* ... твои стили ... */
    </style>
</head>
<body>
    <h1>Наше Кофе</h1>

    <a href="/CoffeeWebSite/coffees/create" class="add-link">Добавить новое кофе</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Происхождение</th>
                <th>Обжарка</th>
                <th>Цена</th>
                <th>Описание</th>
                <th>Дата создания</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
            $hasCoffees = false;
            if ($coffees) {
                while ($row = $coffees->fetch(PDO::FETCH_ASSOC)) {
                    $hasCoffees = true; // Если хотя бы одна строка получена, устанавливаем флаг
                    // Используем htmlspecialchars для предотвращения XSS атак при выводе данных
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['origin']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['roast_level']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "<td class='actions'>";
                    echo "<a href='/CoffeeWebSite/coffees/edit/" . htmlspecialchars($row['id']) . "'>Редактировать</a>";
                    echo "<form action='/coffees/delete/" . htmlspecialchars($row['id']) . "' method='POST' style='display:inline;'>";
                    echo "<button type='submit' onclick='return confirm(\"Вы уверены, что хотите удалить это кофе?\")'>Удалить</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            }

            // Если флаг остался false после цикла, значит кофе нет
            if (!$hasCoffees) {
                echo "<tr><td colspan='8'>Кофе пока нет.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>