<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercises extends Model
{
    use HasFactory;
    protected $table = 'workouts_exercises';

    protected $fillable = [
        'title',
        'type',
        'series',
        'repetitions',
        'done_at',
        'workout_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
