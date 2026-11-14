<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        // Public pages (home, about, etc.)
        RateLimiter::for('pages', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        // Public read APIs (posts, categories, comments list)
        RateLimiter::for('public-api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        RateLimiter::for('auth', function (Request $request) {
            $email = Str::lower(trim((string) $request->input('email', $request->input('emailaddress', ''))));
            return [
                Limit::perMinute(5)->by($email . '|' . $request->ip()),
                Limit::perMinute(30)->by($request->ip()),
            ];
        });

        // Public forms (contact message, newsletter)
        RateLimiter::for('forms', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perDay(30)->by($request->ip()),
            ];
        });

        RateLimiter::for('panel', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        // Comment add/edit/delete
        RateLimiter::for('comments', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
        $this->registerGates();
    }

    private function registerGates(): void{

        // ---------- Role based abilities (used in routes with can:...) ----------
        Gate::define('manage-users',      fn (User $user) => $user->hasRole('admin'));
        Gate::define('manage-settings',   fn (User $user) => $user->hasRole('admin'));
        Gate::define('manage-messages',   fn (User $user) => $user->hasRole('admin'));
        Gate::define('moderate-comments', fn (User $user) => $user->hasRole('admin'));
        Gate::define('manage-categories', fn (User $user) => $user->hasRole('admin', 'editor'));
        Gate::define('write-posts',       fn (User $user) => $user->hasRole('admin', 'editor', 'author'));
        Gate::define('manage-all-posts',  fn (User $user) => $user->hasRole('admin', 'editor'));
        Gate::define('view-all-comments', fn (User $user) => $user->hasRole('admin', 'editor'));
        Gate::define('use-comments',      fn (User $user) => $user->hasRole('admin', 'editor', 'author', 'user'));

        // ---------- Posts: admin/editor can access every post, author only their own ----------
        $postAccess = fn (User $user, Post $post) =>
            ($user->hasRole('admin', 'editor') || $user->id === $post->author_id)
                ? Response::allow()
                : Response::denyAsNotFound();      // other people's post looks like "404 Not found"

        foreach (['view-post', 'update-post', 'delete-post'] as $ability) {
            Gate::define($ability, $postAccess);
        }

        // ---------- Comments ----------
        Gate::define('update-comment', fn (User $user, Comment $comment) =>
            $user->id === $comment->user_id
                ? Response::allow()
                : Response::denyAsNotFound());

        Gate::define('delete-comment', fn (User $user, Comment $comment) =>
            ($user->hasRole('admin') || $user->id === $comment->user_id)
                ? Response::allow()
                : Response::denyAsNotFound());


        // ---------- Users (admin only + safety rules) ----------
        Gate::define('update-user', function (User $user, User $target, string $newRole, string $newStatus) {
            if (! $user->hasRole('admin')) {
                return Response::deny('Only admins can edit users.');
            }
            if ($user->id === $target->id && ($newRole !== 'admin' || $newStatus === 'blocked')) {
                return Response::deny('You cannot demote or block yourself.');
            }
            if ($target->hasRole('admin') && $newRole !== 'admin' && User::where('role', 'admin')->count() <= 1) {
                return Response::deny('The last admin cannot be demoted.');
            }
            return Response::allow();
        });

        Gate::define('delete-user', function (User $user, User $target) {
            if (! $user->hasRole('admin')) {
                return Response::deny('Only admins can delete users.');
            }
            if ($user->id === $target->id) {
                return Response::deny('You cannot delete your own account.');
            }
            if ($target->hasRole('admin') && User::where('role', 'admin')->count() <= 1) {
                return Response::deny('The last admin cannot be deleted.');
            }
            return Response::allow();
        });


    }
        


}
