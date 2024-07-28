<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HTMX SSE Demo</title>
  <script src="htmx.min.js"></script>
  <script src="sse.js"></script>
  <style>
    .numbers {
      font-family: monospace;
      /* font-size: 3rem; */
    }
  </style>
</head>

<body>
  <h1>HTMX SSE Demo</h1>
  <div hx-ext="sse" sse-connect="/sse.php">
    <div class="numbers" sse-swap="ping">Waiting for updates in ping...</div>
    <div class="numbers" sse-swap="foo">Waiting for updates in foo...</div>
    <div class="numbers" sse-swap="bar">Waiting for updates in bar...</div>
    <div class="numbers" sse-swap="baz">Waiting for updates in baz...</div>
    <div class="numbers" sse-swap="ying">Waiting for updates in baz...</div>
    <div class="numbers" sse-swap="yang">Waiting for updates in baz...</div>
    <div class="numbers" sse-swap="peng">Waiting for updates in baz...</div>
  </div>
</body>

</html>