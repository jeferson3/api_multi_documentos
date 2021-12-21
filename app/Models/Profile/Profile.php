<?php

namespace App\Models\Profile;

use App\Models\Permission\Permission;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    public $timestamps = true;

    protected $fillable = [
        'name', 'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Documents(): HasMany
    {
        return $this->hasMany(User::class, 'profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'profile_permission');
    }


}
