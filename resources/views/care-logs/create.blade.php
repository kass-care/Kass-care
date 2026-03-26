<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('care_logs', function (Blueprint $table) {
            $table->boolean('shower')->default(false);
            $table->boolean('grooming')->default(false);
            $table->boolean('dressing')->default(false);
            $table->boolean('medication')->default(false);
            $table->boolean('meal_prepared')->default(false);
            $table->boolean('meal_eaten')->default(false);
            $table->boolean('walk')->default(false);
            $table->boolean('repositioning')->default(false);

            $table->string('bowel_movement')->nullable();
            $table->string('liquid_intake')->nullable();
            $table->string('liquid_output')->nullable();

            $table->string('temperature')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('pulse')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('care_logs', function (Blueprint $table) {
            $table->dropColumn([
                'shower',
                'grooming',
                'dressing',
                'medication',
                'meal_prepared',
                'meal_eaten',
                'walk',
                'repositioning',
                'bowel_movement',
                'liquid_intake',
                'liquid_output',
                'temperature',
                'blood_pressure',
                'pulse',
            ]);
        });
    }
};
<div class="mt-8">
    <h3 class="text-lg font-bold mb-4">ADL Charting</h3>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="shower">
            <span>Shower / Bath</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="grooming">
            <span>Grooming</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="dressing">
            <span>Dressing</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="medication">
            <span>Medication</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="meal_prepared">
            <span>Meal Prepared</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="meal_eaten">
            <span>Meal Eaten</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="walk">
            <span>Walk / Ambulation</span>
        </label>

        <label class="flex items-center space-x-2">
            <input type="checkbox" name="repositioning">
            <span>Repositioning</span>
        </label>

    </div>
</div>

<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">

    <div>
        <label class="block font-semibold mb-1">Bowel Movement</label>
        <select name="bowel_movement" class="w-full border rounded p-2">
            <option value="">Select</option>
            <option>None</option>
            <option>Normal</option>
            <option>Loose</option>
            <option>Constipation</option>
        </select>
    </div>

    <div>
        <label class="block font-semibold mb-1">Liquid Intake</label>
        <input type="text" name="liquid_intake" placeholder="ml" class="w-full border rounded p-2">
    </div>

    <div>
        <label class="block font-semibold mb-1">Liquid Output</label>
        <input type="text" name="liquid_output" placeholder="ml" class="w-full border rounded p-2">
    </div>

</div>
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">

    <div>
        <label class="block font-semibold mb-1">Bowel Movement</label>
        <select name="bowel_movement" class="w-full border rounded p-2">
            <option value="">Select</option>
            <option>None</option>
            <option>Normal</option>
            <option>Loose</option>
            <option>Constipation</option>
        </select>
    </div>

    <div>
        <label class="block font-semibold mb-1">Liquid Intake</label>
        <input type="text" name="liquid_intake" placeholder="ml" class="w-full border rounded p-2">
    </div>

    <div>
        <label class="block font-semibold mb-1">Liquid Output</label>
        <input type="text" name="liquid_output" placeholder="ml" class="w-full border rounded p-2">
    </div>

</div>
