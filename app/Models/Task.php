<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    protected $fillable = ['task', 'taskdescription', 'user_id', 'due'];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user'); // Specify pivot table name
    }

}
