<?php

class User extends Spacewind\Models\User
{
    protected $fillable = ['username', 'firstname', 'lastname', 'password', 'user_type_id', 'description','upload_id', 'active','cti_user_id'];
    protected $auth_fillable = ['username', 'firstname', 'lastname', 'password'];
    protected $guarded = ['id'];
    public $relations = ['type','upload','cti_user'];



    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = $value;
    }

    public function upload()
    {
        return $this->belongsTo('Upload');
    }

    public function cti_user()
    {
        return $this->belongsTo('CtiUser','cti_user_id','Код');
    }
}