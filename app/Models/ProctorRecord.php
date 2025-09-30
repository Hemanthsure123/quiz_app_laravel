<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProctorRecord extends Model
{
    protected $fillable = ['user_id', 'exam_id', 'camera_video_path', 'screen_video_path'];
}
