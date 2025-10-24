<?php

namespace App\Repositories\Saas;

use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/*
 * This Repository is the base for all the SaaS Features
 * It includes Below Features
 * 1. By Default Apply Owner Scope the model
 * 2. Add School ID automatically while Creating an Entry
 */

class SaaSRepository extends BaseRepository
{


    public function defaultModel()
    {
        return parent::defaultModel()->owner();
    }

    public function create(array $payload): Model
    {
        
        return parent::create($payload)->fresh();
    }

    public function createBulk(array $payload): bool
    {
        $payload = array_map(static function ($d) {
            return $d;
        }, $payload);
        return parent::createBulk($payload);
    }

    public function update(int $modelId, array $payload): ?Model
    {
        
        return parent::update($modelId, $payload);

    }

    public function updateOrCreate(array $uniqueColumns, array $updatingColumn): Model
    {
     
        return parent::updateOrCreate($uniqueColumns, $updatingColumn);
    }

    public function upsert(array $payload, array $uniqueColumns, array $updatingColumn): bool
    {
        $payload = array_map(static function ($d) {
            return $d;
        }, $payload);
        return parent::upsert($payload, $uniqueColumns, $updatingColumn);
    }
}
