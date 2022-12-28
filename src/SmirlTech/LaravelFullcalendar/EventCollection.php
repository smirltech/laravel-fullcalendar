<?php namespace SmirlTech\LaravelFullcalendar;

use Illuminate\Support\Collection;

class EventCollection
{

    /**
     * @var Collection
     */
    protected Collection $events;

    public function __construct()
    {
        $this->events = new Collection();
    }

    public function push(Event $event, array $customAttributes = []): void
    {
        $this->events->push($this->convertToArray($event, $customAttributes));
    }

    public function toJson(): string
    {
        return $this->events->toJson();
    }

    public function toArray(): array
    {
        return $this->events->toArray();
    }

    private function convertToArray(Event $event, array $customAttributes = []): array
    {
        $eventArray = [
            'id' => $this->getEventId($event),
            'title' => $event->getTitle(),
            'allDay' => $event->isAllDay(),
            'start' => $event->getStart()->format('c'),
            'end' => $event->getEnd()->format('c'),
        ];

        $eventOptions = method_exists($event, 'getEventOptions') ? $event->getEventOptions() : [];

        return array_merge($eventArray, $eventOptions, $customAttributes);
    }

    private function getEventId(Event $event): int|string|null
    {
        return $event->getId();
    }
}