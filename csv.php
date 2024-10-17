<?php
require_once('db_connect.php');

try {
  // データベースに接続
  $dbh = db_connect();

  //例外処理を投げるようにする（throw）
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // データベースから値を取ってきたり、 データを挿入したりする処理 
  $statement = $dbh->query('SELECT * FROM member');    // 例
  $users = $statement->fetchAll(PDO::FETCH_ASSOC);

  //データベース接続切断
  $statement = null;
  $dbh = null;

} catch (PDOException $e) {
  header('Content-Type: text/plain; charset=UTF-8', true, 500);
  // エラー内容は本番環境ではログファイルに記録して， Webブラウザには出さないほうが望ましい
  exit($e->getMessage()); 
}

$data = $users;

function putCsv($data) {

    try {

        //CSV形式で情報をファイルに出力のための準備
        $csvFileName = '/tmp/' . time() . rand() . '.csv';
        $fileName = time() . rand() . '.csv';
        $res = fopen($csvFileName, 'w');
        if ($res === FALSE) {
            throw new Exception('ファイルの書き込みに失敗しました。');
        }

        // 項目名先に出力
        $header = ["ID", "名前", "年齢", "生年月日", "性別", "職業", "趣味", "身長"];
        fputcsv($res, $header);

        // ループしながら出力
        foreach($data as $key => $dataInfo) {
            // 文字コード変換。エクセルで開けるようにする
            mb_convert_encoding($dataInfo, 'SJIS-win', 'UTF-8');

            // ファイルに書き出しをする
            fputcsv($res, $dataInfo);
        }

        // ファイルを閉じる
        fclose($res);

        // ダウンロード開始

        // ファイルタイプ（csv）
        header('Content-Type: application/octet-stream');

        // ファイル名
        header('Content-Disposition: attachment; filename=' . $fileName); 
        // ファイルのサイズ　ダウンロードの進捗状況が表示
        header('Content-Length: ' . filesize($csvFileName)); 
        header('Content-Transfer-Encoding: binary');
        // ファイルを出力する
        readfile($csvFileName);

    } catch(Exception $e) {

        // 例外処理をここに書きます
        echo $e->getMessage();

    }
}

putCsv($data);