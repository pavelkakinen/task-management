<?php

$books = [['Head First HTML and CSS', [['Elisabeth', 'Robson'], ['Eric', 'Freeman']], 5],
          ['Learning Web Design', [['Jennifer', 'Robbins']], 4],
          ['Head First Learn to Code', [['Eric', 'Freeman']], 4]];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <link rel="stylesheet" type="text/css" href="styles.css">

    <title></title>
</head>
<body>

<main>

    <div id="author-list">

        <div class="title-cell header-cell">Pealkiri</div>
        <div class="author-cell header-cell">Autorid</div>
        <div class="grade-cell header-cell">Hinne</div>

        <div class="flex-break header-divider"></div>

        <?php foreach ($books as $book): ?>
        <div>
            <?= $book[0] ?>
        </div>
        <div>
            <?php foreach ($book[1] as $index => $author): ?>
                <?php if ($index !== 0): ?>, <?php endif; ?>
                <?= $author[0] ?> <?= $author[1] ?>
            <?php endforeach; ?>
        </div>

        <div class="score-empty">
            <?php foreach (range(1, 5) as $grade): ?>
            <span class="<?= $book[2] >= $grade
                             ? 'score-filled' : ''  ?>">&#9733;</span>
            <?php endforeach; ?>
        </div>

        <div class="flex-break"></div>
        <?php endforeach; ?>

    </div>
</main>

</body>
</html>
