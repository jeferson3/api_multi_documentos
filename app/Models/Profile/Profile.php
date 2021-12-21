<?php

namespace App\Models\Profile;

use App\Models\Permission\Permission;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    public $timestamps = true;

    protected $fillable = [
        'id', 'name', 'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Users(): HasMany
    {
        return $this->hasMany(User::class, 'profile_id');
    }

}
