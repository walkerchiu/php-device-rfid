<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWkDeviceRFIDTable extends Migration
{
    public function up()
    {
        Schema::create(config('wk-core.table.device-rfid.readers'), function (Blueprint $table) {
            $table->uuid('id');
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->unsignedBigInteger('order')->nullable();
            $table->boolean('is_enabled')->default(0);

            $table->unsignedSmallInteger('slave_id');
            $table->string('ip');
            $table->unsignedSmallInteger('port');
            $table->unsignedSmallInteger('scan_interval')->default(500);
            $table->char('sync_at', 6)->nullable();
            $table->boolean('is_multiplex')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_enabled');
            $table->index('slave_id');
            $table->index(['ip', 'port']);
        });
        if (!config('wk-device-rfid.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.device-rfid.readers_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }

        Schema::create(config('wk-core.table.device-rfid.readers_registers'), function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('reader_id');
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->string('mean');
            $table->string('data_type');
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('reader_id')->references('id')
                  ->on(config('wk-core.table.device-rfid.readers'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_enabled');
        });
        if (!config('wk-device-rfid.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.device-rfid.readers_registers_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }

        Schema::create(config('wk-core.table.device-rfid.readers_states'), function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('reader_id');
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->string('mean');
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('reader_id')->references('id')
                  ->on(config('wk-core.table.device-rfid.readers'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_enabled');
        });
        if (!config('wk-device-rfid.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.device-rfid.readers_states_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }


        Schema::create(config('wk-core.table.device-rfid.cards'), function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('reader_id');
            $table->uuid('user_id')->nullable();
            $table->uuid('status_id')->nullable();
            $table->uuid('level_id')->nullable();
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->string('username', 20);
            $table->boolean('is_black')->default(0);
            $table->boolean('is_enabled')->default(0);
            $table->timestamp('begin_at');
            $table->timestamp('end_at');
            $table->json('only_dayType')->nullable();
            $table->json('exclude_date')->nullable();
            $table->json('exclude_time')->nullable();

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('reader_id')->references('id')
                  ->on(config('wk-core.table.device-rfid.readers'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('user_id')->references('id')
                  ->on(config('wk-core.table.user'))
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_black');
            $table->index('is_enabled');
        });
        if (config('wk-device-rfid.onoff.morph-rank')) {
            if (Schema::hasTable(config('wk-core.table.morph-rank.statuses'))) {
                Schema::table(config('wk-core.table.device-rfid.cards'), function (Blueprint $table) {
                    $table->foreign('status_id')->references('id')
                          ->on(config('wk-core.table.morph-rank.statuses'))
                          ->onDelete('set null')
                          ->onUpdate('cascade');
                });
            }
            if (Schema::hasTable(config('wk-core.table.morph-rank.levels'))) {
                Schema::table(config('wk-core.table.device-rfid.cards'), function (Blueprint $table) {
                    $table->foreign('level_id')->references('id')
                          ->on(config('wk-core.table.morph-rank.levels'))
                          ->onDelete('set null')
                          ->onUpdate('cascade');
                });
            }
        }
        if (!config('wk-device-rfid.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.device-rfid.cards_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }


        Schema::create(config('wk-core.table.device-rfid.data'), function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('reader_id');
            $table->uuid('register_id');
            $table->uuid('card_id')->nullable();
            $table->string('identifier');
            $table->string('log');
            $table->timestamp('trigger_at');

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('reader_id')->references('id')
                  ->on(config('wk-core.table.device-rfid.readers'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('register_id')->references('id')
                  ->on(config('wk-core.table.device-rfid.readers_registers'))
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->foreign('card_id')->references('id')
                  ->on(config('wk-core.table.device-rfid.cards'))
                  ->onDelete('no action')
                  ->onUpdate('cascade');

            $table->primary('id');
        });
    }

    public function down() {
        Schema::dropIfExists(config('wk-core.table.device-rfid.data'));

        Schema::dropIfExists(config('wk-core.table.device-rfid.cards_lang'));
        Schema::dropIfExists(config('wk-core.table.device-rfid.cards'));

        Schema::dropIfExists(config('wk-core.table.device-rfid.readers_states_lang'));
        Schema::dropIfExists(config('wk-core.table.device-rfid.readers_states'));
        Schema::dropIfExists(config('wk-core.table.device-rfid.readers_registers_lang'));
        Schema::dropIfExists(config('wk-core.table.device-rfid.readers_registers'));
        Schema::dropIfExists(config('wk-core.table.device-rfid.readers_lang'));
        Schema::dropIfExists(config('wk-core.table.device-rfid.readers'));
    }
}
