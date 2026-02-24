<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportLog extends Model
{
    use BelongsToStore;

    protected $fillable = [
        'store_id',
        'user_id',
        'entity_type',
        'file_name',
        'original_file_name',
        'total_rows',
        'success_count',
        'error_count',
        'skipped_count',
        'status',
        'errors',
        'summary',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'total_rows' => 'integer',
            'success_count' => 'integer',
            'error_count' => 'integer',
            'skipped_count' => 'integer',
            'errors' => 'array',
            'summary' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getEntityTypeLabelAttribute(): string
    {
        return __("import.{$this->entity_type}") !== "import.{$this->entity_type}"
            ? __("import.{$this->entity_type}")
            : $this->entity_type;
    }

    public function getStatusLabelAttribute(): string
    {
        return __("import.status_{$this->status}") !== "import.status_{$this->status}"
            ? __("import.status_{$this->status}")
            : $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            default => 'gray',
        };
    }

    public function getSuccessRateAttribute(): float
    {
        if ($this->total_rows === 0) {
            return 0;
        }

        return round(($this->success_count / $this->total_rows) * 100, 1);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
