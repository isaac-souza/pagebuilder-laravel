<?php

namespace App\Models\Repositories\LandingPage\Decorators;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use App\Models\Repositories\LandingPage\LandingPageRepositoryInterface;
use App\Models\Repositories\LandingPage\LandingPageRepository;
use App\Models\LandingPage;

class LandingPageRepositoryCacheDecorator implements LandingPageRepositoryInterface
{
    public function __construct(private LandingPageRepository $landingPageRepository)
    {
        // 
    }

    public function all(): Collection
    {
        return Cache::rememberForever('landingPages.' . account()->uuid, function() {
            return $this->landingPageRepository->all();
        });
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