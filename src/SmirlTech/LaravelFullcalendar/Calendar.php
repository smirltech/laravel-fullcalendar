<?php namespace SmirlTech\LaravelFullcalendar;

use ArrayAccess;
use DateTime;
use Exception;
use Illuminate\Support\Str;
use Illuminate\View\Factory;
use Illuminate\View\View;

class Calendar
{

    /**
     * @var Factory
     */
    protected Factory $view;

    /**
     * @var EventCollection
     */
    protected EventCollection $eventCollection;

    /**
     * @var string
     */
    protected string $id;

    /**
     * Default options array
     *
     * @var array
     */
    protected array $defaultOptions = [
        'headerToolbar' => [
            'left' => 'prev,next today',
            'center' => 'title',
            'right' => 'listWeek,dayGridMonth',
        ],
        'views'=> [
            'listWeek'=> [
                'buttonText'=> 'Semaine'
            ],
            'dayGridMonth'=>[
                'buttonText'=> 'Mois',
            ]
        ],
        'eventLimit' => true,
    ];

    /**
     * User defined options
     *
     * @var array
     */
    protected array $userOptions = [];

    /**
     * User defined callback options
     *
     * @var array
     */
    protected array $callbacks = [];

    /**
     * @param Factory         $view
     * @param EventCollection $eventCollection
     */
    public function __construct(Factory $view, EventCollection $eventCollection)
    {
        $this->view            = $view;
        $this->eventCollection = $eventCollection;
    }

    /**
     * Create an event DTO to add to a calendar
     *
     * @param string $title
     * @param string $isAllDay
     * @param DateTime|string $start If string, must be valid datetime format: http://bit.ly/1z7QWbg
     * @param DateTime|string $end If string, must be valid datetime format: http://bit.ly/1z7QWbg
     * @param string|null $id event Id
     * @param array $options
     * @return SimpleEvent
     * @throws Exception
     */
    public static function event(string $title, string $isAllDay, DateTime|string $start, DateTime|string $end, string $id = null, array $options = []): SimpleEvent
    {
        return new SimpleEvent($title, $isAllDay, $start, $end, $id, $options);
    }

    /**
     * Create the <div> the calendar will be rendered into
     *
     * @return string
     */
    public function calendar(): string
    {
        return '<div id="calendar-' . $this->getId() . '"></div>';
    }

    /**
     * Get the <script> block to render the calendar (as a View)
     *
     * @return View
     */
    public function script(): View
    {
        $options = $this->getOptionsJson();

        return $this->view->make('fullcalendar::script', [
            'id' => $this->getId(),
            'options' => $options,
        ]);
    }

    /**
     * Get the <script> block to render the calendar (as a View)
     *
     * @return string
     */
    public function scriptToHtml(): string
    {
        return $this->script()->toHtml();
    }


    /**
     * Customize the ID of the generated <div>
     *
     * @param string $id
     * @return $this
     */
    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the ID of the generated <div>
     * This value is randomized unless a custom value was set via setId
     *
     * @return string
     */
    public function getId(): string
    {
        if ( ! empty($this->id)) {
            return $this->id;
        }

        $this->id = Str::random(8);

        return $this->id;
    }

    /**
     * Add an event
     *
     * @param Event $event
     * @param array $customAttributes
     * @return $this
     */
    public function addEvent(Event $event, array $customAttributes = []): static
    {
        $this->eventCollection->push($event, $customAttributes);

        return $this;
    }

    /**
     * Add multiple events
     *
     * @param ArrayAccess|array $events
     * @param array $customAttributes
     * @return $this
     */
    public function addEvents(ArrayAccess|array $events, array $customAttributes = []): static
    {
        foreach ($events as $event) {
            $this->eventCollection->push($event, $customAttributes);
        }

        return $this;
    }

    /**
     * Set fullcalendar options
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): static
    {
        $this->userOptions = $options;

        return $this;
    }

    /**
     * Get the fullcalendar options (not including the events list)
     *
     * @return array
     */
    public function getOptions(): array
    {
        return array_merge($this->defaultOptions, $this->userOptions);
    }

    /**
     * Set fullcalendar callback options
     *
     * @param array $callbacks
     * @return $this
     */
    public function setCallbacks(array $callbacks): static
    {
        $this->callbacks = $callbacks;

        return $this;
    }

    /**
     * Get the callbacks currently defined
     *
     * @return array
     */
    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    /**
     * Get options+events JSON
     *
     * @return string
     */
    public function getOptionsJson(): string
    {
        $options      = $this->getOptions();
        $placeholders = $this->getCallbackPlaceholders();
        $parameters   = array_merge($options, $placeholders);

        // Allow the user to override the events list with a url
        if (!isset($parameters['events'])) {
            $parameters['events'] = $this->eventCollection->toArray();
        }

        $json = json_encode($parameters);

        if ($placeholders) {
            return $this->replaceCallbackPlaceholders($json, $placeholders);
        }

        return $json;

    }

    /**
     * Generate placeholders for callbacks, will be replaced after JSON encoding
     *
     * @return array
     */
    protected function getCallbackPlaceholders(): array
    {
        $callbacks    = $this->getCallbacks();
        $placeholders = [];

        foreach ($callbacks as $name => $callback) {
            $placeholders[$name] = '[' . md5($callback) . ']';
        }

        return $placeholders;
    }

    /**
     * Replace placeholders with non-JSON encoded values
     *
     * @param $json
     * @param $placeholders
     * @return string
     */
    protected function replaceCallbackPlaceholders($json, $placeholders): string
    {
        $search  = [];
        $replace = [];

        foreach ($placeholders as $name => $placeholder) {
            $search[]  = '"' . $placeholder . '"';
            $replace[] = $this->getCallbacks()[$name];
        }

        return str_replace($search, $replace, $json);
    }

}
