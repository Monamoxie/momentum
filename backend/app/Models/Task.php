<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $with = ['children'];

    protected $fillable = ['name', 'label', 'priority', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    // Child tasks relationship
    public function children()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }
}
