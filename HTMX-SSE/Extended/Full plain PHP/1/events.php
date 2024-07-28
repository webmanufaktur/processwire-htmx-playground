<?php

// Set timezone
date_default_timezone_set('Europe/Berlin');

// Create an array of events
$events = [
  ['title' => 'Past Event 1', 'date' => '2024-07-23', 'description' => 'This event is in the past'],
  ['title' => 'Past Event 2', 'date' => '2024-07-24', 'description' => 'This event is also in the past'],
  ['title' => 'Today Event 1', 'date' => '2024-07-25', 'description' => 'This event is today'],
  ['title' => 'Tomorrow Event 1', 'date' => '2024-07-26', 'description' => 'This event is tomorrow'],
  ['title' => 'Tomorrow Event 2', 'date' => '2024-07-26', 'description' => 'This is also tomorrow'],
  ['title' => 'Future Event 1', 'date' => '2024-07-27', 'description' => 'This event is in the future'],
  ['title' => 'Future Event 2', 'date' => '2024-07-28', 'description' => 'This is also in the future'],
  ['title' => 'Future Event 3', 'date' => '2024-07-29', 'description' => 'Another future event'],
  ['title' => 'Future Event 4', 'date' => '2024-07-30', 'description' => 'Yet another future event'],
  ['title' => 'Future Event 5', 'date' => '2024-07-31', 'description' => 'The last event in our list']
];

// Get current date and time
$now = new DateTime();
$today = $now->format('Y-m-d');
$tomorrow = (new DateTime('tomorrow'))->format('Y-m-d');

// Determine if it's before or after 17:00
$isBeforeCutoff = $now->format('H') < 17;

// Filter events
$pastEvents = [];
$todayEvents = [];
$tomorrowEvents = [];

foreach ($events as $event) {
  $eventDate = $event['date'];
  if ($eventDate < $today) {
    $pastEvents[] = $event;
  } elseif ($eventDate == $today && $isBeforeCutoff) {
    $todayEvents[] = $event;
  } elseif ($eventDate == $tomorrow) {
    $tomorrowEvents[] = $event;
  }
}

// Function to display events
function displayEvents($title, $events)
{
  echo "$title:\n";
  if (empty($events)) {
    echo "No events.\n";
  } else {
    foreach ($events as $event) {
      echo "- {$event['title']} ({$event['date']}): {$event['description']}\n";
    }
  }
  echo "\n";
}

// Display events
displayEvents("Past Events", $pastEvents);
displayEvents($isBeforeCutoff ? "Today's Events" : "Today's Events (None after 17:00)", $todayEvents);
displayEvents("Tomorrow's Events", $tomorrowEvents);
