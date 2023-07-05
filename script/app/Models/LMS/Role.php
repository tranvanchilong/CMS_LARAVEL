<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Role extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Role::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public $timestamps = false;
    protected $table = 'lms_roles';
    static $admin = 'admin';
    static $user = 'user';
    static $teacher = 'teacher';
    static $organization = 'organization';

    protected $guarded = ['id'];

    public function canDelete()
    {
        switch ($this->name) {
            case self::$admin:
            case self::$user:
            case self::$organization:
            case self::$teacher:
                return false;
                break;
            default:
                return true;
        }
    }

    public function users()
    {
        return $this->hasMany('App\Models\LMS\User', 'role_id', 'id');
    }

    public function isDefaultRole()
    {
        return in_array($this->name, [self::$admin, self::$user, self::$organization, self::$teacher]);
    }

    public function isMainAdminRole()
    {
        return $this->name == self::$admin;
    }

    public static function getUserRoleId()
    {
        $id = 1; // user role id

        $role = self::where('name', self::$user)->first();

        return !empty($role) ? $role->id : $id;
    }

    public static function getTeacherRoleId()
    {
        $id = 4; // teacher role id

        $role = self::where('name', self::$teacher)->first();

        return !empty($role) ? $role->id : $id;
    }

    public static function getOrganizationRoleId()
    {
        $id = 3; // teacher role id

        $role = self::where('name', self::$organization)->first();

        return !empty($role) ? $role->id : $id;
    }
}
