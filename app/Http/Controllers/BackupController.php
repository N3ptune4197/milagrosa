<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackupController extends Controller
{

    public function exportarBaseDeDatos()
    {
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPassword = env('DB_PASSWORD', '');
        $dbName = env('DB_DATABASE', 'tu_base_de_datos');
    
        $mysqldumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
        $fileName = "backup_" . date('Y_m_d_H_i_s') . ".sql";
        $backupFile = storage_path("app/{$fileName}");
    
        // Tablas a excluir
        $excludedTables = [
            'cache',
            'cache_locks',
            'failed_jobs',
            'jobs',
            'jobs_batches',
            'model_has_permissions',
            'model_has_roles',
            'password_reset_tokens',
            'permissions',
            'roles',
            'role_has_permissions',
            'users',
        ];
    
        // Crear una lista de exclusión para mysqldump
        $ignoreTables = '';
        foreach ($excludedTables as $table) {
            $ignoreTables .= " --ignore-table={$dbName}.{$table}";
        }
    
        // Construir el comando mysqldump con exclusión de tablas
        $command = "\"{$mysqldumpPath}\" -h {$dbHost} -u {$dbUser} --password={$dbPassword} {$dbName} {$ignoreTables} > \"{$backupFile}\"";
    
        // Ejecutar el comando
        $result = null;
        system($command, $result);
    
        if ($result === 0) {
            // Descargar el archivo generado
            return response()->download($backupFile)->deleteFileAfterSend(true);
        } else {
            return back()->withErrors(['error' => 'Error al exportar la base de datos.']);
        }
    }
    








    /* public function exportarBaseDeDatos()
{
    $dbHost = env('DB_HOST', '127.0.0.1');
    $dbUser = env('DB_USERNAME', 'root');
    $dbPassword = env('DB_PASSWORD', '');
    $dbName = env('DB_DATABASE', 'tu_base_de_datos');

    // Ruta al ejecutable mysqldump (cambia esta ruta si tu XAMPP está en otra ubicación)
    $mysqldumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

    // Nombre del archivo de respaldo
    $fileName = "backup_" . date('Y_m_d_H_i_s') . ".sql";

    // Ruta para almacenar temporalmente el archivo
    $backupFile = storage_path("app/{$fileName}");

    // Construir el comando mysqldump
    $command = "\"{$mysqldumpPath}\" -h {$dbHost} -u {$dbUser} --password={$dbPassword} {$dbName} > \"{$backupFile}\"";

    // Ejecutar el comando
    $result = null;
    system($command, $result);

    if ($result === 0) {
        // Descargar el archivo generado
        return response()->download($backupFile)->deleteFileAfterSend(true);
    } else {
        return back()->withErrors(['error' => 'Error al exportar la base de datos.']);
    }
} */







    public function importarBaseDeDatos(Request $request)
    {
        $file = $request->file('archivo_mysql');

        if (!$file) {
            return back()->withErrors(['archivo_mysql' => 'Por favor, seleccione un archivo para importar.']);
        }

        $dbHost = env('DB_HOST');
        $dbUser = env('DB_USERNAME');
        $dbPassword = env('DB_PASSWORD');
        $dbName = env('DB_DATABASE');
        $backupFile = $file->getRealPath();
        $mysqlPath = 'C:\\xampp\\mysql\\bin\\mysql.exe'; // Ruta completa a mysql.exe

        // Comando mysql para importar
        $command = "\"{$mysqlPath}\" -h {$dbHost} -u {$dbUser} --password=\"{$dbPassword}\" {$dbName} < \"{$backupFile}\"";

        // Ejecutar el comando
        exec($command, $output, $result);

        if ($result === 0) {
            return back()->with('success', 'Base de datos restaurada con éxito.');
        } else {
            return back()->withErrors(['error' => 'Error al importar la base de datos.']);
        }
    }


}
