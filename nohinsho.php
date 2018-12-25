<?php
if( !empty($_GET['code']) ){
  require_once('connect.php');

  //1. denpyoとtokuiに対してSQL文を発行
      //伝票の上半分
  $sql="SELECT `denpyo_id`, denpyo.`tokui_id`,tokui_name,tokui_addr, `hiduke` 
        FROM `denpyo` 
          LEFT JOIN tokui 
          ON denpyo.tokui_id = tokui.tokui_id 
        WHERE denpyo_id = ? ";
  $stmt = $dbh->prepare($sql);   //プリペア
  $stmt->bindValue(1 ,$_GET['code'],PDO::PARAM_INT);
  $stmt->execute();              //実行

  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  echo "<p>" , $row["denpyo_id"];
  echo "<p>" , $row["tokui_id"];
  echo "<p>" , $row["tokui_name"];
  echo "<p>" , $row["tokui_addr"];
  echo "<p>" , $row["hiduke"];

  //伝票の下半分(詳細)
  $sql="SELECT shosai.shohin_id as 商品コード,shohin_name as 商品名, num as 数量 
        FROM `shosai`
          LEFT JOIN shohin
          ON shosai.shohin_id = shohin.shohin_id 
        WHERE shosai.denpyo_id = ? ";
  
  $stmt = $dbh->prepare($sql);   //プリペア
  $stmt->bindValue(1 ,$_GET['code'],PDO::PARAM_INT);
  $stmt->execute();              //実行

  //$row = $stmt->fetch(PDO::FETCH_ASSOC);
  echo "<table border='1'>
        <th>商品コード</th><th>商品名</th><th>数量</th>";

    foreach ( $stmt as $key => $v) {
     echo "<tr><td>{$v['商品コード']}</td>
               <td>{$v['商品名']}</td>
               <td>{$v['数量']}</td></tr>";
    }
  echo "</table>";
  exit;  //処理の中断
}  //GETがあれば if end
  //2. 1の戻り値を表示する
  //3. shosaiとshohinに対してSQL文を発行
  //4. 2の戻り値をループしてテーブル状に表示する
  //5. 色と幅をつける
?>
 
<form>
 <label>伝票番号</label>
 <input type="number" name="code">
 <input type="submit" value="検索">
</form>