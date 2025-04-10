<?php

namespace Database\Seeders;

use App\Models\Rubro;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
      // si quiero crear un admin ya por defecto 
       \App\Models\User::factory(1)->create([

            'name' => 'KarolM',
            'email' => 'karol1770@hotmail.com',
            'password' => bcrypt('karol*001'),
            'role' => 'admin',




        ]);
        \App\Models\User::factory(1)->create([
            'name' => 'SantiagoG',
            'email' => 'santiagog@webcoopec.com',
            'password' => bcrypt('santigog*001'),
            'role' => 'admin',

        ]);


        $this->call([
            DepartamentoSeeder::class,
            CargosSeeder::class,
            RubroSeeder::class,
            ParametrosSeeder::class,
            
        ]);



        


     

    }
}
