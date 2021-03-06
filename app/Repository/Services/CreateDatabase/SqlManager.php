<?php


namespace App\Repository\Services\CreateDatabase;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Symfony\Component\Process\Process;
use function response;

class SqlManager implements DatabaseRepositoryInterface {

    public function databaseExists($database_name): bool
    {
        $process = new Process(['/home/exedir/mysql_tasks/db_exists.sh',$database_name]);
        $process->run();
        $commandLine = $process->getCommandLine();
        $isSuccessful = $process->isSuccessful();
        $error = $process->getErrorOutput();

        return boolval($process->getOutput());
    }

    public function userExists($user_name): bool
    {
        $process = new Process(['/home/exedir/mysql_tasks/user_exists.sh',$user_name]);
        $process->run();
        $commandLine = $process->getCommandLine();
        $isSuccessful = $process->isSuccessful();
        $error = $process->getErrorOutput();

        return boolval($process->getOutput());
    }

    public function create($db_name,$user_name,$password): Response|Application|ResponseFactory
    {
        $process = new Process(['/home/exedir/mysql_tasks/create.sh',$db_name,$user_name,$password]);
        $process->run();
        return response([
            'command'=>$process->getCommandLine(),
            'isSuccessful'=>$process->isSuccessful(),
            'error'=>$process->getErrorOutput()
        ],201);
    }
}
