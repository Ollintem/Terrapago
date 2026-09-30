```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {

            // El folio individual ya no será único.
            // Una operación puede generar varios registros de pago.
            $table->dropUnique('pagos_folio_unique');

            // Folio que identifica toda la operación de cobranza.
            $table->string('operacion_folio', 30)
                ->nullable()
                ->after('usuario_id');

            $table->unique('operacion_folio');
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {

            $table->dropUnique('pagos_operacion_folio_unique');

            $table->dropColumn('operacion_folio');

            $table->unique('folio');
        });
    }
};
