<?php

declare(strict_types=1);

namespace CapeAndBay\Versionable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * @property Collection $versions
 * @property Model $latestVersion
 * @property Model $earliestVersion
 */
trait Versionable
{
    public static function bootVersionable(): void
    {
        static::updating(function (Model $model) {
            /** @var Model $new_model_version */
            $new_model_version = $model->replicate(['updated_at']);
            $new_model_version->created_at = Carbon::now();
            if ($model instanceof VersionableInterface && $model->onNewVersionCreate($new_model_version) === false) {
                return;
            }

            $new_model_version->save();

            $version = new ModelVersion();
            $version->owner_id = $model->getOriginal($id = $model->getKeyName());
            $version->versionable_type = $new_model_version::class;
            $version->versionable_id = $new_model_version->{$id};
            $version->save();

            return false;
        });
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ModelVersion::class, 'owner_id')
            ->where('versionable_type', static::class)
            ->with('versionable');
    }

    public function latestVersion(): Model
    {
        return $this->hasOne(ModelVersion::class, 'owner_id')
            ->where('versionable_type', static::class)
            ->with('versionable')
            ->latest('created_at')
            ->first()
            ->versionable;
    }

    public function oldestVersion(): Model
    {
        return $this->hasOne(ModelVersion::class, 'owner_id')
            ->where('versionable_type', static::class)
            ->with('versionable')
            ->oldest('created_at')
            ->first()
            ->versionable;
    }
}
