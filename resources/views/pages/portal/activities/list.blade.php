<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Activity;

new
#[Layout("layouts::portal")]
class extends Component
{

    public bool $isDrawerOpened = false;

    public int $perPage = 10;

    public string $keyword = '';

    //filters
    public ?string $selectedCampus = null;
    public ?string $selectedType = null;
    public ?string $title = null;
    public ?string $instructor = null;
    public ?string $execution_from = null;
    public ?string $execution_to = null;
    public ?string $attribute = null;
    public bool $vacancy = false;


    //options
    public array $activity_type_options = [
            'All Activity Type',
            'Campus Representatives',
            'Career Development Activities',
            'Extra-curricular Activities',
            'Language Activities',
            'Other Achievements',
            'Personal Development Activities',
            'Physical Education & Sports',
            'Professional Qualifications',
            'Student Groups',
            'Student Organizations',
            'Volunteer Services',
        ];

    public array $attribute_options = [
            'All Atteibute',
            'Effective Communicators (EC)', 
            'Independent Learners (IDL)', 
            'Informed and Professionally Competent (IPC)', 
            'No need to classify', 'Positive and Flexible (PF)', 
            'Problem-solvers (PS)', 
            'Professional, Socially and Globally Responsible (PSG)'
        ];
    
    public array $campus_options = ['Not Specified'];


    public function filters()
    {
    }

    public function resetFilters()
    {
    }

    public function activities()
    {
        return Activity::query()
        ->paginate($this->perPage);

    }


    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title','class' => 'w-auto min-w-64'],
            ['key' => 'activity_code', 'label' => 'Code','class' => 'w-fit'],
            ['key' => 'total_amount', 'label' => 'total amount','class' => 'w-fit'],
            ['key' => 'included_deposit', 'label' => 'included deposit','class' => 'w-fit'],
            ['key' => 'has_vacancy', 'label' => 'Vacancy', 'class' => 'w-fit', 'format' => fn($activities, $has_vacancy) => $has_vacancy ? 'Yes' : 'No', 'sortable' => false],
        ];
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'activities' => $this->activities(),
            'activity_type_options' => $this->activity_type_options,
            'attribute_options' => $this->attribute_options,
            'campus_options' => $this->campus_options
        ];
    }

}; ?>

<div>

    <x-drawer
    wire:model="isDrawerOpened"
    title="Filter" 
    separator
    with-close-button
    close-on-escape
    class="w-11/12 lg:w-1/3" right>

    <div>
        <x-form>
                
        <x-select
        label="Campus"
        wire:model="selectedCampus"
        :options="$campus_options" /></br>

        <x-input label="Activity Code" wire:model="activity_Code" placeholder="Enter code" clearable /></br>

        <x-select
        label="Activity Type"
        wire:model="selectedType"
        :options="$activity_type_options"/></br>

        <x-input label="Activity Title" wire:model="title" placeholder="Enter Title" clearable /></br>


        <x-input label="instructor" wire:model="instructor" placeholder="Instructor" clearable /></br>

        Execution Period 
        <x-datetime label="From" wire:model="execution_from" />
        <x-datetime label="To" wire:model="execution_to" /> </br>

        <x-select
        label="Attribute"
        wire:model="attribute"
        :options="$attribute_options" /></br>
        
        <x-checkbox label="Activity with Vacancies only" wire:model="vacancy" right /></br>

        
        
    </div>
 
    <x-slot:actions>
        <x-button label="Reset" icon="fal.xmark" wire:click="clear" spinner />
        <x-button label="Confirm" class="btn-primary" type="submit" icon="o-check" />
    </x-slot:actions>

    </x-form>

    </x-drawer>

    <x-header :title="__('Activities')" separator>
 
         <x-slot:middle class="!justify-end max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keyword" type="search" :placeholder="__('Search...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
        </x-slot:actions>

    </x-header>


    <x-card shadow>
        <x-table :headers="$headers" :rows="$activities" >
            @scope('actions', $activity)
                <div class="hidden lg:inline-flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.file-lines" :tooltip="__('Activity Details')" :link="route('portal.activities.show', ['id' => $activity->id ])" class="btn-ghost btn-square btn-sm" />
                </div>        

                    <x-dropdown right>
                        <x-slot:trigger>
                            <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                        </x-slot:trigger>

                        <x-menu-item title="Activity Details" icon="fal.file-lines" :link="route('portal.activities.show', ['id' => $activity->id ])" />
                    </x-dropdown>
            @endscope
        </x-table>
    </x-card>
</div>
