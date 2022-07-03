<?php

namespace MVC\Core;

class Session
{
    public const FLASH_KEY = "flash";

    public function __construct()
    {
        session_start();

        if (!is_null($_SESSION[self::FLASH_KEY] ?? null)) {
            $this->trashFlashes();
            $this->markFlashesForTrash();
        }
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key)
    {
        return $_SESSION[$key];
    }

    public function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function destroy(): void
    {
        session_destroy();
    }

    public function createFlash($name, $message): void
    {
        $_SESSION[self::FLASH_KEY][$name] = [
            'message' => $message,
            'removed' => false
        ];
    }

    public function getFlashMessage($name): string
    {
        return $_SESSION[self::FLASH_KEY][$name]['message'] ?? '';
    }

    public function flashExists($name): bool {
        return isset($_SESSION[self::FLASH_KEY][$name]);
    }

    private function markFlashesForTrash(): void
    {
        foreach ($_SESSION[self::FLASH_KEY] as $key => $value) {
            $_SESSION[self::FLASH_KEY][$key]['removed'] = true;
        }
    }

    private function trashFlashes(): void
    {
        foreach ($_SESSION[self::FLASH_KEY] as $key => $value) {
            if ($value['removed']) {
                unset($_SESSION[self::FLASH_KEY][$key]);
            }
        }
    }
}