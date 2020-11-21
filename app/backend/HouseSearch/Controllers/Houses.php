<?php

namespace HouseSearch\Controllers;

use Core\Controller\Json;
use Core\Exceptions\External;
use HouseSearch\Models\House;

class Houses extends Json
{
    public function GET()
    {
        $out = [];
        foreach (House::getAll() as $house) {
            $out[] = $house->getPublicObject();
        }
        return $out;
    }

    public function POST()
    {
        $fields = $this->getParameters();

        $house = new House();
        $house->loadFromObject($fields);
        $house->generateId();
        $house->save();

        return $house->getPublicObject();
    }

    public function GET_PARAMS()
    {
        $houseId = $this->getRouterParameter('houseId');
        $house = House::getById($houseId);

        if (!$house) {
            throw new External("Not found", 404);
        }

        return $house->getPublicObject();
    }

    public function PATCH_PARAMS()
    {
        $houseId = $this->getRouterParameter('houseId');
        $house = House::getById($houseId);

        if (!$house) {
            throw new External("Not found", 404);
        }

        $data = $this->getParameters();
        unset($data->id);
        $house->loadFromObject($data);
        $house->save();

        return $house->getPublicObject();
    }

    public function OPTIONS_PARAMS()
    {
        $houseId = $this->getRouterParameter('houseId');
        $house = House::getById($houseId);

        if ($house) {
            return ['message' => 'OK'];
        } else {
            throw new External("Device not found", 404);
        }
    }
}