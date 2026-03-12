<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Student;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('admin.admin_sidebar', function ($view) {
            $newStudentCount = Student::where('verification_status', 'pending')->count();
            $user = session('user');
            $view->with('newStudentCount', $newStudentCount)->with('user', $user);
        });
    }
}
