<?php
function getTasks() {
    if (!file_exists('tasks.txt')) return [];

    $tasks = [];
    $lines = file('tasks.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if (count($parts) === 2) {
            $tasks[] = [
                    'description' => urldecode($parts[0]),
                    'estimate' => (int)$parts[1]
            ];
        }
    }
    return $tasks;
}

$tasks = getTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
</head>
<body id="task-list-page">

<table border="1" width="100%">
    <tr>
        <td>Tasks</td>
    </tr>
    <tr>
        <td>
            <?php foreach ($tasks as $task): ?>
                <table border="1" width="100%">
                    <tr>
                        <td colspan="2" height="32px">
                            <?= htmlspecialchars($task['description']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td height="32px" width="50%">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <input type="checkbox" <?= $i <= $task['estimate'] ? 'checked' : '' ?> disabled>
                            <?php endfor; ?>
                        </td>
                        <td height="32px" width="50%" align="right">
                            Estimate: <?= $task['estimate'] ?>/5
                        </td>
                    </tr>
                </table>
            <?php endforeach; ?>
        </td>
    </tr>
</table>

</body>
</html>