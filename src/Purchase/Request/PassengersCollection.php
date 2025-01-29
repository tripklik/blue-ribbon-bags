<?php

namespace Tripklik\BlueRibbonBags\Purchase\Request;

use Illuminate\Support\Collection;

class PassengersCollection extends Collection
{
    public function toArray(): array
    {
        return $this->map(fn (Passenger $passenger) => $passenger->toArray())->all();
    }
}
