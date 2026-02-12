4) Strategy

Po co: wymienialne algorytmy (np. zniżki, sortowanie, wysyłka).

<?php

interface DiscountStrategy { public function apply(int $priceCents): int; }

final class NoDiscount implements DiscountStrategy {
  public function apply(int $priceCents): int { return $priceCents; }
}
final class PercentageDiscount implements DiscountStrategy {
  public function __construct(private int $percent) {}
  public function apply(int $priceCents): int {
    return (int) round($priceCents * (100 - $this->percent) / 100);
  }
}

final class Checkout {
  public function __construct(private DiscountStrategy $discount) {}
  public function total(int $subtotalCents): int { return $this->discount->apply($subtotalCents); }
}

$checkout = new Checkout(new PercentageDiscount(10));
echo $checkout->total(10000); // 9000
