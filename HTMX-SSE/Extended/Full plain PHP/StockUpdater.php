<?php
class StockUpdater
{
  private $stocks = [
    'AAPL' => 150.00,
    'GOOGL' => 2800.00,
    'MSFT' => 300.00
  ];

  public function getLatestStocks()
  {
    // Simulate price changes
    foreach ($this->stocks as &$price) {
      $price += rand(-5, 5);
    }
    return $this->stocks;
  }
}
