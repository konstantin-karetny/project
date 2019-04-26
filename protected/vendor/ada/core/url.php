<?php

namespace Ada\Core;

class Url extends Proto
{
    protected static
        $config = [
            'default_parts' => [
                'scheme',
                'host',
                'path',
                'query',
                'fragment'
            ],
            'default_root' => '',
            'parts' => [
                'scheme',
                'user',
                'password',
                'host',
                'port',
                'path',
                'query',
                'fragment'
            ],
            'root_default_parts' => [
                'scheme',
                'host'
            ],
            'root_parts' => [
                'scheme',
                'user',
                'password',
                'host',
                'port'
            ],
            'schemes' => [
                'http',
                'https'
            ],
            'special_chars_codes' => [
                '!'  => '%21',
                '#'  => '%23',
                '$'  => '%24',
                '&'  => '%26',
                '\'' => '%27',
                '('  => '%28',
                ')'  => '%29',
                '*'  => '%2A',
                ','  => '%2C',
                '/'  => '%2F',
                ':'  => '%3A',
                ';'  => '%3B',
                '='  => '%3D',
                '?'  => '%3F',
                '@'  => '%40',
                '['  => '%5B',
                ']'  => '%5D'
            ],
            'unsafe_chars_codes' => [
                '\'' => '%27',
                '"'  => '%22',
                '<'  => '%3C',
                '>'  => '%3E'
            ]
        ];

    protected
        $fragment = '',
        $host     = '',
        $password = '',
        $path     = '',
        $port     = 80,
        $scheme   = '',
        $user     = '',
        $vars     = null;

    public static function clean($value): string
    {
        return
            str_replace(
                array_keys(static::config()->getArray('unsafe_chars_codes')),
                array_values(static::config()->getArray('unsafe_chars_codes')),
                static::decode(
                    (string) filter_var(
                        str_replace(
                            '+',
                            ' ',
                            static::encode(
                                strtolower(
                                    Str::trim(Value::string($value), '/')
                                )
                            )
                        ),
                        FILTER_SANITIZE_URL
                    )
                )
            );
    }

    public static function decode(string $url): string
    {
        return
            urldecode(
                str_replace(
                    array_keys(static::config()->getArray('special_chars_codes')),
                    array_values(static::config()->getArray('special_chars_codes')),
                    $url
                )
            );
    }

    public static function encode(string $url): string
    {
        return
            str_replace(
                array_values(static::config()->getArray('special_chars_codes')),
                array_keys(static::config()->getArray('special_chars_codes')),
                urlencode($url)
            );
    }

    public static function init(string $url = '')
    {
        return new static($url);
    }

    public function __construct(string $url = '')
    {
        $url = $url ? static::clean($url) : $this->current();
        if (!$this->check($url)) {
            return;
        }
        foreach ($this->parse($url) as $k => $v) {
            $this->{'set' . ucfirst($k)}($v);
        }
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getQuery(): string
    {
        return http_build_query($this->getVars()->out());
    }

    public function getRoot(array $parts = null): string
    {
        $default_parts = static::config()->getCmds('root_default_parts');
        return
            $this->out(
                array_intersect(
                    $default_parts,
                    $parts === null ? $default_parts : $parts
                )
            );
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getVars(): Url\DataSet
    {
        return $this->vars = $this->vars ?: Url\DataSet::init();
    }

    public function isInternal(): bool
    {
        return $this->getRoot() == static::init()->getRoot();
    }

    public function isSSL(): bool
    {
        return $this->getScheme() == 'https';
    }

    public function out(array $parts = null): string
    {
        $res   = '';
        $parts = $parts === null ? static::config()->getCmds('default_parts') : $parts;
        if (in_array('scheme', $parts)) {
            $res .= $this->getScheme() . '://';
        }
        if (in_array('user', $parts) && $this->getUser() != '') {
            $res .= $this->getUser() . ':';
            if (in_array('password', $parts)) {
                $res .= $this->getPassword();
            }
            $res .= '@';
        }
        if (in_array('host', $parts)) {
            $res .= $this->getHost();
        }
        if (in_array('port', $parts) && $this->getPort() > 0) {
            $res .= ':' . $this->getPort();
        }
        if (in_array('path', $parts) && $this->getPath() != '') {
            $res .= '/' . $this->getPath();
        }
        if (in_array('query', $parts) && $this->getQuery() != '') {
            $res .= '?' . $this->getQuery();
        }
        if (in_array('fragment', $parts) && $this->getFragment() != '') {
            $res .= '#' . $this->getFragment();
        }
        return $res;
    }

    public function setFragment(string $fragment)
    {
        $this->fragment = static::clean($fragment);
    }

    public function setHost(string $host)
    {
        $this->host = static::clean($host);
    }

    public function setPassword(string $password)
    {
        $this->password = static::clean($password);
    }

    public function setPath(string $path)
    {
        $this->path = static::clean($path);
    }

    public function setPort(int $port)
    {
        $this->port = Value::int($port);
    }

    public function setQuery(string $query)
    {
        $this->getVars()->sets($this->parseQuery($query));
    }

    public function setScheme(string $scheme)
    {
        $scheme = static::clean($scheme);
        if (!in_array($scheme, static::config()->getCmds('schemes'))) {
            throw new Exception('Unknown scheme \'' . $scheme . '\'', 5);
        }
        $this->scheme = $scheme;
    }

    public function setUser(string $user)
    {
        $this->user = static::clean($user);
    }

    protected function check(string $url, $options = null): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL, $options)) {
            return true;
        }
        $mb_strlen = mb_strlen($url);
        if ($mb_strlen == strlen($url)) {
            return false;
        }
        $url_ascii = '';
        for ($i = 0; $i < $mb_strlen; $i++) {
            $char       = mb_substr($url, $i, 1);
            $url_ascii .= strlen($char) != mb_strlen($char) ? 'a' : $char;
        }
        return
            (bool) filter_var(
                $url_ascii,
                FILTER_VALIDATE_URL,
                $options
            );
    }

    protected function current(bool $cached = true): string
    {
        if (!$cached || !static::cache()->get('current')) {
            $res    = static::clean($this->config()->getString('default_root'));
            $server = Server::init();
            if (!$res) {
                $res = 'http';
                if (
                    ($server->getCmd('https')                  ?: 'off')  !== 'off' ||
                    ($server->getCmd('http_x_forwarded_proto') ?: 'http') !== 'http'
                ) {
                    $res .= 's';
                }
                $res .= '://' . $server->getString('http_host');
            }
            if (
                $server->getBool('php_self') &&
                $server->getBool('request_uri')
            ) {
                $res .= '/' . $server->getString('request_uri');
            }
            else {
                $res .= $server->getString('script_name');
                if ($server->getString('query_string')) {
                    $res .= '?' . $server->getString('query_string');
                }
            }
            static::cache()->set('current', static::clean($res));
        }
        return static::cache()->get('current');
    }

    protected function parse(string $url): array
    {
        $server = Server::init();
        $res    = parse_url($url) ?: [];
        if (isset($res['pass'])) {
            $res['password'] = $res['pass'];
            unset($res['pass']);
        }
        if ($res['host'] !== static::clean($server->getString('http_host'))) {
            return $res;
        }
        $subdir = (string) File::init(
            $server->getString(
                strpos(php_sapi_name(), 'cgi') !== false &&
                !ini_get('cgi.fix_pathinfo') &&
                $server->getBool('request_uri')
                    ? 'php_self'
                    : 'script_name'
            )
        )->getDir();
        if ($subdir && strpos($res['path'], $subdir) === 0) {
            $length       = strlen($subdir);
            $res['host'] .= '/' . substr($res['path'], 0, $length);
            $res['path']  = substr($res['path'], $length);
        }
        return $res;
    }

    protected function parseQuery(string $query): array
    {
        $res = [];
        parse_str($query, $res);
        return array_map([$this, 'clean'], $res);
    }
}
