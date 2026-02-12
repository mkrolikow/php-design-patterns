6) Decorator

Po co: dodaje funkcje „owijając” obiekt (logowanie, cache, metryki).

<?php

interface Notifier { public function send(string $to, string $msg): void; }

final class EmailNotifier implements Notifier {
  public function send(string $to, string $msg): void {
    // mail($to, "Msg", $msg);
    echo "EMAIL to $to: $msg\n";
  }
}

final class LoggingNotifier implements Notifier {
  public function __construct(private Notifier $inner) {}
  public function send(string $to, string $msg): void {
    error_log("Sending to $to");
    $this->inner->send($to, $msg);
  }
}

$notifier = new LoggingNotifier(new EmailNotifier());
$notifier->send("a@b.com", "Hi!");
