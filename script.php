<?php
$start = microtime(true);

session_start();

if(!isset($_SESSION["requestsCount"])){
    $_SESSION["requestsCount"] = 0;
}
?>
<html>
<head>
    <meta charset="utf-8">
    <title>Result</title>
    <style>
        body {
            background: #e5f8ff;
            font-family: monospace;
            color: white;
        }

        .block {
            background: #5eaecc;
            box-shadow: 0 0 5px #999999;

            margin-left: auto;
            margin-right: auto;
            width: 700px;
            padding: 20px;
            margin-bottom: 20px;

            border-radius: 30px;

            transition: 1s;
        }

        .block:hover {
            box-shadow: 0 0 20px #999999;
        }

        button {
            display: block;
            height: 50px;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
            padding: 0;

            background-color: #5eaecc;
            border: none;
            border-radius: 30px;
            box-shadow: 0 0 5px #999999;

            font-family: monospace;
            font-size: 20px;
            color: white;

            transition: 500ms;
        }

        button:hover {
            cursor: pointer;
            background-color: #61c4e2;
        }

        table {
            margin-left: auto;
            margin-right: auto;
            padding: 10px;
            width: 100%;
            border-radius: 10px;

            font-size: 20px;
            background: #e5f8ff;
        }

        th {
            margin: 10px;
            padding: 10px;
            background: #5eaecc;
            width: auto;
        }

        td {
            width: auto;
            background: #5eaecc;
            text-align: center;
            padding: 10px;
        }

    </style>
</head>
<body>
    <div class="block">
        <table class="results">
            <tr>
                <th>X</th>
                <th>Y</th>
                <th>R</th>
                <th>Result</th>
                <th>Script runtime</th>
                <th>Script timestamp</th>
            </tr>
        <?php
        date_default_timezone_set("Europe/Moscow");

        function isInside($x, $y, $r) {
            if ($x <= 0 && $y >= 0 && $x*$x+$y*$y <= $r*$r) {
               return true;
            }
            if ($x >= 0 && $y >= 0 && $x <= $r && $y <= $r) {
                return true;
            }
            if ($x <= 0 && $y <= 0 && $y >= -$x/2 - $r/2) {
                return true;
            }
            return false;
        }

        function extendTable($i) {
            echo "<tr><td>" . $_SESSION[$i."x"]
                . "</td><td>" . $_SESSION[$i."y"]
                . "</td><td>" . $_SESSION[$i."r"]
                . "</td><td>" . $_SESSION[$i."result"]
                . "</td><td>" . $_SESSION[$i."runtime"]
                . "</td><td>" . $_SESSION[$i."time"]
                . "</td></tr>";
        }

        function validateNumbers() {
            $xIsOk = false;
            if (is_numeric($_GET['x']) && strlen($_GET['x']) <= 5) {
                if ($_GET["x"] > -3 && $_GET["x"] < 5) {
                    $xIsOk = true;
                }
            }

            $yIsOk = false;
            if (is_numeric($_GET['y'])) {
                if (fmod($_GET["y"], 1) == 0 && $_GET["y"] >= -5 && $_GET["y"] <= 3) {
                    $yIsOk = true;
                }
            }

            $rIsOk = false;
            if (is_numeric($_GET['r'])) {
                if (fmod($_GET["r"], 0.5) == 0 && $_GET["r"] >= 1 && $_GET["r"] <= 3) {
                    $rIsOk = true;
                }
            }

            if ($xIsOk && $yIsOk && $rIsOk) {
                return true;
            } else return false;
        }
        //Печатаем таблицу со всеми предыдущими данными из $_SESSION
        for ($i = 0; $i < $_SESSION["requestsCount"]; $i++) {
            extendTable($i);
        }

        if (isset($_GET["x"], $_GET["y"], $_GET["r"]) && validateNumbers()) {

            $x = $_GET["x"];
            $y = $_GET["y"];
            $r = $_GET["r"];

            //Добавляем в $_SESSION текущие данные
            $currentRequestId = $_SESSION["requestsCount"];

            $_SESSION[$currentRequestId."x"] = $x;
            $_SESSION[$currentRequestId."y"] = $y;
            $_SESSION[$currentRequestId."r"] = $r;
            $_SESSION[$currentRequestId."result"] = isInside($x, $y, $r) ? "true" : "false";
            $_SESSION[$currentRequestId."runtime"] = round(microtime(true) - $start, 6) . " s";
            $_SESSION[$currentRequestId."time"] = date("d/m/Y h:i:s a", time());

            //Расширяем таблицу для текущего запроса
            extendTable($_SESSION["requestsCount"]);

            //Увеличиваем кол-во запросов на 1
            $_SESSION["requestsCount"]++;
        }
        ?>
        </table>
    </div>
</body>
</html>
