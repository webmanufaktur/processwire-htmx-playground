<?php
$stocks = $data; // json_decode($data, true);
?>
<ul>
  <?php foreach ($stocks as $symbol => $price) : ?>
    <li><?php echo htmlspecialchars($symbol); ?>: $<?php echo number_format($price, 2); ?></li>
  <?php endforeach; ?>
</ul>