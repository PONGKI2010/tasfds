<?php
// 파일 경로
$filename = 'licenses.txt';

// POST 요청으로 받은 작업
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action === 'create') {
        $user_key = $_POST['user_key'];
        $expiration_date = $_POST['expiration_date'];

        // 라이선스 키와 만료 날짜를 텍스트 파일에 저장
        $data = "License Key: $user_key\nExpiration Date: $expiration_date\n\n";

        // 파일에 데이터 추가
        file_put_contents($filename, $data, FILE_APPEND);

        echo "라이선스 키가 성공적으로 생성되었습니다.";
    } elseif ($action === 'delete') {
        $user_key = $_POST['user_key'];

        // 라이선스 키를 텍스트 파일에서 삭제 (비활성화 대신 삭제)
        $file_contents = file_get_contents($filename);
        $new_contents = str_replace("License Key: $user_key", "License Key: $user_key (Inactive)", $file_contents);

        // 파일에 새로운 내용을 쓰고 기존 파일 덮어쓰기
        file_put_contents($filename, $new_contents);

        echo "라이선스 키가 성공적으로 삭제되었습니다.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>라이선스 키 관리</title>
</head>
<body>
    <h1>라이선스 키 관리</h1>
    <h2>라이선스 키 생성</h2>
    <form action="" method="post">
        <input type="hidden" name="action" value="create">
        License Key: <input type="text" name="user_key" required><br>
        Expiration Date: <input type="date" name="expiration_date" required><br>
        <input type="submit" value="라이선스 키 생성">
    </form>

    <h2>라이선스 키 삭제/수정</h2>
    <form action="" method="post">
        <input type="hidden" name="action" value="delete">
        License Key to Delete/Deactivate: <input type="text" name="user_key" required><br>
        <input type="submit" value="라이선스 키 삭제/비활성화">
    </form>
</body>
</html>
