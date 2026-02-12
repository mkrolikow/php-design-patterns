Outbox pattern (czasem „Transactional Outbox”) to sposób na pewne publikowanie zdarzeń/wiadomości z aplikacji (np. do RabbitMQ/Kafka/SQS) bez ryzyka, że zapiszesz dane w bazie, a wysyłka eventu się nie uda (albo odwrotnie).

Kluczowa idea: zapis zmian w danych i zapis „wiadomości do wysłania” robisz w tej samej transakcji DB. Wysyłką zajmuje się osobny proces/worker, który czyta tabelę outbox i publikuje.

Problem, który rozwiązuje

Bez outboxa często robimy coś takiego:

INSERT/UPDATE w DB

publish event do brokera

Jeśli aplikacja padnie między (1) i (2) albo broker jest chwilowo niedostępny, masz niespójność: dane w DB są, ale event nie poszedł (albo event poszedł, a zapis nie).

Outbox pattern zapewnia: „co najmniej raz” dostarczenie zdarzeń przy zachowaniu spójności z DB.

Jak to wygląda w PHP (architektura)
1) Tabela outbox_messages

Przykładowe pola:

id (UUID lub BIGINT)

aggregate_type (np. Order)

aggregate_id (np. 123)

event_type (np. OrderCreated)

payload (JSON)

headers (JSON, opcjonalnie)

status (pending/sent/failed)

attempts

available_at (do retry/backoff)

created_at, sent_at

dedup_key (opcjonalnie, do idempotencji)

Minimalna wersja to: id, event_type, payload, created_at, status.

2) Zapis domeny + outbox w jednej transakcji

W Twoim serwisie aplikacyjnym:

zapisujesz encję (np. orders)

zapisujesz rekord w outbox_messages z eventem

Wszystko w jednym BEGIN ... COMMIT.

3) Worker (CLI/cron/supervisor) publikuje wiadomości

Worker:

pobiera paczkę pending (z blokadą typu FOR UPDATE SKIP LOCKED)

publikuje do brokera

oznacza jako sent (albo planuje retry)
