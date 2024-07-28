<?php
date_default_timezone_set("Europe/Berlin");
header("X-Accel-Buffering: no");
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");

$counter = rand(1, 10); // a random counter

function sendSSE($event, $data)
{
  echo "event: $event\n";
  echo "data: $data\n\n";
}

while (true) {
  // 1 is always true, so repeat the while loop forever (aka event-loop)

  $curDate = time();
  // echo "event: ping\n",
  // 'data: {"time": "' . $curDate . '"}', "\n\n";

  // echo "event: foo\n",
  // 'data: {"footimes": "' . $curDate . '"}', "\n\n";

  sendSSE('ping', 'ping: ' . $curDate);
  sendSSE('foo', 'foo: ' . $curDate);
  sendSSE('bar', 'bar: ' . $curDate);
  sendSSE('baz', 'baz: ' . $curDate);

  // echo "event: bar\n";
  // echo 'data: {"bar": "' . $curDate . '"}', "\n\n";

  // echo "event: baz\n";
  // echo 'data: {"baz": "' . $curDate . '"}', "\n\n";

  // Send a simple message at random intervals.
  // $counter--;
  // if (!$counter) {
  //   echo 'data: This is a message at time ' . $curDate, "\n\n";
  //   $counter = rand(1, 10); // reset random counter
  // }
  // flush the output buffer and send echoed messages to the browser

  while (ob_get_level() > 0) {
    ob_end_flush();
  }
  flush();

  // break the loop if the client aborted the connection (closed the page)

  if (connection_aborted()) break;

  // sleep for 1 second before running the loop again

  sleep(1);
}
