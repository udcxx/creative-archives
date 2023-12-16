<?php

    $code = $_POST['code'];
    $duedate = $_POST['duedate'];

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

    // 検索用のSQL
    $check_code_sql = "SELECT * FROM codes WHERE code = BINARY :code";
    // 登録用のSQL文
    $add_aql = "INSERT INTO codes (code, duedate) VALUES (:code, :duedate)";


    //post送信されてきた認証コードがデータベースにあるか検索
    try {
        $stmt = $dbh->prepare($check_code_sql);
        $stmt->execute(array(':code' => $code));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['code'] == "") {
            // 認証コードが存在しない
            // 登録する
            $judgement = false;
            $stmt = $dbh->prepare($add_aql);
            $stmt->execute(array(':code' => $code, ':duedate' => $duedate));

            header("Location: ./?create=".$code);

            exit();
        } else {
            // 認証コードが存在する
            // エラーで返す
            header("Location: ./?error=1");
        }
    }
    catch (PDOExeption $e) {
        // サーバーエラー
        header("Location: ./?error=2");
    }
?>