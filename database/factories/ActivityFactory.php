<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     public function definition(): array
    {
        $capacity   = $this->faker->numberBetween(20, 100);
        $registered = $this->faker->numberBetween(0, $capacity);

        $from = $this->faker->dateTimeBetween('now', '+1 month');
        $to   = $this->faker->dateTimeBetween($from, '+2 months');

        $slotFrom = $this->faker->dateTimeBetween($from, $to);
        $slotTo   = (clone $slotFrom)->modify('+1 hour');

        $totalAmount     = $this->faker->randomFloat(2, 100, 1000);
        $includedDeposit = $this->faker->randomFloat(2, 0, $totalAmount);

        return [
            'activity_code'     => strtoupper($this->faker->numerify('ACT####')),
            'activity_type'     => $this->faker->randomElement([
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
                'Volunteer Services'
            ]),
            'campus_id'         => $this->faker->numberBetween(1, 2),
            'instructor'        => $this->faker->name(),
            'responsible_staff' => $this->faker->name(),
            'execution_from'    => $from->format('Y-m-d'),
            'execution_to'      => $to->format('Y-m-d'),
            'time_slot_from'    => $slotFrom->format('Y-m-d H:i'),
            'time_slot_to'      => $slotTo->format('Y-m-d H:i'),
            'duration_hours'    => $this->faker->randomFloat(2, 0.5, 8),
            'swpd_programme'    => $this->faker->boolean(),
            'venue'             => $this->faker->randomElement(['Hall A', 'Room 101', 'Gym', 'Not specified']),
            'venue_remark'      => $this->faker->sentence(),
            'capacity'          => $capacity,
            'registered'        => $registered,
            'total_amount'      => $totalAmount,
            'included_deposit'  => $includedDeposit,
            'attachment'        => $this->faker->optional()->word() . '.pdf',
            // Translatable fields
            'title:en'          => $this->faker->sentence(3),
            'title:zh'          => $this->faker->sentence(3),
            'description:en'    => $this->faker->paragraph(),
            'description:zh'    => $this->faker->paragraph(),
            'discipline:en'     => $this->faker->randomElement(['IT', 'Business', 'Engineering', 'Arts']),
            'discipline:zh'     => $this->faker->randomElement(['資訊技術', '商業', '工程', '藝術']),
            'attribute:en'      => $this->faker->randomElement([
                'Effective Communicators (EC)',
                'Independent Learners (IDL)',
                'Informed and Professionally Competent (IPC)',
                'No need to classify',
                'Positive and Flexible (PF)',
                'Problem-solvers (PS)',
                'Professional, Socially and Globally Responsible (PSG)'
            ]),
            'attribute:zh'      => $this->faker->randomElement([
                '有效溝通者 (EC)',
                '獨立學習者 (IDL)',
                '知識淵博和專業稱職 (IPC)',
                '無需分類',
                '積極靈活 (PF)',
                '問題解決者 (PS)',
                '專業、社會和全球責任感 (PSG)'
            ]),
        ];
    }

}
