<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssueDiscussion extends Model
{
    protected $fillable = [
        'issue_id', 'user_id', 'text'
    ];

    public function issue()
    {
        return $this->hasOne('\App\Issue', 'id', 'issue_id');
    }

    public function creator()
    {
        return $this->hasOne('\App\User', 'id', 'user_id');
    }
}