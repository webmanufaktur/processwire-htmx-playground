<?php
// header('Content-Type: text/event-stream');
// header('Cache-Control: no-cache');
// header('Connection: keep-alive');
// header("X-Accel-Buffering: no");

// Initialize the SSE stream
function initSSE()
{
  header('Content-Type: text/event-stream');
  header('Cache-Control: no-cache');
  header('Connection: keep-alive');
  header("X-Accel-Buffering: no");

  // Disable output buffering at the PHP level
  if (ob_get_level()) ob_end_clean();

  // Start output buffering
  ob_start();

  // Send padding for IE
  echo ':' . str_repeat(' ', 2048) . "\n\n";
  ob_flush();
  flush();
}



function sendSSE($event, $data)
{
  // ob_* sit in while loop
  echo "event: $event\n";
  echo "data: $data\n\n";
}

function sendSSE2($event, $data)
{
  ob_start();
  ob_get_clean();
  echo "event: $event\n";
  echo "data: $data\n\n";
  ob_flush();
  flush();
}

function sendSSE3($event, $data)
{
  ob_start();
  ob_get_clean();
  echo "event: $event\n";
  echo "data: $data\n\n";
  ob_end_flush();
  flush();
}

function sendSSE4($event, $data)
{
  echo "event: $event\n";
  echo "data: $data\n\n";
  ob_flush();
  flush();
}



include 'StockUpdater.php';
$stockUpdater = new StockUpdater();

// Main SSE loop
function runSSEStream($stockUpdater)
{
  initSSE();

  while (true) {
    $stocks = $stockUpdater->getLatestStocks();
    $data = json_encode($stocks);
    // $data = json_encode($stocks);

    sendSSE2('stocks', $data);
    sendSSE2('bar', $data);
    sendSSE3('baz', $data);
    sendSSE4('foo', $data);

    // Check connection status
    if (connection_aborted()) break;

    // Update every 5 seconds
    sleep(1);
  }

  // Clean up
  ob_end_flush();
}

// Usage
runSSEStream($stockUpdater);

// while (true) {

//   $stocks = $stockUpdater->getLatestStocks();
//   $data = json_encode($stocks);

//   sendSSE2('stocks', $data);
//   sendSSE2('bar', $data);
//   sendSSE3('baz', $data);
//   sendSSE4('foo', $data);

//   // Check connection status
//   if (connection_aborted()) break;

//   // Update every 5 seconds
//   sleep(1);
// }
