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

  $curDate = time();

  // send SSE messages
  // sendSSE('ping', 'ping: ' . $curDate);
  // sendSSE('foo', 'foo: ' . $curDate);
  // sendSSE('bar', 'bar: ' . $curDate);
  // sendSSE('baz', 'baz: ' . $curDate);

  $rando = date('d.M.Y H:i:s', time()) . " + " . rand(1, 999999999) * rand(1, 999999999);
  sendSSE('ping', $rando);

  $rando = date('d.M.Y H:i:s',  time()) . " + " . rand(1, 999999999) * rand(1, 999999999);
  sendSSE('foo', $rando);

  $rando = date('d.M.Y H:i:s', time()) . " + " . rand(1, 999999999) * rand(1, 999999999);
  sendSSE('bar', $rando);

  $rando = date('d.M.Y H:i:s', time()) . " + " . rand(1, 999999999) * rand(1, 999999999);
  sendSSE('baz', $rando);

  $rando = date('d.M.Y H:i:s', time()) . " + " . rand(1, 999999999) * rand(1, 999999999);
  sendSSE('ying', $rando);

  $rando = date('d.M.Y H:i:s', time()) . " + " . rand(1, 999999999) * rand(1, 999999999);
  sendSSE('yang', $rando);

  $rando = date('d.M.Y H:i:s', time()) . " + " . rand(1, 999999999) * rand(1, 999999999);
  sendSSE('peng', $rando);

  // flush the output buffer and send echoed messages to the browser
  while (ob_get_level() > 0) {
    ob_end_flush();
  }
  flush();

  // break the loop if the client aborted the connection (closed the page)
  if (connection_aborted()) break;

  // sleep for 1 second before running the loop again
  sleep(10);
  // usleep(250000);
}
