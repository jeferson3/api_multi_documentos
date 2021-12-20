<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name', 50)->comment('Nome do documento');
            $table->string('type', 50)->comment('Tipo do documento');
            $table->string('id_number', 100)->comment('Número de identificação do documento');
            $table->date('issue_date')->comment('Data de emissão do documento');
            $table->string('issuing_body', 50)->comment('Órgão emissor do documento')->nullable();
            $table->string('country_issuing', 100)->comment('País onde o documento foi emitido')->nullable();

            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
