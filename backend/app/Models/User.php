<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Administration\Entities\EmailSignature;
use Spatie\Permission\Traits\HasRoles;
/**
 * @method whoAmI()
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, GlobalSearchTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'business_unit'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function email_signature()
    {
        return $this->hasOne(EmailSignature::class);
    }

    /**
     * Determines the identity of the user.
     *
     * @param mixed $model The model object.
     * @param mixed $by    The attribute to identify the user.
     * @return string
     */
    // public static function whoAmI($model, $by): string
    // {
    //     return (string) (auth()->user()->id == (int)($model->{$by}->id)) ? 'You' : $model->{$by}?->name ?? null;
    // }
}
