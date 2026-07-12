<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'user_id', 'parent_id', 'text', 'x', 'y', 'status', 'element_selector', 'element_xpath', 'offset_x', 'offset_y'];

    protected $casts = [
        'x' => 'float',
        'y' => 'float',
        'offset_x' => 'float',
        'offset_y' => 'float',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
