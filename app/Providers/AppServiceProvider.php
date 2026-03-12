<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Models\Student;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // FORCE HTTPS FOR RAILWAY
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        View::composer('admin.admin_sidebar', function ($view) {
            $newStudentCount = Student::where('verification_status', 'pending')->count();
            $user = session('user');

            $view->with('newStudentCount', $newStudentCount)
                 ->with('user', $user);
        });
    }
}
