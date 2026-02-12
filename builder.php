2) Builder

Po co: budowanie złożonych obiektów krok po kroku (czytelne tworzenie, walidacja).

<?php

final class HttpRequest {
  public function __construct(
    public readonly string $method,
    public readonly string $url,
    public readonly array $headers,
    public readonly ?string $body
  ) {}
}

final class HttpRequestBuilder {
  private string $method = 'GET';
  private string $url = '';
  private array $headers = [];
  private ?string $body = null;

  public function method(string $m): self { $this->method = strtoupper($m); return $this; }
  public function url(string $u): self { $this->url = $u; return $this; }
  public function header(string $k, string $v): self { $this->headers[$k] = $v; return $this; }
  public function body(?string $b): self { $this->body = $b; return $this; }

  public function build(): HttpRequest {
    if ($this->url === '') throw new InvalidArgumentException("URL required");
    return new HttpRequest($this->method, $this->url, $this->headers, $this->body);
  }
}

$req = (new HttpRequestBuilder())
  ->method('POST')
  ->url('https://api.example.com/items')
  ->header('Content-Type', 'application/json')
  ->body(json_encode(['name' => 'A']))
  ->build();
