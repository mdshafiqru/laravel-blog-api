<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneNumber2ToUsersTable extends Migration
{

    // command for creating migration file to add new column into existing table
    //php artisan make:migration add_type_to_unique_ids_table

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone2')->after('phone')->nullable();
            $table->string('address')->after('phone2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone2', 'address']);
        });
    }
}
