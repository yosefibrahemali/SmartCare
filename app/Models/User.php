<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'patient_id',
    ];
   
    public function canAccessFilament(): bool
    {
        return $this->role === 'admin';
    }

    // إذا كان المستخدم مريض، هذه علاقته مع أقاربه
    public function relatives()
    {
        return $this->hasMany(User::class, 'patient_id')->where('role', 'relative');
    }

    // إذا كان المستخدم قريب، هذه علاقته مع المريض
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }


    public function medications()
    {
        return $this->hasMany(Medication::class, 'patient_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
