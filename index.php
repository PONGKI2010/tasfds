<?php
$filename = 'licenses.txt';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action === 'verify_license') {
        $user_key = $_POST['user_key'];
        $current_date = date("Y-m-d");

        $file_contents = file_get_contents($filename);
        if (strpos($file_contents, "License Key: $user_key") !== false) {
            $lines = explode("\n", $file_contents);
            foreach ($lines as $line) {
                if (strpos($line, "License Key: $user_key") !== false) {
                    $parts = explode("Expiration Date: ", $line);
                    $expiration_date = trim($parts[1]);
                    if ($current_date <= $expiration_date) {
                        echo "valid";
                    } else {
                        echo "expired";
                    }
                    break;
                }
            }
        } else {
            echo "not_found";
        }
        exit; // 중요: 결과를 출력한 후 프로그램을 종료합니다.
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>라이선스 키 확인</title>
</head>
<body>
    <h1>라이선스 키 확인</h1>
    <form action="" method="post" id="licenseForm">
        <input type="hidden" name="action" value="verify_license">
        License Key: <input type="text" name="user_key" required><br>
        <input type="button" value="라이선스 키 확인" onclick="verifyLicense()">
    </form>

    <div id="result"></div>

    <script>
        function verifyLicense() {
            var user_key = document.querySelector("input[name='user_key']").value;
            var resultDiv = document.getElementById('result');
            var form = document.getElementById('licenseForm');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = xhr.responseText;
                        if (response === 'valid') {
                            resultDiv.innerHTML = '라이선스 키가 유효합니다.';
                            resultDiv.style.color = 'green';
                        } else if (response === 'expired') {
                            resultDiv.innerHTML = '라이선스 키의 유효기한이 만료되었습니다.';
                            resultDiv.style.color = 'red';
                        } else if (response === 'not_found') {
                            resultDiv.innerHTML = '라이선스 키를 찾을 수 없습니다.';
                            resultDiv.style.color = 'red';
                        }
                    } else {
                        resultDiv.innerHTML = '오류가 발생했습니다.';
                        resultDiv.style.color = 'red';
                    }
                }
            };

            var formData = new FormData(form);
            xhr.send(formData);
        }
    </script>
</body>
</html>
