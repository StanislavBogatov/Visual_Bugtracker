<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{

    protected $fillable = ['name', 'description', 'thumbnail_img', 'creator_user_id'];

    public function creator()
    {
        return $this->hasOne('App\User', 'id', 'creator_user_id');
    }

    public function team()
    {
        return $this->hasMany('App\ProjectAccess', 'project_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany('App\Activity', 'project_id', 'id');
    }

    public function boards()
    {
        return $this->hasMany('App\Board', 'project_id', 'id');
    }

    public function issues()
    {
        return $this->hasMany('App\Issue', 'project_id', 'id');
    }

    public function project_access()
    {
        return $this->hasMany('App\ProjectAccess', 'project_id', 'id');
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function thumbnailUrl()
    {
        $imagePath = config('images.project_thumb_dir') . '/' . $this->thumbnail_img;
        return Storage::disk('s3')->url($imagePath);
    }
}