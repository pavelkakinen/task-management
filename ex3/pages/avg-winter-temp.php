<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Talve keskmine temperatuur</title>
    <link href="pages/styles.css" rel="stylesheet">
</head>
<body>

<?php include 'menu.html' ?>

<h5>Milline oli valitud aastal talve keskmine temperatuur?</h5>

<form method="POST" action="?">

    <table>
        <tr>
            <td><label for="year">Aasta</label>:</td>
            <td>
                <select name="year" id="year">
                    <option value="2021/2022">2021/2022</option>
                    <option value="2022/2023">2022/2023</option>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <br>
                <button type="submit" name="command"
                        value="avg-winter-temp">Arvuta</button>
            </td>
        </tr>
    </table>
</form>

</body>
</html>