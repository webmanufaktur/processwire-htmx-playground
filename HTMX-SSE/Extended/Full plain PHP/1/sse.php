<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// function sendSSE($id, $message)
// {
//   echo "id: $id" . PHP_EOL;
// echo "event: ping" . PHP_EOL;
$message = 'HTMX SSE PHP Demo 3';
$eventName = 'asdasd';
echo "event: $eventName\n\n";
echo "data: $message\n\n";
// echo "data: $message\n\n";
// echo "data: 'hallo'" . PHP_EOL;
// echo PHP_EOL;
ob_flush();
flush();
// }

// function sendSSE($message)
// {
//   echo "data: $message\n\n";
//   echo str_pad('', 8186) . "\n";
//   flush();
// }

// $i = 0;
// while (true) {
//   sendSSE("asdasd i = $i");
//   if ($i > 30) return; // manual break after 30s for testing
//   $i++;
//   while (ob_get_level() > 0) ob_end_flush();
//   if (connection_aborted()) break;
//   sleep(1);
// }

// $counter = 1;

// while (true) {
//   $currentTime = date('H:i:s');
//   $message = "Update $counter: Current time is $currentTime";
//   sendSSE($counter, $message);

//   $counter++;
//   sleep(3); // Wait for 3 seconds before sending the next update
// }

// WORKS BELOW HERE
// // Log function
// function logMessage($message)
// {
//   error_log($message, 3, 'sse_error.log');
// }

// // logMessage("SSE script started: " . date('Y-m-d H:i:s')) . "\n\n";


// while (true) {
//   $counter++;
//   $message = "Update $counter: " . date('H:i:s');
//   sendSSE($counter, $message);
//   logMessage(">> Sent message: $message\n");

//   // if ($counter >= 10) {
//   //   break;
//   // }

//   sleep(5);
// }

// logMessage("SSE script ended: " . date('Y-m-d H:i:s'));
