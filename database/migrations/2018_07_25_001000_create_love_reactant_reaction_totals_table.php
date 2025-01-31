<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Love.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cog\Laravel\Love\Reactant\Models\Reactant;
use Cog\Laravel\Love\Reactant\ReactionTotal\Models\ReactionTotal;
use Cog\Laravel\Love\Support\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        $this->schema->create((new ReactionTotal())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reactant_id');
            $table->unsignedBigInteger('count');
            $table->decimal('weight', 13, 2);
            $table->timestamps();

            $table
                ->foreign('reactant_id')
                ->references('id')
                ->on((new Reactant())->getTable())
                ->onDelete('cascade');
        });
    }
};
