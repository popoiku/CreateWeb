<?php

class Dbget {

  // データベース接続
  public function connection() {
    try {
      $dbh = new PDO(
        '非公開', 
        '非公開', 
        '非公開',
        // DB接続設定時にオプションを登録
        [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
      );
      return $dbh;
    } catch (PDOException $e){
      exit($e->getMessage());
    }
  }

  // ログインユーザー存在確認
  public function loginJudg($user) {
    $pdo = $this->connection();
    $sql = 'SELECT id, login_id, login_pass FROM user WHERE login_id = :user';
    $pre = $pdo->prepare($sql);
    $pre->bindparam(':user', $user, PDO::PARAM_STR);
    $pre->execute();
    $member = $pre->fetch();
    return $member;
  }

  // ログイン情報保存
  public function access($id, $daytime) {
    $pdo = $this->connection();
    $sql = 'INSERT INTO access_log (user_id, login) VALUES (:id, :daytime)';
    $pre = $pdo->prepare($sql);
    $pre->bindparam(':id', $id, PDO::PARAM_INT);
    $pre->bindparam(':daytime', $daytime, PDO::PARAM_STR);
    $pre->execute();
  }

  // SELECT関数（ユーザー入力無し）
  public function selectOrder($sql){
    $pdo = $this->connection();
    $pre = $pdo->prepare($sql);
    $pre->execute();
    $result = $pre->fetchAll();
    return $result;
  }

  // アニメタイトルid取得
  public function titleSelect($animetitle) {
    $pdo = $this->connection();
    $sql = 'SELECT id FROM title WHERE animation_title = :animetitle';
    $pre = $pdo->prepare($sql);
    $pre->bindparam(':animetitle', $animetitle, PDO::PARAM_STR);
    $pre->execute();
    $result = $pre->fetch();
    return $result;
  }

  // アニメタイトル登録
  public function unregisteredAnime($insertTitle, $insertLink) {
    $pdo = $this->connection();
    $sql = 'INSERT INTO title (animation_title, official_link) VALUES (:inserttitle, :insertlink)';
    $pre = $pdo->prepare($sql);
    $pre->bindparam(':inserttitle', $insertTitle, PDO::PARAM_STR);
    $pre->bindparam(':insertlink', $insertLink, PDO::PARAM_STR);
    $result = $pre->execute();
    return $result;
  }
  
  // 編集履歴登録
  public function editHistry($userName, $titleName, $commitType){
    date_default_timezone_set('Asia/Tokyo');
    $commitDay = date("Y-m-d H:i:s");
    $pdo = $this->connection();
    $sql = 'call st_editInsert(?, ?, ?, ?)';
    $pre = $pdo->prepare($sql);
    $pre->bindparam(1, $userName, PDO::PARAM_STR);
    $pre->bindparam(2, $titleName, PDO::PARAM_STR);
    $pre->bindparam(3, $commitType, PDO::PARAM_INT);
    $pre->bindparam(4, $commitDay, PDO::PARAM_STR);
    $result = $pre->execute();
    return $result;
  }
  
  // 放送局id取得
  public function broadcastConfirm($broadCast){
    $pdo = $this->connection();
    $sql = 'SELECT id FROM broadcaster WHERE broadcaster_name = :broadcast';
    $pre = $pdo->prepare($sql);
    $pre->bindparam(':broadcast', $broadCast, PDO::PARAM_STR);
    $pre->execute();
    $result = $pre->fetch();
    return $result;
  }
  
  // 放送局追加
  public function addBroadcast($broadcastName){
    $pdo = $this->connection();
    $sql = 'INSERT INTO broadcaster (broadcaster_name) VALUES (:broadcastname)';
    $pre = $pdo->prepare($sql);
    $pre->bindparam(':broadcastname', $broadcastName, PDO::PARAM_STR);
    $result = $pre->execute();
    return $result;
  }
  
  // スケジュール追加
  public function addShedule($titleId, $broadcasterId, $joinDate, $joinTime, $joinFirsttime){
    $pdo = $this->connection();
    $sql = 'INSERT INTO schedule (title_id, broadcaster_id, airdate, airtime, first_airtime) VALUES (?, ?, ?, ?, ?)';
    $pre = $pdo->prepare($sql);
    $pre->bindparam(1, $titleId, PDO::PARAM_INT);
    $pre->bindparam(2, $broadcasterId, PDO::PARAM_INT);
    $pre->bindparam(3, $joinDate, PDO::PARAM_STR);
    $pre->bindparam(4, $joinTime, PDO::PARAM_STR);
    $pre->bindparam(5, $joinFirsttime, PDO::PARAM_STR);
    $result = $pre->execute();
    return $result;
  }

}

class Reuse {

  // アニメ放送季節特定
  public function animeCours($year, $month){

    if ($month >= 4 && $month <= 6){

      return $year.',春';

    } else if ($month >= 7 && $month <= 9){

      return $year.',夏';

    } else if ($month >= 10 && $month <= 12){

      return $year.',秋';

    } else {

      return $year.',冬';

    }

  }

  // 配列作成
  public function selectData(){

    $date = new Datetime();
    $year = $date->format('Y');
    $year += 5;
    $month = $date->format('n');
    $season = [];

    for($i = 0; $i < 10; $i++){

      for($n = 0; $n = $month;){

        $re = $this->animeCours($year, $month);
        array_push($season, $re);

        $month--;
      
      }
      
      $month = 12;
      $year--;
    
    }

    return $season;

  }



}


?>