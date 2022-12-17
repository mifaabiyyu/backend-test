<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    use HasFactory;
    protected $table = 'model_has_roles';

    public function data_roles()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
