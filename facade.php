8) Facade

Po co: prosty interfejs do „kombajnu” (np. wysyłka: template + mail + log + retry).

<?php

final class Mailer { public function send(string $to, string $body): void { echo "Sent to $to\n"; } }
final class Template { public function render(string $name, array $data): string { return "Hi {$data['name']}"; } }
final class Audit { public function log(string $msg): void { error_log($msg); } }

final class NotificationFacade {
  public function __construct(private Mailer $m, private Template $t, private Audit $a) {}
  public function welcome(string $email, string $name): void {
    $body = $this->t->render('welcome', ['name' => $name]);
    $this->m->send($email, $body);
    $this->a->log("welcome sent to $email");
  }
}

$facade = new NotificationFacade(new Mailer(), new Template(), new Audit());
$facade->welcome('x@y.com', 'Jan');
