<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgrammeModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Software_Engineering = \App\Models\Programme::where('programme_code', 'IT114105')->first();
        $Telecommunications_and_Networking = \App\Models\Programme::where('programme_code', 'IT114103')->first();

        $Artificial_Intelligence_and_Machine_Learning = \App\Models\Module::where('module_code', 'ITP4514')->first();
        $Contemporary_Topics_in_Software_Engineering = \App\Models\Module::where('module_code', 'ITP4507')->first();
        $Human_Computer_Interaction_and_GUI_Programming = \App\Models\Module::where('module_code', 'ITP4506')->first();
        $IT_Professionalism = \App\Models\Module::where('module_code', 'ITE4103')->first();
        $Professional_Workplace_Communication_Storytelling_and_Job_Search = \App\Models\Module::where('module_code', 'ITP4514')->first();
        $Vocational_Chinese_Communication_Putonghua_Conversation_and_Reports = \App\Models\Module::where('module_code', 'ITP4514')->first();

        $Software_Engineering->modules()->sync(
            [
                $Artificial_Intelligence_and_Machine_Learning->id,
                $Contemporary_Topics_in_Software_Engineering->id,
                $Human_Computer_Interaction_and_GUI_Programming->id,
                $IT_Professionalism->id,
                $Professional_Workplace_Communication_Storytelling_and_Job_Search->id,
                $Vocational_Chinese_Communication_Putonghua_Conversation_and_Reports->id,
            ]
        );
        $Telecommunications_and_Networking->modules()->sync(
            [
                $IT_Professionalism->id,
                $Professional_Workplace_Communication_Storytelling_and_Job_Search->id,
                $Vocational_Chinese_Communication_Putonghua_Conversation_and_Reports->id,
            ]
        );
    }
}
