<?php

    namespace App;

    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;

    class Admin extends Authenticatable
    {
        use Notifiable;

        protected $guard = 'admin';

        protected $fillable = [
            'admin_name', 'admin_email', 'admin_phone','password',
        ];

        protected $primaryKey = 'admin_no';

        protected $hidden = [
            'password', 'remember_token',
        ];
    }