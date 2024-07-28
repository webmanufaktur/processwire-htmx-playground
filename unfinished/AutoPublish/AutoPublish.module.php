<?php

namespace ProcessWire;

class AutoPublish extends WireData implements Module, ConfigurableModule
{

  public static function getModuleInfo()
  {
    return array(
      'title' => 'Auto Publish Content',
      'version' => 10,
      'summary' => 'Automatically updates content status based on a pages->findMany() selector at configurable intervals with multiple configuration sets.',
      'author' => 'Your Name',
      'singular' => true,
      'autoload' => true,
    );
  }

  public function __construct()
  {
    parent::__construct();
    $this->config_sets = array();
  }

  public function init()
  {
    $this->addHook('LazyCron::everyMinute', $this, 'checkAndUpdateContent');
  }

  public function checkAndUpdateContent()
  {
    $this->log->save('auto-publish', "LazyCron triggered checkAndUpdateContent at " . date('Y-m-d H:i:s'));

    foreach ($this->config_sets as $index => $config) {
      $this->log->save('auto-publish', "Processing config set {$index}");
      $this->processConfigSet($config, $index);
    }
  }

  protected function processConfigSet($config, $index)
  {
    if (empty($config['selector']) || empty($config['cron_interval']) || !isset($config['target_status'])) {
      $this->log->save('auto-publish', "Error: Incomplete configuration set {$index}.");
      return;
    }

    $this->log->save('auto-publish', "Config set {$index} - Selector: {$config['selector']}, Interval: {$config['cron_interval']}, Target Status: {$config['target_status']}");

    if (!$this->isTimeToRun($config['cron_interval'])) {
      $this->log->save('auto-publish', "Config set {$index} - Not time to run yet.");
      return;
    }

    $selector = $config['selector'];
    $targetStatus = $config['target_status'];

    $this->log->save('auto-publish', "Config set {$index} - Finding pages with selector: {$selector}");

    try {
      // Use eval to execute the pages->findMany() method
      $items = eval("return \$this->pages->findMany({$selector});");

      $this->log->save('auto-publish', "Config set {$index} - Found " . count($items) . " pages");

      foreach ($items as $item) {
        $oldStatus = $item->status;
        $item->of(false);
        $item->status = $targetStatus;
        $item->save();
        $this->log->save('auto-publish', "Updated item: {$item->title} (ID: {$item->id}, Template: {$item->template}, Old Status: {$oldStatus}, New Status: {$targetStatus})");
      }
    } catch (\Throwable $e) {
      $this->log->save('auto-publish', "Error in config set {$index}: " . $e->getMessage());
    }
  }

  protected function isTimeToRun($interval)
  {
    $currentMinute = (int)date('i');
    $currentHour = (int)date('H');
    $currentDay = (int)date('d');

    switch ($interval) {
      case 'everyMinute':
        return true;
      case 'everyFiveMinutes':
        return $currentMinute % 5 === 0;
      case 'everyFifteenMinutes':
        return $currentMinute % 15 === 0;
      case 'everyThirtyMinutes':
        return $currentMinute % 30 === 0;
      case 'everyHour':
        return $currentMinute === 0;
      case 'everyDay':
        return $currentMinute === 0 && $currentHour === 0;
      default:
        return false;
    }
  }

  public static function getModuleConfigInputfields(array $data)
  {
    $inputfields = new InputfieldWrapper();

    // Number of configuration sets
    $f = wire('modules')->get('InputfieldInteger');
    $f->attr('name', 'num_config_sets');
    $f->label = 'Number of Configuration Sets';
    $f->min = 1;
    $f->max = 10;
    $f->value = isset($data['num_config_sets']) ? $data['num_config_sets'] : 1;
    $inputfields->add($f);

    $numSets = isset($data['num_config_sets']) ? $data['num_config_sets'] : 1;

    for ($i = 0; $i < $numSets; $i++) {
      $set = new InputfieldFieldset();
      $set->label = "Configuration Set " . ($i + 1);

      // Selector input
      $f = wire('modules')->get('InputfieldText');
      $f->attr('name', "config_sets_{$i}_selector");
      $f->label = 'Selector';
      $f->description = 'Enter a ProcessWire selector to find pages to update. Use the format: "template=basic-page, status!=1"';
      $f->value = isset($data["config_sets_{$i}_selector"]) ? $data["config_sets_{$i}_selector"] : '';
      $f->required = true;
      $set->add($f);

      // LazyCron interval selection
      $f = wire('modules')->get('InputfieldSelect');
      $f->attr('name', "config_sets_{$i}_cron_interval");
      $f->label = 'Check Interval';
      $f->description = 'How often should the module check for content to update?';
      $f->addOption('everyMinute', 'Every Minute');
      $f->addOption('everyFiveMinutes', 'Every 5 Minutes');
      $f->addOption('everyFifteenMinutes', 'Every 15 Minutes');
      $f->addOption('everyThirtyMinutes', 'Every 30 Minutes');
      $f->addOption('everyHour', 'Every Hour');
      $f->addOption('everyDay', 'Every Day');
      $f->value = isset($data["config_sets_{$i}_cron_interval"]) ? $data["config_sets_{$i}_cron_interval"] : 'everyDay';
      $f->required = true;
      $set->add($f);

      // Target status selection
      $f = wire('modules')->get('InputfieldSelect');
      $f->attr('name', "config_sets_{$i}_target_status");
      $f->label = 'Target Status';
      $f->description = 'Select the status to set when updating content.';
      $f->addOption(Page::statusUnpublished, 'Unpublished');
      $f->addOption(Page::statusOn, 'Published');
      $f->addOption(Page::statusHidden, 'Hidden');
      $f->value = isset($data["config_sets_{$i}_target_status"]) ? $data["config_sets_{$i}_target_status"] : Page::statusOn;
      $f->required = true;
      $set->add($f);

      $inputfields->add($set);
    }

    return $inputfields;
  }

  public function ___install()
  {
    // Create a default configuration set
    $this->num_config_sets = 1;
    $this->config_sets_0_selector = '"template=basic-page, status!=1"';
    $this->config_sets_0_cron_interval = 'everyDay';
    $this->config_sets_0_target_status = Page::statusOn;
  }

  public function ___upgrade($fromVersion, $toVersion)
  {
    if ($fromVersion < 10) {
      // Convert old configuration to new format
      $oldConfig = $this->config_sets;
      $this->num_config_sets = count($oldConfig);
      foreach ($oldConfig as $i => $config) {
        $selector = isset($config['selector']) ? $config['selector'] : '"template=' . implode('|', $config['templates']) . '"';
        $this->set("config_sets_{$i}_selector", $selector);
        $this->set("config_sets_{$i}_cron_interval", $config['cron_interval']);
        $this->set("config_sets_{$i}_target_status", $config['target_status']);
      }
      $this->modules->saveConfig($this, 'config_sets', null);
    }
  }

  public function setArray($data)
  {
    parent::setArray($data);
    $this->config_sets = array();
    for ($i = 0; $i < $this->num_config_sets; $i++) {
      $this->config_sets[] = array(
        'selector' => $this->get("config_sets_{$i}_selector"),
        'cron_interval' => $this->get("config_sets_{$i}_cron_interval"),
        'target_status' => $this->get("config_sets_{$i}_target_status")
      );
    }
  }
}
