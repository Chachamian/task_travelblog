<?php

namespace App;

use App\Mapper\Travel;
use App\Mapper\User;

class FileSystem
{
    private const APP_BD_PREFIX = '/bd/';

    /**
     * @return User[]
     */
    public function getUsersFileData(): array
    {
        $users =  $this->getInfoInDb('users') ?? [];

        return array_map(function($user) {
            return new User(
                $user->id,
                $user->userName,
                $user->password,
                $user->role,
            );
        }, $users);
    }

    public function writeUsersFileData(array $data)
    {
        $this->writeInfoInDb('users', $data);
    }

    /**
     * @return Travel[]
     */
    public function getTravelsFileData(): array
    {
        $travels = $this->getInfoInDb('travels') ?? [];

        return array_map(function ($travel) {
            return new Travel(
                $travel->id,
                $travel->userId,
                $travel->location,
                $travel->latitude,
                $travel->longitude,
                $travel->image,
                $travel->cost,
                $travel->culturalPlaces,
                $travel->visitPlaces,
                $travel->rating,
                $travel->vegetation ?? 1,
                $travel->safety ?? 1,
                $travel->population_density ?? 1
            );
        }, $travels);
    }

    public function writeTravelsFileData(array $data)
    {
        $this->writeInfoInDb('travels', $data);
    }

    public function AddIncrement(string $table): void
    {
        $data = $this->getInfoInDb('increment', true);
        $increment = $data[$table]['value'] ?? 0;
        $data[$table]['value'] = $increment + 1;
        $this->writeInfoInDb('increment', $data);
    }

    public function GetIncrement(string $table)
    {
        $data = $this->getInfoInDb('increment', true);
        $increment = $data[$table]['value'] ?? 0;
        return $increment + 1;
    }

    public function getInfoInDb($fileName, $isArray = false)
    {
        return json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . self::APP_BD_PREFIX . "{$fileName}.json"), $isArray);
    }

    public function writeInfoInDb($fileName, $data, $isUpdate = false): void
    {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . self::APP_BD_PREFIX . "{$fileName}.json", json_encode($data, JSON_UNESCAPED_UNICODE));
        if ($fileName !== 'increment' && !$isUpdate) {
            $this->AddIncrement($fileName);
        }
    }
}