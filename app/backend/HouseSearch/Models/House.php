<?php

namespace HouseSearch\Models;

use Core\Exceptions\External;

class House implements \JsonSerializable
{
    private static $fields = [
        'id',
        'address',
    ];
    private static $basePath = "/var/data/houses/";
    /** @var string */
    private $id;
    private $address;

    /**
     * @return self[];
     */
    public static function getAll() {
        $path = APP_ROOT . self::$basePath;

        $objs = [];
        foreach (scandir($path) as $fileName) {
            if (substr($fileName,0,1) == '.') {
                continue;
            }

            $objs[] = self::getById($fileName);
        }

        return $objs;
    }

    /**
     * @param $id
     * @return self
     */
    public static function getById($id)
    {
        $path = APP_ROOT . self::$basePath;
        $data = json_decode(file_get_contents("$path/$id"));
        $obj = new self();
        $obj->loadFromObject($data);

        return $obj;
    }

    public function jsonSerialize() {
        $obj = new \stdClass();
        foreach (self::$fields as $fieldName) {
            $obj->$fieldName = $this->$fieldName;
        }

        return $obj;
    }

    public function save()
    {
        if (!$this->id) {
            throw new External("Missing house id", 400);
        }
        $path = APP_ROOT . self::$basePath . $this->id;

        $json = json_encode($this);
        return (bool) file_put_contents($path, $json);
    }

    public function getPublicObject()
    {
        $obj = new \stdClass();

        $obj->id = $this->id;
        $obj->address = $this->address;

        return $obj;
    }

    public function loadFromObject($object)
    {
        foreach (self::$fields as $fieldName) {
            if (property_exists($this, $fieldName) && property_exists($object, $fieldName)) {
                $this->$fieldName = $object->$fieldName;
            }
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function generateId()
    {
        $this->id = uniqid();
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }
}