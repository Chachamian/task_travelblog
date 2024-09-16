<?php
require_once 'autoload.php';
$fileSystem = new \App\FileSystem();
$travels = $fileSystem->getTravelsFileData();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Travel</title>
</head>
<body>
<div class="mt-5">
    <h2>Список всех путешествий</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Дата</th>
            <th>Местоположение</th>
            <th>Широта</th>
            <th>Долгота</th>
            <th>Изображение</th>
            <th>Стоимость</th>
            <th>Культурные места</th>
            <th>Места для посещения</th>
            <th>Оценка передвижения</th>
            <th>Оценка безопасности</th>
            <th>Оценка населенности</th>
            <th>Оценка растительности</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($travels as $travel): ?>
            <tr>
                <td><?php echo $travel->createdAt; ?></td>
                <td><?php echo $travel->location; ?></td>
                <td><?php echo $travel->latitude; ?></td>
                <td><?php echo $travel->longitude; ?></td>
                <td><img src="<?php echo $travel->image; ?>" alt="<?php echo $travel->location; ?>" style="width: 100px;"></td>
                <td><?php echo $travel->cost; ?> ₽</td>
                <td><?php echo $travel->culturalPlaces; ?></td>
                <td><?php echo $travel->visitPlaces; ?></td>
                <td><?php echo $travel->rating; ?></td>
                <td><?php echo $travel->safety; ?></td>
                <td><?php echo $travel->population_density; ?></td>
                <td><?php echo $travel->vegetation; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>