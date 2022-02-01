<?php

namespace App\Models\Repositories\LandingPage;

use App\Models\LandingPage;
use Illuminate\Support\Collection;

interface LandingPageRepositoryInterface
{
    public function all(): Collection;
    public function find(string $uuid): LandingPage|null;
    public function create(array $attributes): LandingPage;
    public function update(LandingPage $landingPage, array $attributes): bool;
}
