<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH .'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'details.css'); ?>">
</head>
<body>
  <?php 
  include VIEW_PATH . 'templates/header_logined.php'; 
  ?>
  <table class="table table-striped table-borderless">
    <tr>
      <th>注文番号:<?php print $order_id;?></th>
      <th>購入日時:<?php print $purchase_time;?></th>
      <th>合計金額:<?php print $total;?>円</th>
    </tr>
  </table>
  <table class="table table-striped">
    <tr>  
      <th>商品名</th>
      <th>購入時の商品価格</th>
      <th>購入数</th>
      <th>小計</th>
    </tr>
  <?php foreach($details as $detail){ ?>
    <tr>
      <td><?php print $detail['name']; ?></td>
      <td><?php print $detail['price']; ?></td>
      <td><?php print $detail['amount']; ?></td>
      <td><?php print $detail['subtotal']; ?>円</td>
    </tr>
  <?php } ?>
  </table>

  



</body>
</html>
