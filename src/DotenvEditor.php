<?php

namespace Redot\DotenvEditor;

use Dotenv\Dotenv;
use Redot\DotenvEditor\Exceptions\FileNotFoundException;

class DotenvEditor
{
    /**
     * The environment variables.
     *
     * @var array<string, string>
     */
    protected array $env = [];

    /**
     * The path to the environment file.
     *
     * @var string
     */
    protected string $path;

    /**
     * Whether to backup the environment file.
     *
     * @var bool
     */
    protected bool $backup = false;

    /**
     * Create a new instance.
     *
     * @param string $path
     * @param bool $backup
     */
    public function __construct(string $path, bool $backup = false)
    {
        $this->path = $path;
        $this->backup = $backup;

        $this->load();
    }

    /**
     * Create a new instance.
     *
     * @param string $path
     * @param bool $backup
     */
    public static function make(string $path, bool $backup = false): self
    {
        return new static($path, $backup);
    }

    /**
     * Load the environment variables.
     *
     * @throws FileNotFoundException
     */
    protected function load(): void
    {
        if (!file_exists($this->path)) {
            throw new FileNotFoundException("File $this->path not found");
        }

        $contents = file_get_contents($this->path);
        $this->env = Dotenv::parse($contents);

        if ($this->backup) {
            $this->backup();
        }
    }

    /**
     * Get environment variable by key.
     *
     * @return array<string, string>
     */
    public function get(string $key, string $default = ''): string
    {
        return $this->env[$key] ?? $default;
    }

    /**
     * Set environment variable by key.
     *
     * @param array<string, string> $env
     */
    public function set(string $key, string $value): void
    {
        $this->env[$key] = $value;
    }

    /**
     * Save the environment variables.
     */
    public function save(): void
    {
        $contents = '';

        foreach ($this->env as $key => $value) {
            $contents .= $key . '=' . '"' . $this->escape($value) . '"' . PHP_EOL;
        }

        file_put_contents($this->path, $contents);
    }

    /**
     * Escape the value.
     */
    protected function escape(string $value): string
    {
        return str_replace('"', '\"', $value);
    }

    /**
     * Backup the environment file.
     */
    protected function backup(): void
    {
        $backupPath = $this->path . '.backup';

        if (file_exists($backupPath)) {
            unlink($backupPath);
        }

        copy($this->path, $backupPath);
    }
}
