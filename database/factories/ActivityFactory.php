<?php

namespace Database\Factories;

use App\Enums\NewsArticleStatus;
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

        $slotFromDate = $this->faker->dateTimeBetween($from, $to);
        $slotToDate   = (clone $slotFromDate)->modify('+' . $this->faker->numberBetween(0, 5) . ' days');

        $slotFromTime = $this->faker->time('H:i');
        $slotToTime   = $this->faker->time('H:i');

        $totalAmount     = $this->faker->randomFloat(2, 100, 1000);
        $includedDeposit = $this->faker->randomFloat(2, 0, $totalAmount);

        return [
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
            'activity_code'     => $this->faker->unique()->bothify('ACT-###??'),
            'campus_id'         => $this->faker->numberBetween(1, 2),
            'instructor'        => $this->faker->randomElement(['Tom', 'Sarah', 'Michael', 'Emma']),
            'responsible_staff' => $this->faker->randomElement(['Joe', 'Maria', 'Robert', 'Anna']),
            'execution_from'    => $from->format('Y-m-d'),
            'execution_to'      => $to->format('Y-m-d'),
            'time_slot_from_date' => $slotFromDate->format('Y-m-d'),
            'time_slot_from_time' => $slotFromTime,
            'time_slot_to_date'   => $slotToDate->format('Y-m-d'),
            'time_slot_to_time'   => $slotToTime,
            'duration_hours'    => $this->faker->randomFloat(2, 0.5, 8),
            'swpd_programme'    => $this->faker->boolean(),
            'venue'             => $this->faker->randomElement(['Hall A', 'Room 101', 'Gym', 'Not specified']),
            'capacity'          => $capacity,
            'registered'        => $registered,
            'total_amount'      => $totalAmount,
            'included_deposit'  => $includedDeposit,
            'attachment'        => $this->faker->optional()->word() . '.pdf',
            // Non-translatable enum fields
            'discipline'        => $this->faker->randomElement(['IT', 'Business', 'Engineering', 'Arts']),
            'attribute'         => $this->faker->randomElement([
                'Effective Communicators (EC)',
                'Independent Learners (IDL)',
                'Informed and Professionally Competent (IPC)',
                'No need to classify',
                'Positive and Flexible (PF)',
                'Problem-solvers (PS)',
                'Professional, Socially and Globally Responsible (PSG)'
            ]),
            'status' => NewsArticleStatus::Published,
            // Translatable fields
            'title:en'          => $this->faker->sentence(3),
            'title:zh'          => $this->faker->sentence(3),
            'description:en'    => $this->faker->paragraph(),
            'description:zh'    => $this->faker->paragraph(),
            'venue_remark:en'   => $this->faker->sentence(),
            'venue_remark:zh'   => $this->faker->sentence(),
        ];
    }

}
