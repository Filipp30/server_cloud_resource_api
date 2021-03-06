<?php

use App\ExternalServices\Contracts\GeoLocationContract;
use App\Http\Controllers\Storage\StorageController;
use App\Http\Controllers\VpsServer\ServerTaskController;
use App\Jobs\HandleIpJob;
use Illuminate\Support\Facades\Route;

require_once "auth-api.php";

//Request from Nginx: Save Ip address from making request.
    Route::get('/ip/run/{ip}', function ($ip) {
        HandleIpJob::dispatch($ip);
        return response(['ip' => $ip], 200);
    });


//SFTP: Storage Routes # SFTP server created on the VPS server.
    Route::post('/save/file', [StorageController::class, 'put']);

    Route::get('/exists/{dir}/{file_name}', function ($dir, $file_name, StorageController $storage) {
        return $storage->exists($dir, $file_name);
    });
    Route::get('/download/{dir}/{file_name}', function ($dir, $file_name, StorageController $storage) {
        return $storage->download($dir, $file_name);
    });
    Route::delete('/delete/{dir}/{file_name}', function ($dir, $file_name, StorageController $storage) {
        return $storage->delete($dir, $file_name);
    });


//VPS-server: Run bash script #home/exedir/run.sh  on the server.
    Route::get('/run/task/rm/log', [ServerTaskController::class, 'rm_log']);

    Route::get('/run/task/ping/{ip}', function ($ip, ServerTaskController $serverTaskController) {
        return $serverTaskController->ping_ip($ip);
    });

### Task: database handler ###
    Route::post('/run/task/create/database', [ServerTaskController::class, 'create_database']);


