<?php

$errors = ['Pealkiri peab olema 2 kuni 10 märki', 'Hinne peab olema määratud'];
$title = 'Head First HTML and CSS';
$gradeValue = 4;
$isRead = true;
$isEditForm = true;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <link rel="stylesheet"
          type="text/css" href="styles.css">

    <title></title>
</head>
<body>

<main>

    <?php if ($errors): ?>
    <div id="error-block">
        <?php foreach ($errors as $error): ?>
            <strong><?= $error ?></strong><br>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form id="input-form">

        <div class="label-cell"><label for="title">Pealkiri:</label></div>
        <div class="input-cell"><input
                id="title"
                name="title"
                value="<?= $title ?>"
                type="text" /></div>

        <div class="label-cell">Hinne: </div>
        <div class="input-cell">
            <?php foreach (range(1, 5) as $grade): ?>
                <label for="grade<?= $grade ?>"><?= $grade ?></label>
                <input type="radio"
                    id="grade<?= $grade ?>"
                       name="grade"
                    <?= $grade === $gradeValue ? 'checked' : '' ?>
                       value="<?= $grade ?>">
            <?php endforeach; ?>
        </div>

        <div class="flex-break"></div>

        <div class="label-cell"><label for="read">Loetud:</label></div>
        <div class="input-cell">
            <input name="isRead"
                id="read"
                <?= $isRead ? 'checked' : '' ?>
                type="checkbox" /></div>

        <div class="flex-break"></div>

        <div class="label-cell"></div>
        <div class="input-cell button-cell">
            <?php if ($isEditForm): ?>
            <input name="deleteButton"
                   class="danger"
                   type="submit"
                   value="Kustuta">
            <?php endif; ?>

            <input name="submitButton" type="submit" value="Salvesta">
        </div>
    </form>
</main>

</body>
</html>
