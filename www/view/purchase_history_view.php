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
  <div class="container">
  <table class="table table-striped table-borderless">
    <tr>
      <th>注文番号</th>
      <th>購入日時</th>
      <th>合計金額</th>
      <th></th>
    </tr>
  <?php foreach($historys as $history) { ?>
    <tr>
      <td><?php print $history['order_id']; ?></td>
      <td><?php print $history['purchase_time']; ?></td>
      <td><?php print $history['total'];?>円</td>
      <td>
        <form action="purchase_details.php" method="post">
          <input type="submit" value="購入明細">
          <input type="hidden" name="order_id" value="<?php print($history['order_id']);?>">
          <input type="hidden" name="purchase_time" value="<?php print($history['purchase_time']);?>">
          <input type="hidden" name="total" value="<?php print($history['total']);?>">
          <!-- token -->
          <?php put_csrf_token(); ?>
        </form>
      </td>
    </tr>
  <?php } ?>
    
  </table>
  </div>
</body>
</html>