<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Document;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    const ADMIN_UNIT_ID = 1;

    public function isAdminUnit(): bool
    {
        return $this->id === self::ADMIN_UNIT_ID;
    }

    /** @param User $user */
    public static function visibleToUser(User $user)
    {
        if ($user->isAdmin()) {
            return self::all();
        }

        return self::where('id', '!=', self::ADMIN_UNIT_ID)->get();
    }

    public function scopeNonAdmin($query)
    {
        return $query->where('id', '!=', self::ADMIN_UNIT_ID);
    }

    public static function adminUnit(): ?self
    {
        return self::find(self::ADMIN_UNIT_ID);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function sentDocuments()
    {
        return $this->hasMany(Document::class, 'sender_unit_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function receivedDocuments()
    {
        return $this->hasMany(Document::class, 'receiving_unit_id');
    }
}
