<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $table = 'modules';
    protected $fillable = [
        'course_id',
        'name',
        'duration',
    ];
    public function moduleBelongsToCourse()
    {
        return $this->belongsTo('App\Course',);
    }
    public function lessons()
    {
        return $this->hasMany('App\Lesson');
    }

}
