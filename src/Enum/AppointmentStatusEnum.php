<?php

/**
 * Created by Muzzapp.
 * User: Filipe Batista
 * Date: 04/07/25 15:26
 */

namespace App\Enum;

enum AppointmentStatusEnum: string
{
    case SCHEDULED = 'scheduled';
    case CONFIRMED = 'confirmed';
    case CHECKED_IN = 'checked_in';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
    case NO_SHOW = 'no_show';
    case RESCHEDULED = 'rescheduled';
    case PENDING = 'pending';
    case WAITING_LIST = 'waiting_list';

    public function getDisplayName(): string
    {
        return match($this) {
            self::SCHEDULED => 'Scheduled',
            self::CONFIRMED => 'Confirmed',
            self::CHECKED_IN => 'Checked In',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::CANCELED => 'Canceled',
            self::NO_SHOW => 'No Show',
            self::RESCHEDULED => 'Rescheduled',
            self::PENDING => 'Pending Confirmation',
            self::WAITING_LIST => 'Waiting List',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::SCHEDULED => 'blue',
            self::CONFIRMED => 'green',
            self::CHECKED_IN => 'purple',
            self::IN_PROGRESS => 'orange',
            self::COMPLETED => 'gray',
            self::CANCELED => 'red',
            self::NO_SHOW => 'red',
            self::RESCHEDULED => 'yellow',
            self::PENDING => 'amber',
            self::WAITING_LIST => 'indigo',
        };
    }

    public function isActive(): bool
    {
        return match($this) {
            self::SCHEDULED,
            self::CONFIRMED,
            self::CHECKED_IN,
            self::IN_PROGRESS => true,
            default => false,
        };
    }

    public function isCompleted(): bool
    {
        return $this === self::COMPLETED;
    }

    public function isCanceled(): bool
    {
        return in_array($this, [self::CANCELED, self::NO_SHOW]);
    }

    public function canBeRescheduled(): bool
    {
        return !in_array($this, [self::COMPLETED, self::NO_SHOW]);
    }

    public function canBeCanceled(): bool
    {
        return in_array($this, [
            self::SCHEDULED,
            self::CONFIRMED,
            self::PENDING,
            self::WAITING_LIST
        ]);
    }

    public static function getActiveStatuses(): array
    {
        return [
            self::SCHEDULED,
            self::CONFIRMED,
            self::CHECKED_IN,
            self::IN_PROGRESS,
        ];
    }

    public static function getCompletedStatuses(): array
    {
        return [
            self::COMPLETED,
            self::CANCELED,
            self::NO_SHOW,
        ];
    }
}
