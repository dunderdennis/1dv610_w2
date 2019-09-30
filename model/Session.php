<?php

namespace model;

class Session
{
    private const SAVEGAME_STRING = "savegame";

    public function sessionIsSet(): bool
    {
        return isset($_SESSION[self::SAVEGAME_STRING]);
    }

    public function getSession(): object
    { 
        return $_SESSION[self::SAVEGAME_STRING];
    }

    public function setSession($session): void {
        $_SESSION[self::SAVEGAME_STRING] = $session;
    }
}
