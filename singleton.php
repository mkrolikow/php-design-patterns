3) Singleton (ostrożnie)

Po co: jedna instancja globalnie (często nadużywany; zwykle lepszy jest DI/Container).

<?php

final class Config {
  private static ?self $instance = null;
  private function __construct(private array $data) {}

  public static function init(array $data): void { self::$instance = new self($data); }
  public static function get(): self {
    if (!self::$instance) throw new RuntimeException("Config not initialized");
    return self::$instance;
  }
  public function value(string $key, mixed $default=null): mixed { return $this->data[$key] ?? $default; }
}

Config::init(['env' => 'prod']);
echo Config::get()->value('env');
