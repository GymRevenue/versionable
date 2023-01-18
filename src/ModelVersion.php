<?php

declare(strict_types=1);

namespace CapeAndBay\Versionable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $versionable_type
 * @property string $versionable_id
 * @property string $owner_id
 */
class ModelVersion extends Model
{
    public function versionable(): MorphTo
    {
        return $this->morphTo('versionable');
    }
}
