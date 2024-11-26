<?php

use Illuminate\Support\Facades\DB;

/**
 * Aca hace el rollback automatico si un registro no pudo guardarse correctamente
 * @param Closure $callback
 * @return mixed
 */
function transactional(Closure $callback)
{
    // inicia la transaccion
    DB::beginTransaction();
    try {
        // Se ejecuta el callback $callback(), que contiene la lógica o el conjunto de operaciones a realizar en la base de datos.
        $result = $callback();
        // Si el código se ejecuta sin errores, se confirma la transacción con DB::commit();, guardando todos los cambios realizados.
        DB::commit();
        return $result;
    } catch (\Exception $e) {
        DB::rollBack();
        dd($e);

    }
}
