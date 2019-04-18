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
        $this->data = Type::set($data);
    }

    public function getArray(string $key): array
    {
        return (array) $this->getValue($key);
    }

    public function getBase64(string $key): string
    {
        return Clean::base64(Type::set($this->getValue($key), 'str', false));
    }

    public function getBool(string $key): bool
    {
        return Clean::bool($this->getValue($key));
    }

    public function getCmd(string $key): string
    {
        return Clean::cmd(Type::set($this->getValue($key), 'str', false));
    }

    public function getCmds(string $key): array
    {
        return Clean::cmds(Type::set($this->getValue($key), 'arr', false));
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getEmail(string $key): string
    {
        return Clean::email(Type::set($this->getValue($key), 'str', false));
    }

    public function getFloat(string $key): float
    {
        return Clean::float(Type::set($this->getValue($key), 'str', false));
    }

    public function getHtml(string $key): string
    {
        return Clean::html(Type::set($this->getValue($key), 'str', false));
    }

    public function getInt(string $key): int
    {
        return Clean::int(Type::set($this->getValue($key), 'str', false));
    }

    public function getPath(string $key): string
    {
        return Clean::path(Type::set($this->getValue($key), 'str', false));
    }

    public function getUfloat(string $key): float
    {
        return Clean::ufloat(Type::set($this->getValue($key), 'str', false));
    }

    public function getUint(string $key): int
    {
        return Clean::uint(Type::set($this->getValue($key), 'str', false));
    }

    public function getUrl(string $key): string
    {
        return Clean::url(Type::set($this->getValue($key), 'str', false));
    }

    public function getValue(string $key, string $filter = null)
    {
        $res = $this->getData();
        foreach (explode('[', str_replace(']', '', $key)) as $k) {
            if (!isset($res[$k])) {
                return;
            }
            $res = $res[$k];
        }
        return $res;
    }
}
