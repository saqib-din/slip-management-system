<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function getRoleBadgeClass(): string
    {
        return match ($this->role) {
            'admin'   => 'bg-light-danger text-danger',
            'manager' => 'bg-light-warning text-warning',
            'user'    => 'bg-light-primary text-primary',
            default   => 'bg-light-secondary text-secondary',
        };
    }

    public function getRoleLabel(): string
    {
        return match ($this->role) {
            'admin'   => 'Admin',
            'manager' => 'Manager',
            'user'    => 'User',
            default   => ucfirst($this->role),
        };
    }

    public function getStatusBadgeClass(): string
    {
        return $this->status === 'active'
            ? 'bg-light-success text-success'
            : 'bg-light-danger text-danger';
    }
}
