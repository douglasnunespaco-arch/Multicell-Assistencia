<?php
namespace App\Core;

/**
 * Validator — validações simples encadeáveis para requests.
 */
final class Validator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function make(array $data): self
    {
        return new self($data);
    }

    public function required(string $field, string $label): self
    {
        $v = trim((string) ($this->data[$field] ?? ''));
        if ($v === '') {
            $this->errors[$field] = "O campo {$label} é obrigatório.";
        }
        return $this;
    }

    public function max(string $field, int $max, string $label): self
    {
        if (isset($this->data[$field]) && mb_strlen((string) $this->data[$field]) > $max) {
            $this->errors[$field] = "{$label} deve ter no máximo {$max} caracteres.";
        }
        return $this;
    }

    public function email(string $field, string $label): self
    {
        $v = (string) ($this->data[$field] ?? '');
        if ($v !== '' && !filter_var($v, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "{$label} inválido.";
        }
        return $this;
    }

    public function phoneBR(string $field, string $label): self
    {
        $v = preg_replace('/\D+/', '', (string) ($this->data[$field] ?? ''));
        if ($v !== '' && (strlen($v) < 10 || strlen($v) > 13)) {
            $this->errors[$field] = "{$label} inválido.";
        }
        return $this;
    }

    public function in(string $field, array $allowed, string $label): self
    {
        if (isset($this->data[$field]) && !in_array($this->data[$field], $allowed, true)) {
            $this->errors[$field] = "{$label} inválido.";
        }
        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
