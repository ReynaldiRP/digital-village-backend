<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\HeadOfFamily;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $headOfFamilies = HeadOfFamily::with('familyMembers')->get();
        $activeEvents = Event::where('is_active', true)
            ->where('date', '>=', now()->format('Y-m-d'))
            ->get();

        // 50-70% of families participate in events
        foreach ($headOfFamilies as $family) {
            if (fake()->boolean(60)) {
                // Each family participates in 1-3 events
                $eventsCount = fake()->numberBetween(1, min(3, $activeEvents->count()));
                $selectedEvents = $activeEvents->random($eventsCount);

                foreach ($selectedEvents as $event) {
                    // Avoid duplicates
                    if (EventParticipant::where('head_of_family_id', $family->id)
                        ->where('event_id', $event->id)
                        ->exists()
                    ) {
                        continue;
                    }

                    // Calculate realistic participants (head + some family members)
                    $totalFamilyMembers = $family->familyMembers->count();
                    $participantsCount = fake()->numberBetween(1, max(1, $totalFamilyMembers + 1));

                    // Actual participants should not exceed total family (1 head + members)
                    $participantsCount = min($participantsCount, $totalFamilyMembers + 1);

                    $totalPrice = $event->price * $participantsCount;

                    EventParticipant::create([
                        'event_id' => $event->id,
                        'head_of_family_id' => $family->id,
                        'quantity' => $participantsCount,
                        'total_price' => $totalPrice,
                        'payment_status' => $this->getPaymentStatus($event->price),
                    ]);
                }
            }
        }
    }

    private function getPaymentStatus(float $price): string
    {
        // Free events are always completed
        if ($price == 0) {
            return 'selesai';
        }

        // Paid events: 70% completed, 20% pending, 10% failed
        return fake()->randomElement([
            'selesai',
            'selesai',
            'selesai',
            'selesai',
            'selesai',
            'selesai',
            'selesai',
            'menunggu',
            'menunggu',
            'gagal'
        ]);
    }
}
