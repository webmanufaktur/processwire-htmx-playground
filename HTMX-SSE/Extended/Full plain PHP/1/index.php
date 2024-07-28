<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HTMX SSE PHP Demo</title>
  <script src="./htmx.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }

    #updates {
      border: 1px solid #ccc;
      padding: 10px;
      min-height: 100px;
    }
  </style>
</head>

<body>
  <h1>HTMX SSE PHP Demo</h1>
  <div id="updates" hx-sse="connect:/sse.php">
    <div hx-sse="swap:asdasd">
      Waiting for updates...
    </div>
  </div>
</body>

</html>