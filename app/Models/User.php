<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Role;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Model implements AuthenticatableContract, AuthorizableContract, HasMedia
{
    use Authenticatable, Authorizable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        "username",
        "password",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        "password",
        "remember_token",
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "password" => "hashed",
            "role" => Role::class,
        ];
    }

    /**
     * Get the user"s initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(" ")
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode("");
    }

    public function hasRole(string|Role $role): bool
    {
        if (is_string($role)) { $role = Role::from($role); }
        return ($this->role == $role);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->useFallbackUrl('/images/avatar.svg')
            ->useFallbackPath(public_path('/images/avatar.svg'))
            ->singleFile();
    }

    public function avatar(): string
    {
        return $this->getFirstMediaUrl('avatar');
    }
}
