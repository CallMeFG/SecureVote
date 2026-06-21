<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nim', 'name', 'email', 'photo_path', 'password', 'is_active', 'is_voted', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_voted' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function otpToken()
    {
        return $this->hasOne(OtpToken::class);
    }

    public function vote()
    {
        return $this->hasOne(Vote::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function scopeSudahMemilih($query)
    {
        return $query->where('is_voted', true);
    }

    public function scopeBelumMemilih($query)
    {
        return $query->where('is_voted', false);
    }

    public function scopeMahasiswaPcr($query)
    {
        return $query->where('email', 'like', '%@mahasiswa.pcr.ac.id');
    }
}
