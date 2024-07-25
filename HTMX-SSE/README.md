# HTMX SSE Demo

This project demonstrates a simple implementation of Server-Sent Events (SSE) using HTMX. It showcases real-time updates from the server to the client without the need for polling or WebSockets.

## Features

- Real-time updates using Server-Sent Events
- Simple PHP backend
- HTMX-powered frontend for easy integration
- Demonstration of progress bar updates

## Project Structure

The project consists of two main files:

1. `index.php`: The main HTML file that includes HTMX and sets up the SSE connection.
2. `sse.php`: The server-side PHP script that generates and sends SSE events.

## How It Works

The `index.php` file sets up a basic HTML structure with a progress bar. It uses HTMX to establish an SSE connection to the server:

```html
<div hx-ext="sse" sse-connect="/sse.php">
  <div hx-sse="message" hx-swap="innerHTML">
    <progress id="progress" value="0" max="100"></progress>
  </div>
</div>
```

The `sse.php` file simulates a long-running process by sending progress updates every second:

```php
<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

for ($i = 0; $i <= 100; $i += 10) {
    echo "data: <progress id=\"progress\" value=\"$i\" max=\"100\"></progress>\n\n";
    flush();
    sleep(1);
}
```

## Setup and Running

1. Ensure you have a PHP server installed on your machine.
2. Clone this repository to your local machine.
3. Place the files in your web server's document root.
4. Access the `index.php` file through your web browser.

## Requirements

- PHP 7.0 or higher
- A modern web browser that supports Server-Sent Events

## Notes

This demo is intended for educational purposes and demonstrates a basic implementation of SSE with HTMX. In a production environment, you would want to add error handling, security measures, and potentially use a more robust server setup.

## Contributing

Feel free to fork this project, submit issues, or send pull requests to improve the demo.

## License

This project is open-source and available under the MIT License.
