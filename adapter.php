5) Adapter

Po co: dopasowuje „obcą” bibliotekę/klasę do interfejsu aplikacji.

<?php

// Nasz standard
interface PaymentGateway { public function charge(int $amountCents): string; }

// Obca biblioteka
final class AcmePaySdk {
  public function makePayment(float $amount): string { return "tx_".uniqid(); }
}

// Adapter
final class AcmePayAdapter implements PaymentGateway {
  public function __construct(private AcmePaySdk $sdk) {}
  public function charge(int $amountCents): string {
    return $this->sdk->makePayment($amountCents / 100.0);
  }
}

$gw = new AcmePayAdapter(new AcmePaySdk());
echo $gw->charge(1999);
