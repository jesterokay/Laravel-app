<?php

namespace Modules\Media\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTableTemp extends Migration
{
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('telegram_file_id');
            $table->string('telegram_file_path')->nullable();
            $table->bigInteger('telegram_message_id')->nullable();
            $table->enum('media_type', ['image', 'video', 'document']);
            $table->timestamps();
            
            $table->index('media_type');
            $table->index('created_at');
            $table->index('telegram_file_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
    }
}
