<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use PDO;

class createDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria o banco de dados definido no .env se ele ainda não existir';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = config('database.connections.mysql.database');
        $host = config('database.connections.mysql.host');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
    
        try{
            $pdo = new PDO("mysql:host=$host", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("ALTER DATABASE `$database` DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci");

            $this->info("Banco de dados: $database criado com sucesso!");
        } catch(Exception $error){
            $this->error("Error ao criar o banco: ".$error->getMessage());
        }
    }
}
