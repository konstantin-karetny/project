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
        $query    = '',
        $scheme   = '',
        $user     = '',
        $vars     = [];

    public static function init(string $url = '')
    {
        return new static($url);
    }

    public function __construct(string $url = '')
    {
        $url = $url ? $this->clean($url) : $this->current();
        if ($this->check(!$url)) {
            return;
        }


        die(var_dump($this->parse($url)));


        foreach ($this->parse($url) as $k => $v) {
            $this->{'set' . ucfirst($k)}($v);
        }
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

    protected function clean(string $url): string
    {
        return
            $this->decode(
                (string) filter_var(
                    str_replace(
                        array_keys(static::config()->getArray('unsafe_chars_codes')),
                        array_values(static::config()->getArray('unsafe_chars_codes')),
                        $this->encode(strtolower(Str::trim($url, '/')))
                    ),
                    FILTER_SANITIZE_URL
                )
            );
    }

    protected function current(bool $cached = true): string
    {
        if (!$cached || !static::cache()->get('current')) {
            $res    = $this->clean($this->config()->getString('default_root'));
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
            static::cache()->set('current', $this->clean($res));
        }
        return static::cache()->get('current');
    }

    protected function decode(string $url): string
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

    protected function encode(string $url): string
    {
        return
            str_replace(
                array_values(static::config()->getArray('special_chars_codes')),
                array_keys(static::config()->getArray('special_chars_codes')),
                urlencode($url)
            );
    }

    protected function parse(string $url): array
    {
        $res    = [];
        $parts  = static::config()->getCmds('parts');
        $server = Server::init();
        foreach ((array) parse_url($this->clean($url)) as $k => $v) {
            if (in_array($k, $parts)) {
                $res[$k] = $this->clean($v);
            }
        }
        if ($res['host'] !== $this->clean($server->getString('http_host'))) {
            return $res;
        }
        $subdir = File::init(
            $server->getString(
                strpos(php_sapi_name(), 'cgi') !== false &&
                !ini_get('cgi.fix_pathinfo') &&
                $server->getBool('request_uri')
                    ? 'php_self'
                    : 'script_name'
            )
        )->getDir()->getPath();
        if ($subdir && strpos($res['path'] ?? '', $subdir) === 0) {
            $length       = strlen($subdir);
            $res['host'] .= '/' . substr($res['path'], 0, $length);
            $res['path']  = substr($res['path'], $length);
        }
        return array_map([__CLASS__, 'clean'], $res);
    }













    public function dropVar(string $name): bool {
        $name = Clean::cmd($name);
        if (isset($this->vars[$name])) {
            unset($this->vars[$name]);
            $this->query = $this->buildQuery($this->vars);
            return true;
        }
        return false;
    }

    public function getFragment(): string {
        return $this->fragment;
    }

    public function getHost(): string {
        return $this->host;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getPort(): int {
        return $this->port;
    }

    public function getQuery(): string {
        return $this->query;
    }

    public function getRoot(array $parts = self::ROOT_DEFAULT_PARTS): string {
        return $this->toStr(
            array_intersect(static::ROOT_PARTS, $parts)
        );
    }

    public function getScheme(): string {
        return $this->scheme;
    }

    public function getUser(): string {
        return $this->user;
    }

    public function getVar(
        string $name,
        string $filter,
               $default = ''
    ) {
        return Clean::value(
            $this->vars[Clean::cmd($name)] ?? $default,
            $filter
        );
    }

    public function getVars(string $filter = ''): array {
        return $filter ? Clean::values($this->vars, $filter) : $this->vars;
    }

    public function isInternal(): bool {
        return $this->getRoot() == static::init()->getRoot();
    }

    public function isSSL(): bool {
        return $this->scheme == 'https';
    }

    public function redirect(
        int  $delay              = 0,
        bool $replace            = true,
        int  $http_response_code = 302
    ) {
        if (headers_sent()) {
            echo (
                '<script>document.location.href="' .
                str_replace('"', '&apos;', $this->toStr()) .
                '";</script>'
            );
            return;
        }
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
        header(
            'Refresh: ' . ($delay > 0 ? $delay : 0) . '; ' . $this->toStr(),
            $replace,
            $http_response_code
        );
    }

    public function setFragment(string $fragment) {
        $this->fragment = static::clean($fragment);
    }

    public function setHost(string $host) {
        $host = static::clean($host);
        if ($host == '') {
            throw new Exception(
                'Argument 1 passed to ' . __METHOD__ . '() must not be empty',
                3
            );
        }
        $this->host = $host;
    }

    public function setPassword(string $password) {
        $this->password = static::clean($password);
    }

    public function setPath(string $path) {
        $this->path = static::clean($path);
    }

    public function setPort(int $port) {
        $this->port = $port;
    }

    public function setQuery(string $query) {
        $this->setVars($this->parseQuery($query));
    }

    public function setRoot(string $root) {
        $root_obj = static::init($root);
        foreach (static::ROOT_PARTS as $part) {
            $this->{'set' . ucfirst($part)}(
                $root_obj->{'get' . ucfirst($part)}()
            );
        }
    }

    public function setScheme(string $scheme) {
        $scheme = static::clean($scheme);
        if ($scheme == '') {
            throw new Exception(
                'Argument 1 passed to ' . __METHOD__ . '() must not be empty',
                4
            );
        }
        if (!in_array($scheme, static::SCHEMES)) {
            throw new Exception('Unknown scheme \'' . $scheme . '\'', 5);
        }
        $this->scheme = $scheme;
    }

    public function setUser(string $user) {
        $this->user = static::clean($user);
    }

    public function setVar(string $name, string $value) {
        $this->vars[Clean::cmd($name)] = static::clean($value);
        $this->query                   = $this->buildQuery($this->vars);
    }

    public function setVars(array $vars) {
        foreach ($vars as $k => $v) {
            $this->setVar($k, $v);
        }
    }

    public function toStr(array $parts = self::DEFAULT_PARTS): string {
        $res = '';
        if (in_array('scheme', $parts)) {
            $res .= $this->scheme . '://';
        }
        if (in_array('user', $parts) && $this->user != '') {
            $res .= $this->user . ':';
            if (in_array('password', $parts)) {
                $res .= $this->password;
            }
            $res .= '@';
        }
        if (in_array('host', $parts)) {
            $res .= $this->host;
        }
        if (in_array('port', $parts) && $this->port > 0) {
            $res .= ':' . $this->port;
        }
        if (in_array('path', $parts) && $this->path != '') {
            $res .= '/' . $this->path;
        }
        if (in_array('query', $parts) && $this->query != '') {
            $res .= '?' . $this->query;
        }
        if (in_array('fragment', $parts) && $this->fragment != '') {
            $res .= '#' . $this->fragment;
        }
        return $res;
    }

    protected function buildQuery(array $vars): string {
        return http_build_query($vars);
    }

    protected function parseQuery(string $query): array {
        $res = [];
        parse_str($query, $res);
        return $res;
    }
}
