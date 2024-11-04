<?php
// Параметры подключения к базе данных
define("SERVERNAME", "localhost");
define("USERNAME", "username");
define("PASSWORD", "password");
define("DBNAME", "database");

function loadXmlToDatabase($filepath) {
    $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DBNAME);
    if (!$conn) {
        die("Ошибка подключения: " . mysqli_connect_error());
    }

    $domxml = new DOMDocument();
    $domxml->load($filepath);

    $number = $domxml->getElementsByTagName('NUMBER')->item(0)->textContent;
    $date = $domxml->getElementsByTagName('DATE')->item(0)->textContent;
    $sender = $domxml->getElementsByTagName('SENDER')->item(0)->textContent;
    $recipient = $domxml->getElementsByTagName('RECIPIENT')->item(0)->textContent;

    // Сохраняем массив тегов
    $positions = $domxml->getElementsByTagName('POSITION');
    $body = [];
    foreach ($positions as $position) {
        $data = [];
        foreach ($position->childNodes as $child) {
            if ($child->nodeType == XML_ELEMENT_NODE) {
                $data[$child->nodeName] = $child->textContent;
            }
        }
        $body[] = $data;
    }
    $body_json = json_encode($body);

    $sql = "INSERT INTO desadv (NUMBER, DATE, SENDER, RECIPIENT, BODY) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssss', $number, $date, $sender, $recipient, $body_json);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "Ошибка SQL запроса: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}

// Проверка
loadXmlToDatabase("desadv_1111.xml");

?>