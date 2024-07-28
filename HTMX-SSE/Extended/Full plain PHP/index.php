<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Stock Tracker</title>
  <script src="/htmx.min.js"></script>
  <script src="sse.js"></script>
</head>

<body>
  <h1>Real-time Stock Tracker</h1>
  <div hx-ext="sse" sse-connect="/sse.php">
    <div>
      <div sse-swap="foo"></div>
    </div>
  </div>
</body>

</html>