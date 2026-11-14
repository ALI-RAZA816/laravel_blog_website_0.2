<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * JSON files are in database/seeders/data/.
     * The order matters: parent tables first, child tables after.
     */
    private array $tables = [
        'users',               // 100
        'categories',          // 8
        'posts',               // 100 (needs users + categories)
        'post_views',          // needs users + posts
        'comments',            // needs users + posts
        'monthly_reports',     // needs users
        'last_months_views',   // monthly chart data
        'messages',
        'newsletters',         // needs users
        'settings',
    ];

    public function run(): void
    {
        // Hash once. Hash::make() for every row would be very slow.
        $hashed = Hash::make(env('SEED_PASSWORD', 'password'));

        foreach ($this->tables as $table) {
            $path = database_path("seeders/data/{$table}.json");
            $rows = json_decode(file_get_contents($path), true);

            foreach ($rows as &$row) {
                if ($table === 'users') {
                    $row['password'] = $hashed;
                }
            }
            unset($row);

            foreach (array_chunk($rows, 200) as $chunk) {
                DB::table($table)->insert($chunk);
            }

            $this->command->info("Seeded {$table}: " . count($rows));
        }

        $this->makePlaceholderImages();

        $this->command->info('Login: admin@example.com / ' . env('SEED_PASSWORD', 'password'));
    }

    /**
     * Posts and users only store an image file name, so we create small
     * placeholder images in public/posts-images and public/uploads (needs GD).
     */
    private function makePlaceholderImages(): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            $this->command->warn('GD is not enabled, so placeholder images were skipped.');
            return;
        }

        $make = function (string $dir, string $prefix, int $count, int $w, int $h) {
            @mkdir(public_path($dir), 0777, true);
            for ($i = 1; $i <= $count; $i++) {
                $img = imagecreatetruecolor($w, $h);
                imagefill($img, 0, 0, imagecolorallocate($img, rand(40, 200), rand(40, 200), rand(40, 200)));
                imagestring($img, 5, 10, 10, "{$prefix}-{$i}", imagecolorallocate($img, 255, 255, 255));
                imagepng($img, public_path("{$dir}/{$prefix}-{$i}.png"));
                imagedestroy($img);
            }
        };

        $make('posts-images', 'post', 10, 800, 450);
        $make('uploads', 'avatar', 8, 200, 200);
    }
}
