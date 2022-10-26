<?php

namespace Uvodo\Cardlink;

/** @package Uvodo\Cardlink */
class ApiKey
{
    private string $value;

    public function __construct(string $clientId)
    {
        $this->value = $clientId;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
