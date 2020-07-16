<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>mission_5-1</title>
</head>
<body>
<?php
if(!empty($_POST["name"])){$name=$_POST["name"];}
else{$name="";}
if(!empty($_POST["str"])){$str=$_POST["str"];}
else{$str="";}
if(!empty($_POST["delete_number"])){$delete_number=$_POST["delete_number"];}
else{$delete_number="";}
if(!empty($_POST["hensyuu"])){$hensyuu=$_POST["hensyuu"];}
else{$hensyuu="";}
if(!empty($_POST["hensyuu_num"])){$hensyuu_num=$_POST["hensyuu_num"];}
else{$hensyuu_num="";}
if(!empty($_POST["password_toukou"])){$password_toukou=$_POST["password_toukou"];}
else{$password_toukou="";}
if(!empty($_POST["password_delete"])){$password_delete=$_POST["password_delete"];}
else{$password_delete="";}
if(!empty($_POST["password_edit"])){$password_edit=$_POST["password_edit"];}
else{$password_edit="";}
//DB接続設定
$dsn='mysql:dbname=データベース名;host=localhost';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn, $user, $password, 
array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
$sql='CREATE TABLE IF NOT EXISTS tb'
."("
. "id INT AUTO_INCREMENT PRIMARY KEY,"//自動連番の整数を指定
. "name char(32),"
. "str TEXT,"
. "date DATETIME,"
. "password_toukou TEXT"
.");";
$stmt=$pdo->query($sql);
$sql=$pdo->prepare("INSERT INTO tb (name,str,date,password_toukou) 
                   VALUES(:name,:str,:date,:password_toukou)");

//投稿機能
if(!empty($name)&&!empty($str)&&!empty($password_toukou)&&empty($hensyuu_num)){
   $sql->bindParam(':name',$name,PDO::PARAM_STR);//:nameなどのパラメータに値を入れる
   $sql->bindParam(':str',$str,PDO::PARAM_STR);
   $sql->bindParam(':date',date('Y/m/d h:i:s'),PDO::PARAM_STR);
   $sql->bindParam(':password_toukou',$password_toukou,PDO::PARAM_STR);
   $sql->execute();}//命令を実行する(prepareのときに必要)

//$row['password_toukou']を作ってパスワード機能を実装する
$sql='SELECT * FROM tb';//tbテーブルにある全てのデータを取得するSQL文を、変数に格納
$stmt=$pdo->query($sql);//SQL文を実行するコードを、変数に格納
$result=$stmt->fetchAll();//該当する全てのデータを配列として返す
foreach($result as $row);//foreach文でデータベースより取得したデータを1行ずつループ処理

//削除機能
if(!empty($delete_number)&&!empty($password_delete)){
   $id=$delete_number;
   $sql='delete from tb where id=:id';
   $stmt=$pdo->prepare($sql);
   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
   if($password_delete==$row['password_toukou']){
      $stmt->execute();}
}   

//編集機能
if(!empty($hensyuu)&&!empty($password_edit)){
    $id=$hensyuu;
    $sql='SELECT * FROM tb WHERE id=:id';//tbテーブルにある全てのデータを取得するSQL文を、変数に格納
    $stmt=$pdo->prepare($sql);//SQL文を実行するコードを、変数に格納
    $stmt->bindParam(':id',$id,PDO::PARAM_INT);
    if($password_edit==$row['password_toukou']){
    $stmt->execute();
    $result=$stmt->fetchAll();//該当する全てのデータを配列として返す
    foreach($result as $row){//foreach文でデータベースより取得したデータを1行ずつループ処理
       $hensyuu_num=$row['id'];
       $hensyuu_name=$row['name'];
       $hensyuu_str=$row['str'];}
	}
}	
if(!empty($hensyuu_num)&&!empty($name)&&!empty($str)){
    $id=$hensyuu_num; //変更する投稿番号
	$sql='UPDATE tb SET name=:name,str=:str WHERE id=:id';
	$stmt=$pdo->prepare($sql);
	$stmt->bindParam(':name',$name,PDO::PARAM_STR);
	$stmt->bindParam(':str',$str,PDO::PARAM_STR);
	$stmt->bindParam(':id',$id,PDO::PARAM_INT);
	$stmt->execute();
}
	?>

  <form action=""method="post">
       <input type="text" name="name" placeholder="名前"
       value="<?php if(isset($hensyuu)) {echo $hensyuu_name;} ?>"><br>
       <input type="text" name="str" placeholder="コメント" 
       value="<?php if(isset($hensyuu)) {echo $hensyuu_str;} ?>"><br>
       <input type="hidden" name="hensyuu_num"
       value="<?php if(isset($hensyuu)) {echo $hensyuu_num;}?>">
       <input type="text" name="password_toukou" placeholder="パスワード"><br>
       <input type="submit" name="submit">
  </form>
  <form action=""method="post">
      <input type="text" name="delete_number" placeholder="削除対象番号"><br>
      <input type="text" name="password_delete" placeholder="パスワード"><br>
      <input type="submit" name="delete" value="削除">
  </form>
  <form action=""method="post">
       <input type="text" name="hensyuu" placeholder="編集対象番号"><br>
       <input type="text" name="password_edit" placeholder="パスワード"><br>
       <input type="submit" name="edit" value="編集">
  </form>

<?php
$sql='SELECT * FROM tb';//tbテーブルにある全てのデータを取得するSQL文を、変数に格納
$stmt=$pdo->query($sql);//SQL文を実行するコードを、変数に格納
$result=$stmt->fetchAll();//該当する全てのデータを配列として返す
foreach($result as $row){//foreach文でデータベースより取得したデータを1行ずつループ処理
echo $row['id'].",";
echo $row['name'].",";
echo $row['str'].",";
echo $row['date']."<br>";
echo "<hr>";}
?>

</body>
</html>