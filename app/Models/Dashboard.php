<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model // This should be Task, not Dashboard
{
use HasFactory;

protected $fillable = ['task', 'taskdescription', 'user_id', 'status'];

public function user(): BelongsToMany
{
return $this->belongsToMany(User::class, 'task_user');
}
}
