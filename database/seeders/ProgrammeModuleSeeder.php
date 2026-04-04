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
        $softwareEngineeringProgramme = \App\Models\Programme::where('programme_code', 'IT114105')->first();
        $illustrationDesignProgramme = \App\Models\Programme::where('programme_code', 'DE114112')->first();

        $informationTechnologyInstitute = \App\Models\Institute::whereTranslation('name', 'Hong Kong Institute of Information Technology', 'en')->first();
        $designInstitute = \App\Models\Institute::whereTranslation('name', 'Hong Kong Design Institute', 'en')->first();

        // HKIIT
        $artificialIntelligenceAndMachineLearningModule = \App\Models\Module::where('module_code', 'ITP4514')->where('institute_id', $informationTechnologyInstitute?->id)->first();
        $contemporaryTopicsInSoftwareEngineeringModule  = \App\Models\Module::where('module_code', 'ITP4507')->where('institute_id', $informationTechnologyInstitute?->id)->first();
        $humanComputerInteractionAndGuiProgrammingModule = \App\Models\Module::where('module_code', 'ITP4506')->where('institute_id', $informationTechnologyInstitute?->id)->first();
        $itProfessionalismModule = \App\Models\Module::where('module_code', 'ITE4103')->where('institute_id', $informationTechnologyInstitute?->id)->first();
        $lan4102InformationTechnologyModule  = \App\Models\Module::where('module_code', 'LAN4102')->where('institute_id', $informationTechnologyInstitute?->id)->first();
        $lan3003InformationTechnologyModule  = \App\Models\Module::where('module_code', 'LAN3003')->where('institute_id', $informationTechnologyInstitute?->id)->first();

        // DI
        $lan4102DesignInstituteModule = \App\Models\Module::where('module_code', 'LAN4102')->where('institute_id', $designInstitute?->id)->first();
        $lan3003DesignInstituteModule = \App\Models\Module::where('module_code', 'LAN3003')->where('institute_id', $designInstitute?->id)->first();


        $softwareEngineeringProgramme->modules()->sync(array_filter([
            $artificialIntelligenceAndMachineLearningModule?->id,
            $contemporaryTopicsInSoftwareEngineeringModule?->id,
            $humanComputerInteractionAndGuiProgrammingModule?->id,
            $itProfessionalismModule?->id,
            $lan4102InformationTechnologyModule?->id,
            $lan3003InformationTechnologyModule?->id,
        ]));

        $illustrationDesignProgramme->modules()->sync(array_filter([
            $lan4102DesignInstituteModule?->id,
            $lan3003DesignInstituteModule?->id,
        ]));
    }
}
