<?php

namespace App\Mapper;

class Travel
{
    public ?int $id;
    public ?int $userId;
    public ?string $location;
    public ?float $latitude;
    public ?float $longitude;
    public ?string $image;
    public ?float $cost;
    public ?string $culturalPlaces;
    public ?string $visitPlaces;
    public ?int $rating;
    public ?string $createdAt;
    public ?int $vegetation;
    public ?int $safety;
    public ?int $population_density;

    public function __construct($id = null, $userId = null, $location = null, $latitude = null,
                                $longitude = null, $image = null, $cost = null,
                                $culturalPlaces = null, $visitPlaces = null, $rating = null,
                                $vegetation = null, $safety = null, $population_density = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->location = $location;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->image = $image;
        $this->cost = $cost;
        $this->culturalPlaces = $culturalPlaces;
        $this->visitPlaces = $visitPlaces;
        $this->rating = $rating;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->vegetation = $vegetation;
        $this->safety = $safety;
        $this->population_density = $population_density;
    }
}