<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $table = 'course_module_lessons';
    protected $fillable = [
        'course_id',
        'name',
        'duration',
    ];
    public function lessonBelongsToModule()
  {
    return $this->belongsTo('App\Module');
  }
}
