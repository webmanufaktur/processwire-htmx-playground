<?php

declare(strict_types=1);

namespace ProcessWire;

class FlipFlop extends WireData implements Module
{
    private const COUNTER_KEY = 'counter';

    public function init(): void
    {
        $this->addHook('/api/(action:count)/', $this, 'handleCount');
        $this->addHook('/api/(action:up)/', $this, 'handleUp');
        $this->addHook('/api/(action:down)/', $this, 'handleDown');
    }

    public function ready(): void
    {
        // Implementation if needed
    }

    protected function writelog(string $message): void
    {
        $this->wire->log->save('testing', $message);
    }

    protected function generateAlert(int $count): string
    {
        return "<div id=\"alert\" hx-swap-oob=\"true\">Counter: {$count}</div>";
    }

    protected function count(): int
    {
        if ($this->session->get(self::COUNTER_KEY) === null) {
            $this->session->set(self::COUNTER_KEY, 0);
        }
        return (int) $this->session->get(self::COUNTER_KEY);
    }

    protected function updateCounter(int $increment): string
    {
        $count = $this->count() + $increment;
        $this->session->set(self::COUNTER_KEY, $count);

        return $this->generateCounterHtml($count, $increment > 0 ? 'up' : 'down') . $this->generateAlert($count);
    }

    protected function generateCounterHtml(int $count, string $triggeredAction): string
    {
        $flippedHtml = $this->generateElementHtml('flipped', $count, 'up', $triggeredAction !== 'up');
        $floppedHtml = $this->generateElementHtml('flopped', $count, 'down', $triggeredAction !== 'down');

        return $flippedHtml . $floppedHtml;
    }

    protected function generateElementHtml(string $id, int $count, string $action, bool $isOob): string
    {
        $oobAttribute = $isOob ? ' hx-swap-oob="true"' : '';
        return <<<EOT
            <div id="{$id}" class="border-2 p-4 rounded-md" hx-trigger="click" hx-swap="outerHTML" hx-get="/api/{$action}/" hx-target="#{$id}"{$oobAttribute}>{$count}</div>
        EOT;
    }

    public function handleCount(HookEvent $event): ?string
    {
        if ($event->action !== 'count') return null;

        $count = $this->count();
        return $this->generateCounterHtml($count, '') . $this->generateAlert($count);
    }

    public function handleUp(HookEvent $event): ?string
    {
        if ($event->action !== 'up') return null;

        return $this->updateCounter(1);
    }

    public function handleDown(HookEvent $event): ?string
    {
        if ($event->action !== 'down') return null;

        return $this->updateCounter(-1);
    }
}
