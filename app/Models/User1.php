<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User1 extends Model
{
    use HasFactory;

    protected $table = 'users1'; 
    protected $primaryKey = 'id_user'; 
    protected $fillable = [
        'nama', 'username', 'password', 'role',
    ];
    protected $hidden = [
        'password',
    ];

    public function progressProjects()
    {
        return $this->hasMany(ProgressProject::class, 'teknisi_id', 'id_user');
    }
}
