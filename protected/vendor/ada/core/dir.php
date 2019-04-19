<?php

namespace Ada\Core;

class Dir extends Proto
{
    protected
        $path = '';

    public static function init(string $path = '')
    {
        return new static($path);
    }

    public function __construct(string $path = '')
    {
        $this->path = Value::path($path);
    }

    public function __toString(): string
    {
        return $this->getPath();
    }

    public function contents(): array
    {
        return array_merge($this->dirs(), $this->files());
    }

    public function copy(string $path): bool
    {
        if (!$this->exists()) {
            return false;
        }
        $dir = static::init($path);
        if ($dir->exists() || !$dir->create()) {
            return false;
        }
        $res = [];
        foreach ($this->dirs() as $subdir) {
            $res[] = static::init($subdir)->copy($dir . '/' . $subdir->getName());
        }
        foreach ($this->files() as $file) {
            $res[] = $file->copy($dir . '/' . $file->getBaseName());
        }
        return !in_array(false, $res);
    }

    public function create(int $mode = 0755): bool
    {
        if ($this->exists()) {
            return true;
        }
        $parent = $this->getDir();
        if (!$parent->exists()) {
            $parent->create($mode);
        }
        return (bool) @mkdir($this, $mode);
    }

    public function delete(): bool
    {
        $this->setPerms(0777);
        foreach ($this->dirs() as $dir) {
            $dir->delete();
        }
        foreach ($this->files() as $file) {
            $file->delete();
        }
        return (bool) @rmdir($this);
    }

    public function dirs(): array
    {
        $res = [];
        if (!$this->exists()) {
            return $res;
        }
        foreach (new \DirectoryIterator($this) as $iterator) {
            if ($iterator->isDir() && !$iterator->isDot()) {
                $path       = Value::path($iterator->getPathname());
                $res[$path] = static::init($path);
            }
        }
        ksort($res);
        return $res;
    }

    public function exists(): bool
    {
        return is_dir($this);
    }

    public function files(): array
    {
        $res = [];
        if (!$this->exists()) {
            return $res;
        }
        foreach (new \DirectoryIterator($this) as $iterator) {
            if ($iterator->isFile() && !$iterator->isDot()) {
                $path       = Value::path($iterator->getPathname());
                $res[$path] = File::init($path);
            }
        }
        ksort($res);
        return $res;
    }

    public function getDir(): Dir
    {
        return static::init(pathinfo($this, PATHINFO_DIRNAME));
    }

    public function getEditTime(): int
    {
        return (int) @stat($this)['mtime'];
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
        $res = 0;
        if (!$this->exists()) {
            return $res;
        }
        foreach(new \DirectoryIterator($this) as $iterator) {
            if (!$iterator->isDot()) {
                $res += $iterator->getSize();
            }
        }
        return $res;
    }

    public function isReadable(): bool
    {
        return is_readable($this);
    }

    public function isWritable(): bool
    {
        return is_writable($this);
    }

    public function move(string $path): bool
    {
        if (!$this->copy($path)) {
            return false;
        }
        return $this->delete();
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
}
