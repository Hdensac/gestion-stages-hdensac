<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Structure;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DpafStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si la structure DPAF existe déjà
        $dpafStructure = Structure::where('sigle', 'DPAF')->first();
        
        if (!$dpafStructure) {
            // Créer la structure DPAF
            $dpafStructure = Structure::create([
                'sigle' => 'DPAF',
                'libelle' => 'Direction du Patrimoine et des Affaires Foncières',
                'description' => 'Structure responsable de la gestion des stages',
                'active' => true,
                'type_structure' => 'Direction',
                'niveau' => 0,
            ]);
            
            $this->command->info('Structure DPAF créée avec succès.');
        } else {
            $this->command->info('La structure DPAF existe déjà.');
        }
        
        // Vérifier s'il existe un agent avec le rôle RS
        $rsAgent = Agent::where('role_agent', 'RS')->first();
        
        if (!$rsAgent) {
            // Créer un utilisateur pour l'agent RS
            $user = User::create([
                'nom' => 'Responsable',
                'prenom' => 'DPAF',
                'email' => 'rs.dpaf@example.com',
                'password' => Hash::make('password'),
                'role' => 'agent',
            ]);
            
            // Créer l'agent RS
            $rsAgent = Agent::create([
                'user_id' => $user->id,
                'matricule' => 'RS-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'fonction' => 'Responsable de Structure',
                'role_agent' => 'RS',
                'date_embauche' => now(),
            ]);
            
            $this->command->info('Agent RS créé avec succès.');
        } else {
            $this->command->info('Un agent RS existe déjà.');
        }
        
        // Assigner l'agent RS comme responsable de la structure DPAF
        $dpafStructure->responsable_id = $rsAgent->id;
        $dpafStructure->save();
        
        $this->command->info('Agent RS assigné comme responsable de la structure DPAF.');
    }
}
