<?php
// デバッグ用
// ini_set('display_errors', 1);

    session_start();

    ini_set('session.gc_maxlifetime', 686400);

    $creative_code = 0;

    $code = $_POST['code'];
    if (isset($_POST['creativecode'])) {
        $creative_code = $_POST['creativecode'];
    }

    require_once ('../vars.php');

    // 文字化け対策
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

    // PHPのエラーを表示するように設定
    error_reporting(E_ALL & ~E_NOTICE);

    // データベースの接続
    try {
        $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, $options);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }

    // SQL
    $check_code_sql = "SELECT * FROM codes WHERE code = BINARY :code";

    //post送信されてきた認証コードがデータベースにあるか検索
    try {
        $stmt = $dbh->prepare($check_code_sql);
        $stmt->execute(array(':code' => $code));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['code'] == "") {
        // 認証コードが存在しない
        if ($creative_code > 0) {
            header("Location: ./?error=3&creativecode=".$creative_code);
        } else {
            header("Location: ./?error=3");
        }

        exit();
    }

    }
    catch (PDOExeption $e) {
        if ($creative_code > 0) {
            header("Location: ./?error=1&creativecode=".$creative_code);
        } else {
            header("Location: ./?error=1");
        }
    }

    //検索したユーザー名に対してパスワードが正しいかを検証
    $date = new DateTime(date('Y-m-d'));
    $due_date = new DateTime($result['duedate']);
    if ($date <= $due_date) {
        session_regenerate_id(TRUE);
        $_SESSION["isLogin"] = true;

        if ($creative_code > 0) {
            header("Location: ../details/?creativecode=".$creative_code);
        } else {
            header("Location: ../");
        }

        exit();
    }
    else {
        if ($creative_code > 0) {
            header("Location: ./?error=2&creativecode=".$creative_code);
        } else {
            header("Location: ./?error=2");
        }
    }


?>