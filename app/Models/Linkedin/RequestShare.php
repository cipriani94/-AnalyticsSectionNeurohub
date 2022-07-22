<?php

namespace App\Models\Linkedin;

use App\Models\AbstractModel\AbstractModel;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestShare extends AbstractModel
{
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
