<?php

namespace Modules\Products\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
class RandomUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if a user is already authenticated
        if (auth()->check()) {
            return $next($request);
        }

        // use the factory to create a Faker\Generator instance
        $faker = \Faker\Factory::create();
        // Create a new user
        $user = User::create([
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail,
            'password' => bcrypt('password'),
        ]);

        // Authenticate the user
        auth()->login($user);

        return $next($request);
    }
}
