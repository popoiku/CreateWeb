<?php

class Dbget {

  // 【SQLインジェクション対策】
  // ・静的プレースホルダを指定する
  // ・prepare()でSQL文をセット
  // ・入力される変数に対してバインド機構を利用し、更に型を指定

  // db接続
  public function con() {

    $dsn = '非公開';
    $user = '非公開';
    $password = '非公開';

    try {
      $dbh = new PDO($dsn, $user, $password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      return $dbh;
    } catch (PDOException $e){
      exit($e->getMessage());
    }
  }

  // 名称検索
  public function name_search($inputword){
    
    $keyword = preg_replace('/( |　)/','',$inputword);
    $keyword = "%".$keyword."%";
    $sql = 'SELECT id , brand , img FROM cat_guide WHERE brand COLLATE utf8_unicode_ci LIKE :keyword';
    $pdo = $this->con();

    $stt = $pdo->prepare($sql);
    $stt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
    $stt->execute();

    $aryItem = $stt->fetchAll(PDO::FETCH_ASSOC);

    return $aryItem;
  }

  // 全件検索
  public function all_search() {
    $pdo = $this->con();
    $all = $pdo->prepare('SELECT id , brand , img FROM cat_guide');
    $all->execute();

    $allItem = $all->fetchAll(PDO::FETCH_ASSOC);

    return $allItem;
  }

  //id検索
  public function id_search($getid) {
    $sql = 'SELECT * FROM cat_guide WHERE id = ?';
    $pdo = $this->con();

    $individual = $pdo->prepare($sql);
    $individual->bindParam(1, $getid, PDO::PARAM_INT);
    $individual->execute();

    $individualItem = $individual->fetchAll(PDO::FETCH_ASSOC);

    return $individualItem;
  }

}


?>