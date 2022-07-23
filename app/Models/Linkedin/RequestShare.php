<?php

namespace App\Models\Linkedin;

use App\Models\AbstractModel\AbstractModel;
use App\Models\Website;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestShare extends AbstractModel
{
    use CrudTrait;
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
