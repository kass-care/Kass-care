public function up()
{
    Schema::create('audit_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->unsignedBigInteger('facility_id')->nullable();
        $table->string('action');
        $table->string('model')->nullable();
        $table->unsignedBigInteger('model_id')->nullable();
        $table->text('details')->nullable();
        $table->timestamps();
    });
}
