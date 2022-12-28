<?php namespace SmirlTech\LaravelFullcalendar;

use DateTime;

/**
 * Class SimpleEvent
 *
 * Simple DTO that implements the Event interface
 *
 * @package SmirlTech\LaravelFullcalendar
 */
class SimpleEvent implements IdentifiableEvent
{
    /**
     * @param string $title
     * @param bool $isAllDay
     * @param DateTime|string $start If string, must be valid datetime format: http://bit.ly/1z7QWbg
     * @param DateTime|string $end If string, must be valid datetime format: http://bit.ly/1z7QWbg
     * @param int|string|null $id
     * @param array $options
     * @throws \Exception
     */
    public function __construct(public string $title, public  bool $isAllDay, public DateTime|string $start, public DateTime|string $end, public  null|int|string $id = null, public  array $options = [])
    {
        $this->start    = $start instanceof DateTime ? $start : new DateTime($start);
        $this->end      = $start instanceof DateTime ? $end : new DateTime($end);
    }

    /**
     * Get the event's id number
     *
     * @return int|string|null
     */
    public function getId(): int|string|null
    {
        return $this->id;
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay(): bool
    {
        return $this->isAllDay;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart(): DateTime
    {
        return $this->start;
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd(): DateTime
    {
        return $this->end;
    }

    /**
     * Get the optional event options
     *
     * @return array
     */
    public function getEventOptions(): array
    {
        return $this->options;
    }
}