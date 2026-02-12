10) Dependency Injection (DI) + Container (praktyka, nie „klasyczny GoF”, ale w PHP mega ważne)

Po co: łatwiejsze testowanie i wymiana zależności bez „new” porozrzucanego wszędzie.

<?php

final class UserService {
  public function __construct(private UserRepository $repo) {}
  public function getEmail(int $id): ?string { return $this->repo->findById($id)?->email; }
}

// “Manual DI”
$pdo = new PDO('sqlite::memory:');
$repo = new PdoUserRepository($pdo);
$svc  = new UserService($repo);
