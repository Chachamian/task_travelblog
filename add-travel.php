<?php

use App\Exception\ValidateException;

require_once 'autoload.php';

$auth = new \App\Authorize();
if ($auth->user() === null) {
    header('Location: /login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
    $imagePath = '';

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 10 * 1024 * 1024;

    if (isset($_FILES['userpic-file']) && $_FILES['userpic-file']['error'] === 0) {
        if (!in_array($_FILES['userpic-file']['type'], $allowedTypes)) {
            throw new ValidateException('Неподдерживаемый тип файла. Допустимы только JPG, PNG и GIF.');
        }

        if ($_FILES['userpic-file']['size'] > $maxFileSize) {
            throw new ValidateException('Размер файла превышает 10 МБ.');
        }

        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
        $file_name = uniqid() . '-' . basename($_FILES['userpic-file']['name']);
        move_uploaded_file($_FILES['userpic-file']['tmp_name'], $file_path . $file_name);
        $imagePath = '/uploads/' . $file_name;
    }

    // Создание объекта путешествия
    $travel = new \App\Mapper\Travel(
        null,
        (new \App\Authorize())->user()->id,
        $_POST['location'],
        (float)$_POST['latitude'],
        (float)$_POST['longitude'],
        $imagePath,
        (float)$_POST['cost'],
        $_POST['heritage_sites'],
        $_POST['places_to_visit'],
        (int)$_POST['comfort'],
        (int) $_POST['safety'],
        (int) $_POST['population_density'],
        (int) $_POST['vegetation'],
    );

    // Обработка данных путешествия
    $fileSystem = new \App\FileSystem();
    $travelsData = $fileSystem->getTravelsFileData();
    $travel->id = $fileSystem->GetIncrement('travels');
    $travelsData[] = $travel;

    $fileSystem->writeTravelsFileData($travelsData);

    header('Location: /');
}

?>
<html lang="en">
<head>
    <title>Travel</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
<div class="container mt-5">
    <h2>Добавить Путешествие</h2>
    <form action="/add-travel.php" method="post" enctype="multipart/form-data">
        <!-- Местоположение с привязкой к геопозиции -->
        <div class="mb-3">
            <label for="location" class="form-label">Местоположение (с привязкой к геопозиции)</label>
            <input type="text" class="form-control" id="location" name="location" required>
            <input type="text" id="latitude" name="latitude" class="form-control">
            <input type="text" id="longitude" name="longitude" class="form-control">
        </div>

        <!-- Изображение мест -->
        <div class="mb-3">
            <label for="image" class="form-label">Изображение мест</label>
            <input type="file" class="form-control" id="image" name="userpic-file" accept="image/*">
        </div>

        <!-- Стоимость путешествия -->
        <div class="mb-3">
            <label for="cost" class="form-label">Стоимость путешествия</label>
            <input type="number" class="form-control" id="cost" name="cost" required>
        </div>

        <!-- Места культурного наследия -->
        <div class="mb-3">
            <label for="heritage_sites" class="form-label">Места культурного наследия</label>
            <textarea class="form-control" id="heritage_sites" name="heritage_sites" rows="3"></textarea>
        </div>

        <!-- Места для посещения -->
        <div class="mb-3">
            <label for="places_to_visit" class="form-label">Места для посещения</label>
            <textarea class="form-control" id="places_to_visit" name="places_to_visit" rows="3"></textarea>
        </div>

        <!-- Оценка удобства -->
        <div class="mb-3">
            <label for="comfort" class="form-label">Оценка передвижения (1-5)</label>
            <select class="form-select" id="comfort" name="comfort">
                <option value="1">1 - Очень неудобно</option>
                <option value="2">2 - Неудобно</option>
                <option value="3">3 - Нормально</option>
                <option value="4">4 - Удобно</option>
                <option value="5">5 - Очень удобно</option>
            </select>
        </div>

        <!-- Оценка безопасности -->
        <div class="mb-3">
            <label for="safety" class="form-label">Оценка безопасности (1-5)</label>
            <select class="form-select" id="safety" name="safety">
                <option value="1">1 - Очень небезопасно</option>
                <option value="2">2 - Небезопасно</option>
                <option value="3">3 - Нормально</option>
                <option value="4">4 - Безопасно</option>
                <option value="5">5 - Очень безопасно</option>
            </select>
        </div>

        <!-- Оценка населения -->
        <div class="mb-3">
            <label for="population_density" class="form-label">Оценка населенности (1-5)</label>
            <select class="form-select" id="population_density" name="population_density">
                <option value="1">1 - Очень малонаселено</option>
                <option value="2">2 - Мало людей</option>
                <option value="3">3 - Средняя плотность</option>
                <option value="4">4 - Много людей</option>
                <option value="5">5 - Очень плотная застройка</option>
            </select>
        </div>

        <!-- Оценка растительности -->
        <div class="mb-3">
            <label for="vegetation" class="form-label">Оценка растительности (1-5)</label>
            <select class="form-select" id="vegetation" name="vegetation">
                <option value="1">1 - Плохо</option>
                <option value="2">2 - Умеренно</option>
                <option value="3">3 - Нормально</option>
                <option value="4">4 - Хорошо</option>
                <option value="5">5 - Отлично</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Добавить Путешествие</button>
    </form>
</div>

<script>
    navigator.geolocation.getCurrentPosition(function(position) {
        document.getElementById('latitude').value = position.coords.latitude;
        document.getElementById('longitude').value = position.coords.longitude;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
