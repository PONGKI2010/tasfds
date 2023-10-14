<?php
// 라이선스 파일 경로
$license_file = "licenses.txt";

// 라이선스 키 추가
if (isset($_POST['add_license'])) {
    $license_key = $_POST['license_key'];
    $expiration_date = $_POST['expiration_date'];
    $license_data = $license_key . ":" . $expiration_date . PHP_EOL;
    
    if (file_put_contents($license_file, $license_data, FILE_APPEND | LOCK_EX)) {
        echo "라이선스 키가 추가되었습니다.";
    } else {
        echo "라이선스 키 추가 오류.";
    }
}

// 라이선스 키 삭제
if (isset($_POST['delete_license'])) {
    $license_key = $_POST['license_key'];
    $contents = file($license_file);
    $new_contents = "";

    foreach ($contents as $line) {
        $parts = explode(":", $line);
        if ($parts[0] != $license_key) {
            $new_contents .= $line;
        }
    }

    if (file_put_contents($license_file, $new_contents)) {
        echo "라이선스 키가 삭제되었습니다.";
    } else {
        echo "라이선스 키 삭제 오류.";
    }
}

// 모든 라이선스 키와 유효기간 가져오기
$contents = file($license_file);
?>

<!DOCTYPE html>
<html>
<head>
    <title>라이선스 관리</title>
</head>
<body>
    <h1>라이선스 관리</h1>

    <!-- 라이선스 키 추가 폼 -->
    <h2>라이선스 키 추가</h2>
    <form method="post">
        License Key: <input type="text" name="license_key" required>
        Expiration Date: <input type="date" name="expiration_date" required>
        <input type="submit" name="add_license" value="추가">
    </form>

    <!-- 라이선스 키 삭제 폼 -->
    <h2>라이선스 키 삭제</h2>
    <form method="post">
        License Key: <input type="text" name="license_key" required>
        <input type="submit" name="delete_license" value="삭제">
    </form>

    <!-- 라이선스 키 및 유효기간 표시 -->
    <h2>라이선스 키 및 유효기간</h2>
    <table border="1">
        <tr>
            <th>라이선스 키</th>
            <th>유효기간</th>
        </tr>
        <?php
        if (!empty($contents)) {
            foreach ($contents as $line) {
                $parts = explode(":", $line);
                echo "<tr><td>" . $parts[0] . "</td><td>" . $parts[1] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>라이선스 키가 없습니다.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
