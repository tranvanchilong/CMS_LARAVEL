<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Section extends Model
{
    public $timestamps = false;
    protected $table = 'lms_sections';

    protected $guarded = ['id'];

    public function children() {
        return $this->hasMany($this, 'section_group_id', 'id');
    }
}
