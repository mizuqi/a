<html>
<head>
  <meta charset="UTF-8">
  <title>mission_5</title>
</head>
<body>
  <?php
  //データベース等の準備
  $dsn="mysql:dbname=*******;host=*******";
  $user="*******";
  $password="*******";
  $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
  $c_time=date("Y/m/d H:i:s");

  //投稿内容をデータベースに保存
  if(isset($_POST["botton_submit"])){
    if($_POST["name"]!=""&&$_POST["comment"]!=""){
     if($_POST["number"]>0){//(1)編集するとき
      $id=$_POST["number"];
      $sql=$pdo->prepare("update tbtest set name=:name, comment=:comment, c_time=:c_time, pass=:pass where id=:id");
      $sql->bindParam(":name", $_POST["name"], PDO::PARAM_STR);
      $sql->bindParam(":comment", $_POST["comment"], PDO::PARAM_STR);
      $sql->bindParam(":id", $id, PDO::PARAM_INT);
      $sql->bindParam(":c_time", $c_time, PDO::PARAM_STR);
      $sql->bindParam(":pass", $_POST["pass"], PDO::PARAM_STR);
      $sql->execute();
     }
     else{//(2)投稿するとき
      $sql=$pdo->prepare("INSERT INTO tbtest (name,comment,c_time,pass) VALUES (:name,:comment,:c_time,:pass)");
      $sql->bindParam(":name", $_POST["name"], PDO::PARAM_STR);
      $sql->bindParam(":comment", $_POST["comment"], PDO::PARAM_STR);
      $sql->bindParam(":c_time", $c_time, PDO::PARAM_STR);
      $sql->bindParam(":pass", $_POST["pass"], PDO::PARAM_STR);
      $sql->execute();
     }
    }
   }

  //投稿内容をデータベースから削除
  if(isset($_POST["botton_delete"])){
   if($_POST["delete_number"]!=""){
    $id=$_POST["delete_number"];
    $sql="SELECT*FROM tbtest where id=$id";
    $stmt=$pdo->query($sql);
    foreach($stmt as $row){
      if($row["pass"]==$_POST["delete_pass"]){
       $sql=$pdo->prepare("delete from tbtest where id=:id");
       $sql->bindParam(":id", $id, PDO::PARAM_INT);
       $sql->execute();
      }
    }
   }
  }

  //指定した番号を投稿欄に表示
  $edit_name="";
  $edit_comment="";
  $edit_number="";
  $pass="";
  if(isset($_POST["botton_edit"])){
    if($_POST["edit_number"]>0){
      $edit_number=$_POST["edit_number"];
      $sql="SELECT*FROM tbtest where id=$edit_number";
      $stmt=$pdo->query($sql);
      foreach($stmt as $row){
        if($row["pass"]==$_POST["edit_pass"]){
         $edit_name=$row["name"];
         $edit_comment=$row["comment"];
         $pass=$row["pass"];
        }
      }
    }
  }

  //投稿内容を表示
  $sql="SELECT*FROM tbtest";
  $stmt=$pdo->query($sql);
  $results=$stmt->fetchAll();
  foreach($results as $row){
    echo $row["id"].",";
    echo $row["name"].",";
    echo $row["c_time"]."<br>";
    echo $row["comment"]."<br>";
  }
  ?>
  <hr>
 <!-投稿フォーム->
 <form method="post" action="mission_5.php">
  <p>【投稿フォーム】</p>
  <input type="text" name="name" value="<?php echo htmlspecialchars($edit_name) ?>" placeholder="名前"><br>
  <input type="text" name="pass" value="<?php echo htmlspecialchars($pass) ?>" placeholder="パスワード(省略可)"><br>
  <input   type="hidden" name="number" value="<?php echo htmlspecialchars($edit_number) ?>">
  <textarea name="comment" rows="5" cols="50" value="<?php echo htmlspecialchars($edit_comment) ?>" placeholder="コメント"></textarea><br>
  <input type="submit" value="送信" name="botton_submit">
 </form>

 <!-削除フォーム->
 <form method="post" action="mission_5.php">
  <p>【削除番号指定用フォーム】</p>
  <input type="text" name="delete_number" placeholder="削除対象番号"><br>
  <input type="text" name="delete_pass" placeholder="パスワード"><br>
  <input type="submit" value="削除" name="botton_delete">
 </form>

 <!-編集フォーム->
 <form method="post" action="mission_5.php">
  <p>【編集番号指定用フォーム】</p>
  <input type="text" name="edit_number" value="<?php echo htmlspecialchars($edit_number) ?>" placeholder="編集対象番号"><br>
  <input type="text" name="edit_pass" placeholder="パスワード"><br>
  <input type="submit" value="表示" name="botton_edit">
 </form>
</body>
</html>
