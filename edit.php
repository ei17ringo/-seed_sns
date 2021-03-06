<?php

 require('function.php');

  //ログインチェック
  login_check(); 

  // 宿題:個別ページの表示を完成させましょう
  
  // ヒント:$_GET["tweet_id"] の中に、表示したいつぶやきのtweet_idが格納されている
  // ヒント2:送信されてるtweet_idを使用して、SQL文でDBからデータを１件取得
  // ヒント3:取得できたデータを、一覧の１行分の表示を参考に、表示してみる

  // DBの接続
  require('dbconnect.php');

  // --------POST送信されていたら、つぶやきをINSERTで保存
  // $_POST["tweet"] => "" $_POSTが空だとおもわれない
  // $_POST["tweet"] => "" $_POST["tweet"]が空だと認識される

  if (isset($_POST) && !empty($_POST)){

    //入力チェック
    if ($_POST["tweet"] == ""){
      $error["tweet"] = "blank";
    }

    if (!isset($error)){
      //SQL文作成
      //Update文
      
      $sql = "UPDATE `tweets` SET `tweet` = ? WHERE `tweets`.`tweet_id` = ?;";


      //SQL文実行
      $data = array($_POST["tweet"],$_GET["tweet_id"]);
      $stmt = $dbh->prepare($sql);
      $stmt->execute($data);

      //一覧へ移動する
      header("Location: index.php");

    }
  }




  // SQL文の作成
  $sql = "SELECT `tweets`.*,`members`.`nick_name`,`members`.`picture_path` FROM `tweets` INNER JOIN `members` ON `tweets`.`member_id`=`members`.`member_id` WHERE `tweets`.`tweet_id`=".$_GET["tweet_id"];

  // SQL文実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

  // 個別ページに表示するデータを取得
  $one_tweet = $stmt->fetch(PDO::FETCH_ASSOC);




?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SeedSNS</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

  </head>
  <body>
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.html"><span class="strong-title"><i class="fa fa-twitter-square"></i> Seed SNS</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php">ログアウト</a></li>
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 content-margin-top">
        <h4>つぶやき編集</h4>
        <div class="msg">
          <form method="post" action="" class="form-horizontal" role="form">
              <!-- つぶやき -->
              <div class="form-group">
                <label class="col-sm-4 control-label">つぶやき</label>
                <div class="col-sm-8">
                  <textarea name="tweet" cols="50" rows="5" class="form-control" placeholder="例：Hello World!"><?php echo $one_tweet["tweet"]; ?></textarea>
                  <?php if (isset($error) && ($error["tweet"] == "blank")){ ?>
                    <p class="error">なにかつぶやいてください。</p>
                  <?php  } ?>
                </div>
              </div>
            <ul class="paging">
              <input type="submit" class="btn btn-info" value="変更保存">
            </ul>
          </form>
        </div>
        <a href="index.php">&laquo;&nbsp;一覧へ戻る</a>
      </div>
    </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
