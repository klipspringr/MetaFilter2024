<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\EnvironmentEnum;
use Database\Seeders\Development\AdminSeeder;
use Database\Seeders\Development\FakeBannerLinkSeeder;
use Database\Seeders\Development\FakeCommentSeeder;
use Database\Seeders\Development\FakePostSeeder;
use Database\Seeders\Development\FakeTagSeeder;
use Database\Seeders\Development\FakeUserSeeder;
use Database\Seeders\Development\FavoriteSeeder;
use Database\Seeders\Development\FlagSeeder;
use Database\Seeders\Production\FlagReasonSeeder;
use Database\Seeders\Production\PageSeeder;
use Database\Seeders\Production\PermissionSeeder;
use Database\Seeders\Production\RoleSeeder;
use Database\Seeders\Production\SnippetSeeder;
use Database\Seeders\Production\SubsiteSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $this->seedProductionData();

        if (config('app.env') !== EnvironmentEnum::Production) {
            $this->seedDevelopmentData();
        }

        Model::reguard();
    }

    private function seedDevelopmentData(): void
    {
        $this->call([
            AdminSeeder::class,
            FakeBannerLinkSeeder::class,
            FakeTagSeeder::class,
            FakeUserSeeder::class,

            // Needs FakeTagSeeder and FakeUserSeeder
            FakePostSeeder::class,

            // Needs FakeUserSeeder and FakePostSeeder
            FakeCommentSeeder::class,

            FavoriteSeeder::class,
            FlagSeeder::class,
        ]);
    }

    private function seedProductionData(): void
    {
        $this->call([
            FlagReasonSeeder::class,
            SubsiteSeeder::class,
            PageSeeder::class,
            SnippetSeeder::class,

            PermissionSeeder::class,

            // Needs PermissionSeeder
            RoleSeeder::class,
        ]);
    }
}
