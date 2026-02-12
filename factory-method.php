1) Factory Method / Simple Factory

Po co: ukrywa tworzenie obiektów; wybór klasy zależy od parametru/konfiguracji.

<?php

interface Logger { public function log(string $msg): void; }

final class FileLogger implements Logger {
  public function __construct(private string $path) {}
  public function log(string $msg): void { file_put_contents($this->path, $msg.PHP_EOL, FILE_APPEND); }
}

final class NullLogger implements Logger {
  public function log(string $msg): void {}
}

final class LoggerFactory {
  public static function make(array $cfg): Logger {
    return ($cfg['enabled'] ?? false)
      ? new FileLogger($cfg['path'] ?? '/tmp/app.log')
      : new NullLogger();
  }
}

$logger = LoggerFactory::make(['enabled' => true, 'path' => __DIR__.'/app.log']);
$logger->log("Hello");
