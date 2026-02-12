7) Observer (Eventy)

Po co: powiadamianie wielu „słuchaczy” o zdarzeniu (np. user registered).

<?php

final class EventBus {
  /** @var array<string, list<callable>> */
  private array $listeners = [];

  public function on(string $event, callable $listener): void {
    $this->listeners[$event][] = $listener;
  }

  public function emit(string $event, array $payload = []): void {
    foreach ($this->listeners[$event] ?? [] as $l) $l($payload);
  }
}

$bus = new EventBus();
$bus->on('user.registered', fn($p) => print "Send welcome email to {$p['email']}\n");
$bus->on('user.registered', fn($p) => print "Add audit log: {$p['email']}\n");

$bus->emit('user.registered', ['email' => 'x@y.com']);
