<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory;
    protected $primaryKey = 'id';

    public function scopeParent($query)
    {
        return $query->where(function($query) {
            $query->whereNotLike('name', '%-%')
                ->whereNotLike('name', '%Read%')
                ->whereNotLike('name', '%Create%')
                ->whereNotLike('name', '%Edit%')
                ->whereNotLike('name', '%Delete%')
                ->whereNotLike('name', '%Setting%');
        })->orderBy('order');
    }

    public function scopeChild($query)
    {
        return $query->where(function($query) {
            $query->where('name', 'like', '%-%')
                ->orWhere('name', 'like', '%Read%')
                ->orWhere('name', 'like', '%Create%')
                ->orWhere('name', 'like', '%Edit%')
                ->orWhere('name', 'like', '%Delete%')
                ->orWhere('name', 'like', '%Setting%');
        });
    }
}
