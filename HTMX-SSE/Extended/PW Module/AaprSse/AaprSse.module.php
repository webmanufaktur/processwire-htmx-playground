<?php

namespace ProcessWire;

class AaprSse extends WireData implements Module
{

  public static function getModuleInfo()
  {
    return [
      'title' => 'AAPR SSE Module',
      'version' => '1.0.0',
      'summary' => 'Implements Server-Sent Events (SSE) functionality for ProcessWire',
      'autoload' => true,
      'singular' => true,
      'requires' => ['ProcessWire>=3.0.0', 'PHP>=7.0.0'],
    ];
  }

  public function init()
  {
    $this->addHook('/sse/', $this, 'handleSSEListener');
    $this->addHook('/sse/send/', $this, 'handleSSESend');
  }

  public function handleSSEListener(HookEvent $event)
  {
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('Connection: keep-alive');
    header("X-Accel-Buffering: no");

    if (ob_get_level()) ob_end_clean();
    ob_start();

    // Send initial connection message
    echo "event: connect\n";
    echo "data: Connected to SSE stream\n\n";
    ob_flush();
    flush();

    // Send padding for IE
    echo ':' . str_repeat(' ', 2048) . "\n\n";
    ob_flush();
    flush();

    while (true) {
      $sse_event = $this->session->get('sse_event');
      $sse_data = $this->session->get('sse_data');

      if ($sse_event && $sse_data) {
        echo "event: $sse_event\n";
        echo "data: $sse_data\n\n";
        ob_flush();
        flush();

        $this->session->remove('sse_event');
        $this->session->remove('sse_data');
      } else {
        // Send a keep-alive message every 15 seconds
        echo ": keepalive\n\n";
        ob_flush();
        flush();
      }

      if (connection_aborted()) break;
      sleep(15);
    }

    ob_end_flush();
    exit;
  }

  public function handleSSESend(HookEvent $event)
  {
    $input = $event->wire('input');
    $sse_event = $input->post('event');
    $sse_data = $input->post('data');

    if ($sse_event && $sse_data) {
      $this->session->set('sse_event', $sse_event);
      $this->session->set('sse_data', $sse_data);
      $event->return = "SSE sent successfully";
    } else {
      $event->return = "Error: Both event and data are required";
    }
  }

  public function sendSSE($event, $data)
  {
    $url = $this->pages->get(1)->httpUrl . 'sse/send/';
    $options = [
      'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query(['event' => $event, 'data' => $data])
      ]
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
  }
}
