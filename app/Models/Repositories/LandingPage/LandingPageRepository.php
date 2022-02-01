<?php

namespace App\Models\Repositories\LandingPage;

use Illuminate\Support\Collection;
use App\Models\Repositories\LandingPage\LandingPageRepositoryInterface;
use App\Models\LandingPage;

class LandingPageRepository implements LandingPageRepositoryInterface
{
    public function all(): Collection
    {
        return LandingPage::query()
            ->where('account_uuid', account()->uuid)
            ->get();
    }

    public function find(string $uuid): LandingPage|null
    {
        return LandingPage::find($uuid);
    }

    public function create(array $attributes): LandingPage
    {
        return account()->landingPages()->create($attributes);
    }

    public function update(LandingPage $landingPage, array $attributes): bool
    {
        return $landingPage->update($attributes);
    }

}