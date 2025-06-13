<?php

namespace App\Interfaces;

interface ObjectRepositoryInterface
{
    public function getAll(array $filters);
}
