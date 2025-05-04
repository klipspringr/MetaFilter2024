<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private const array TABLES = [
        [
            'name' => 'comments',
            'fields' => [
                'body',
            ],
        ],
        [
            'name' => 'filament_simple_pages',
            'fields' => [
                'title',
                'body',
            ],
        ],
        [
            'name' => 'posts',
            'fields' => [
                'title',
                'body',
            ],
        ],
        [
            'name' => 'users',
            'fields' => [
                'username',
            ],
        ],
    ];

    public function up(): void
    {
        foreach (self::TABLES as $tableData) {
            $tableName = $tableData['name'];
            $fields = implode(', ', $tableData['fields']);

            DB::statement(
                "ALTER TABLE $tableName ADD FULLTEXT fulltext_index($fields)",
            );
        }
    }
};
