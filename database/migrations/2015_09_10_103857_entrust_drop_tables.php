<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EntrustDropTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists(Config::get('entrust.permission_role_table', 'permission_role'));
        Schema::dropIfExists(Config::get('entrust.permissions_table', 'permissions'));
        Schema::dropIfExists(Config::get('entrust.role_user_table', 'role_user'));
        Schema::dropIfExists(Config::get('entrust.roles_table', 'roles'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Create table for storing roles
        Schema::create(Config::get('entrust.roles_table', 'roles'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 160)->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create(Config::get('entrust.role_user_table', 'role_user'), function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create(Config::get('entrust.permissions_table', 'permissions'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 160)->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create(Config::get('entrust.permission_role_table', 'permission_role'), function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }
}
