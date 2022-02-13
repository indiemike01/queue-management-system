<?php

class User
{
    private $id;
    private $position;
    public static $ids = array();

    public function __construct($id)
    {
        $this->id = $id;

        if (array_key_exists($id, self::$ids)) {
            throw new Exception('User with id ' . $id . ' already exists.');
        }
        self::$ids[$id] = &$this;
    }

    public static function lookupById($id)
    {
        return self::$ids[$id];
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getId()
    {
        return $this->id;
    }

    public static function removeUser($id)
    {
        unset(self::$ids[$id]);
    }

    public static function exist($id)
    {
        if (array_key_exists($id, self::$ids)) {
            return true;
        }
        return false;
    }
}
