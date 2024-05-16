<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        /* $this->call(PostSeeder::class);
        $this->call(CommentSeeder::class); */

        /* Post::factory()
            ->times(10)
            ->hasComments(5)
            ->create()
        ;

        User::factory()
            ->times(5)
            ->hasPosts(10)
            ->hasComments(5)
            ->create()
        ; */

        /* User::factory()
            ->times(5)
            ->has(
                Post::factory()->count(10)
                ->has(Comment::factory()->count(5))
            )
            ->hasComments(5)
            ->create()
        ; */

        Role::factory()->count(3)->create();

        User::factory()->count(5)->create()->each(function (User $user) {
            Post::factory()->count(10)->create(['user_id' => $user->id])->each(function (Post $post) use ($user) {
                Comment::factory()->count(5)->create([
                    'post_id' => $post->id,
                    'user_id' => $user->id
                ]);
            });
        });

        User::create([
            'name' => "Jonh DOE",
            'email' => "john@doe.fr",
            'email_verified_at' => now(),
            'password' => Hash::make("123456789"), // password
            'remember_token' => Str::random(10),
        ])->roles()->sync([Role::where('name', 'admin')->first()->id, Role::where('name', 'user')->first()->id]);
    }
}
