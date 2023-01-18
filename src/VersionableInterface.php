<?php

declare(strict_types=1);

namespace CapeAndBay\Versionable;

use Illuminate\Database\Eloquent\Model;

interface VersionableInterface
{
    public function onNewVersionCreate(Model $model): bool;
}
