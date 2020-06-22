<?php

class User extends Spacewind\Models\User
{
    protected $fillable = ['username', 'firstname', 'lastname', 'password', 'user_type_id', 'description','upload_id', 'active'];
    protected $auth_fillable = ['username', 'firstname', 'lastname', 'password'];
    protected $guarded = ['id'];
    public $relations = ['type','upload'];



    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = $value;
    }

    public function upload()
    {
        return $this->belongsTo('Upload');
    }
}
