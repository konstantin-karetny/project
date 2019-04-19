<?php

namespace Ada\Core;

class DataSet extends Proto
{
    protected
        $data = [];

    public static function init(array $data = [])
    {
        return new static($data);
    }

    public function __construct(array $data = [])
    {
        $this->sets($data);
    }

    public function get(string $key)
    {
        return Arr::value($this->getData(), $key);
    }

    public function getArray(string $key): array
    {
        return Value::array($this->get($key));
    }

    public function getBase64(string $key): string
    {
        return Value::base64($this->get($key));
    }

    public function getBase64s(string $key): array
    {
        return Value::base64s($this->get($key));
    }

    public function getBool(string $key): bool
    {
        return Value::bool($this->get($key));
    }

    public function getBools(string $key): array
    {
        return Value::bools($this->get($key));
    }

    public function getCmd(string $key): string
    {
        return Value::cmd($this->get($key));
    }

    public function getCmds(string $key): array
    {
        return Value::cmds($this->get($key));
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getEmail(string $key): string
    {
        return Value::email($this->get($key));
    }

    public function getEmails(string $key): array
    {
        return Value::emails($this->get($key));
    }

    public function getFloat(string $key): float
    {
        return Value::float($this->get($key));
    }

    public function getFloats(string $key): array
    {
        return Value::floats($this->get($key));
    }

    public function getHtml(string $key): string
    {
        return Value::html($this->get($key));
    }

    public function getHtmls(string $key): array
    {
        return Value::htmls($this->get($key));
    }

    public function getInt(string $key): int
    {
        return Value::int($this->get($key));
    }

    public function getInts(string $key): array
    {
        return Value::ints($this->get($key));
    }

    public function getPath(string $key): string
    {
        return Value::path($this->get($key));
    }

    public function getPaths(string $key): array
    {
        return Value::paths($this->get($key));
    }

    public function getString(string $key): string
    {
        return Value::string($this->get($key));
    }

    public function getStrings(string $key): array
    {
        return Value::strings($this->get($key));
    }

    public function getUfloat(string $key): float
    {
        return Value::ufloat($this->get($key));
    }

    public function getUfloats(string $key): array
    {
        return Value::ufloats($this->get($key));
    }

    public function getUint(string $key): int
    {
        return Value::uint($this->get($key));
    }

    public function getUints(string $key): array
    {
        return Value::uints($this->get($key));
    }

    public function getUrl(string $key): string
    {
        return Value::url($this->get($key));
    }

    public function getUrls(string $key): array
    {
        return Value::urls($this->get($key));
    }

    public function isset(string $key): bool
    {
        return Arr::isset($this->getData(), $key);
    }

    public function set(string $key, $value): void
    {
        $this->data[$key] = Value::typify($value);
    }

    public function sets(array $values): void
    {
        $this->data = array_merge(
            $this->getData(),
            Value::typify($values)
        );
    }
}
