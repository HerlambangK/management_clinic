<?php

namespace App\Models\Concerns;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToStore
{
    public static function bootBelongsToStore(): void
    {
        static::addGlobalScope('store', function (Builder $builder): void {
            $store = current_store();

            if (! $store) {
                return;
            }

            $builder->where($builder->getModel()->getTable().'.store_id', $store->id);
        });

        static::creating(function (Model $model): void {
            if ($model->store_id) {
                return;
            }

            $store = current_store();

            if ($store) {
                $model->store_id = $store->id;
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
