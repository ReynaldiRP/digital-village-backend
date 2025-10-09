<?php

namespace App\Repositories;

use App\Interfaces\EventParticipantRepositoryInterface;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EventParticipantRepository implements EventParticipantRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ): Collection {
        try {
            $query = EventParticipant::where(function ($query) use ($search) {
                if ($search) {
                    $query->search($search);
                }
            });

            if ($limit) {
                $query->take($limit);
            }

            if ($execute) {
                return $query->get();
            }

            return $query;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getAllPaginated(
        ?string $search,
        int $rowPerPage
    ): LengthAwarePaginator {
        try {
            $query = $this->getAll(
                $search,
                $rowPerPage,
                false
            );

            return $query->paginate($rowPerPage);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getById(
        string $id
    ): ?EventParticipant {
        $query = EventParticipant::where('id', $id);

        return $query->first();
    }

    public function create(
        array $data
    ): EventParticipant {
        DB::beginTransaction();

        try {
            $event = Event::where('id', $data['event_id'])->first();

            $eventParticipant = new EventParticipant();

            $eventParticipant->event_id = $data['event_id'];
            $eventParticipant->head_of_family_id = $data['head_of_family_id'];
            $eventParticipant->quantity = $data['quantity'];
            $eventParticipant->total_price = $event->price * $data['quantity'];
            $eventParticipant->payment_status = $data['payment_status'];

            $eventParticipant->save();
            DB::commit();

            return $eventParticipant;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function update(
        string $id,
        array $data
    ): ?EventParticipant {
        DB::beginTransaction();

        try {
            $event = Event::where('id', $data['event_id'])->first();

            $eventParticipant = EventParticipant::find($id);

            $eventParticipant->event_id = $data['event_id'] ?? $eventParticipant->event_id;
            $eventParticipant->head_of_family_id = $data['head_of_family_id'] ?? $eventParticipant->head_of_family_id;
            $eventParticipant->quantity = $data['quantity'] ?? $eventParticipant->quantity;
            $eventParticipant->total_price = $event->price *  $data['quantity']   ?? $eventParticipant->total_price;
            $eventParticipant->payment_status = $data['payment_status'] ?? $eventParticipant->payment_status;

            $eventParticipant->save();
            DB::commit();

            return $eventParticipant;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(
        string $id
    ): bool {
        DB::beginTransaction();

        try {
            $eventParticipant = EventParticipant::find($id);

            $eventParticipant->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
