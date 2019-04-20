<?php

namespace Ada\Core;

class File extends Proto
{
    protected
        $path = '';

    public static function init(string $path)
    {
        return new static($path);
    }

    public function __construct(string $path)
    {
        $this->path = Value::path($path);
    }

    public function __toString(): string
    {
        return $this->getPath();
    }

    public function copy(string $path): File
    {
        $res = static::init(Value::path($path));
        if (!$res->getDir()->create()) {
            return $res;
        }
        @copy($this, $res);
        return $res;
    }

    public function create(string $contents = ''): bool
    {
        return $this->write($contents);
    }

    public function delete(): bool
    {
        $this->setPerms(0777);
        return (bool) @unlink($this);
    }

    public function exists(): bool
    {
        return is_file($this);
    }

    public function getBaseName(): string
    {
        return pathinfo($this, PATHINFO_BASENAME);
    }

    public function getDir(): Dir
    {
        return Dir::init(pathinfo($this, PATHINFO_DIRNAME));
    }

    public function getEditTime(): int
    {
        return (int) @filemtime($this);
    }

    public function getExt(): string
    {
        return pathinfo($this, PATHINFO_EXTENSION);
    }

    public function getMimeType(): string
    {
        return
            $this->exists() && class_exists('finfo')
                ? (new \finfo())->file($this, FILEINFO_MIME_TYPE)
                : '';
    }

    public function getName(): string
    {
        return pathinfo($this, PATHINFO_FILENAME);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getPerms(): int
    {
        return (int) @fileperms($this);
    }

    public function getSize(): int
    {
        return (int) @filesize($this);
    }

    public function isReadable(): bool
    {
        return is_readable($this);
    }

    public function isWritable(): bool
    {
        return is_writable($this);
    }

    public function move(string $path): File
    {
        $res = static::init(Value::path($path));
        $dir = $res->getDir();
        if (!$dir->create()) {
            return $res;
        }
        @rename($this, $res);
        return $res;
    }

    public function parseIni(
        bool $process_sections = true,
        int  $scanner_mode     = INI_SCANNER_TYPED
    ): DataSet
    {
        return
            DataSet::init(
                @parse_ini_file($this, $process_sections, $scanner_mode) ?: []
            );
    }

    public function read(
        int $offset  = 0,
        int $maxlen  = null,
            $context = null
    ): string
    {
        $args = [$this, false, $context, $offset];
        if ($maxlen !== null) {
            array_push($args, $maxlen);
        }
        return (string) @file_get_contents(...$args);
    }

    public function setEditTime(int $time = 0): bool
    {
        return
            (bool) @touch(
                $this,
                $time ?: DateTime::init()->getTimestamp() ///////////////////////////////////
            );
    }

    public function setPerms(int $mode): bool
    {
        return (bool) @chmod($this, $mode);
    }

    public function write(
        string $contents,
        int    $flags   = FILE_APPEND,
               $context = null
    ): bool
    {
        return
            $this->getDir()->create() &&
            @file_put_contents($this, $contents, $flags, $context) !== false;
    }
}
