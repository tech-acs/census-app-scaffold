<?php

namespace Uneca\Scaffold\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Translatable\HasTranslations;

class Source extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $guarded = ['id'];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];
    public $translatable = ['title'];

    public function analytics()
    {
        return $this->morphMany(Analytics::class, 'analyzable')->orderBy('completed_at');
    }

    public function scopeActive($query)
    {
        return $query->where('connection_active', true);
    }

    private function testCanConnect()
    {
        try {
            DB::connection($this->name)->getPdo();
            return ['passes' => true, 'message' => ''];
        } catch (\Exception $exception) {
            return ['passes' => false, 'message' => $exception->getMessage()];
        }
    }

    public function test()
    {
        $result = collect([]);
        $result->add($this->testCanConnect());
        return $result;
    }
}
