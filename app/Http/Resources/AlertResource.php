<?php

namespace App\Http\Resources;

use App\Models\Alert;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @mixin Alert
 */
class AlertResource extends JsonResource
{
    /**
     * @var array<string, int>
     */
    protected static array $reservationIdsByNumber = [];

    /**
     * @param  Collection<int, Alert>|iterable<int, Alert>  $alerts
     */
    public static function prepareReservationIds(iterable $alerts): void
    {
        static::$reservationIdsByNumber = [];

        $numbers = collect($alerts)
            ->filter(fn (Alert $alert): bool => $alert->alertType?->slug === 'reservation_follow_up')
            ->filter(fn (Alert $alert): bool => ! $alert->reservation_id)
            ->map(fn (Alert $alert): ?string => self::reservationNumberFromTitle($alert->title))
            ->filter()
            ->unique()
            ->values();

        if ($numbers->isEmpty()) {
            return;
        }

        static::$reservationIdsByNumber = Reservation::query()
            ->whereIn('reservation_number', $numbers)
            ->pluck('id', 'reservation_number')
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle' => $this->whenLoaded('vehicle', fn (): ?array => $this->vehicle ? [
                'id' => $this->vehicle->id,
                'name' => $this->vehicle->name,
                'slug' => $this->vehicle->slug,
                'plate_number' => $this->vehicle->plate_number,
            ] : null),
            'reservation_id' => $this->resolvedReservationId(),
            'reservation' => $this->whenLoaded('reservation', fn (): ?array => $this->reservation ? [
                'id' => $this->reservation->id,
                'reservation_number' => $this->reservation->reservation_number,
            ] : null),
            'alert_type' => $this->whenLoaded('alertType', fn (): array => [
                'id' => $this->alertType->id,
                'name' => $this->alertType->name,
                'slug' => $this->alertType->slug,
            ]),
            'alert_status' => $this->whenLoaded('alertStatus', fn (): array => [
                'id' => $this->alertStatus->id,
                'name' => $this->alertStatus->name,
                'slug' => $this->alertStatus->slug,
            ]),
            'title' => $this->title,
            'message' => $this->message,
            'due_date' => $this->due_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function resolvedReservationId(): ?int
    {
        if ($this->reservation_id) {
            return (int) $this->reservation_id;
        }

        if ($this->relationLoaded('reservation') && $this->reservation) {
            return $this->reservation->id;
        }

        if ($this->alertType?->slug !== 'reservation_follow_up') {
            return null;
        }

        $reservationNumber = self::reservationNumberFromTitle($this->title);

        if ($reservationNumber === null) {
            return null;
        }

        $resolvedId = static::$reservationIdsByNumber[$reservationNumber] ?? null;

        if ($resolvedId !== null) {
            return (int) $resolvedId;
        }

        return Reservation::query()
            ->where('reservation_number', $reservationNumber)
            ->value('id');
    }

    private static function reservationNumberFromTitle(?string $title): ?string
    {
        if ($title === null || ! str_starts_with($title, 'New reservation ')) {
            return null;
        }

        $number = trim(substr($title, strlen('New reservation ')));

        return $number !== '' ? $number : null;
    }
}
