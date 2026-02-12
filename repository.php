9) Repository

Po co: oddziela logikę domeny od dostępu do danych (DB/API). Super popularne w Symfony/Laravel.

<?php

final class User {
  public function __construct(public int $id, public string $email) {}
}

interface UserRepository {
  public function findById(int $id): ?User;
}

final class PdoUserRepository implements UserRepository {
  public function __construct(private PDO $pdo) {}
  public function findById(int $id): ?User {
    $stmt = $this->pdo->prepare("SELECT id, email FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? new User((int)$row['id'], $row['email']) : null;
  }
}
