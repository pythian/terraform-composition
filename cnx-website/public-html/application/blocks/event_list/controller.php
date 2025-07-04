<?php
namespace Application\Block\EventList;

use Concrete\Core\Attribute\Key\CollectionKey;
use Concrete\Core\Feature\Features;
use Concrete\Core\Feature\UsesFeatureInterface;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Utility\Service\Validation\Numbers;
use Concrete\Core\Attribute\Key\EventKey;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Calendar\Calendar;
use Concrete\Core\Calendar\CalendarServiceProvider;
use Concrete\Core\Calendar\Event\EventOccurrenceList;
use Core;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController implements UsesFeatureInterface
{
    /**
     * @var string|null
     */
    public $caID;

    /**
     * @var string|null
     */
    public $calendarAttributeKeyHandle;

    /**
     * @var int|string|null
     */
    public $totalToRetrieve;

    /**
     * @var int|string|null
     */
    public $totalPerPage;

    /**
     * @var int|string|null
     */
    public $filterByTopicAttributeKeyID;

    /**
     * @var int|string|null
     */
    public $filterByTopicID;

    /**
     * @var string|null
     */
    public $filterByPageTopicAttributeKeyHandle;

    /**
     * @var bool|int|string|null
     */
    public $filterByFeatured;

    /**
     * @var string|null
     */
    public $eventListTitle;

    /**
     * @var int|string|null
     */
    public $linkToPage;

    /**
     * @var string|null
     */
    public $titleFormat;

    /**
     * @var string|null
     */
    public $eventPeriod;

    /**
     * @var string|null
     */
    public $eventOrder;

    public $helpers = array('form');

    protected $btInterfaceWidth = 500;
    protected $btInterfaceHeight = 340;
    protected $btTable = 'btEventList';

    public function getRequiredFeatures(): array
    {
        return [
            Features::CALENDAR
        ];
    }
    public function getBlockTypeDescription()
    {
        return t("Displays a list of events from a calendar.");
    }

    public function getBlockTypeName()
    {
        return t("Event List");
    }

    public function add()
    {
        $this->edit();
        $this->set('buttonLinkText', t('View Full Calendar'));
        $this->set('eventListTitle', t('Featured Events'));
        $this->set('totalToRetrieve', 9);
        $this->set('totalPerPage', 3);
        $this->set('filterByTopic', 'none');
        $this->set('titleFormat', 'h5');
        $this->set('filterByTopicAttributeKeyID', null);
        $this->set('eventPeriod', 'future_events');
        $this->set('eventOrder', 'most_recent_first');
    }

    protected function getCalendarOrCalendars()
    {
        if ($this->calendarAttributeKeyHandle) {
            $site = \Core::make('site')->getSite();
            $calendar = $site->getAttribute($this->calendarAttributeKeyHandle);
            if (is_object($calendar)) {
                return $calendar;
            }
        }
        if ($this->caID) {
            $number = new Numbers();
            if ($number->integer($this->caID)) {
                return Calendar::getByID($this->caID);
            } else {
                $caIDs = json_decode($this->caID);
                if (is_array($caIDs)) {
                    $calendars = array();
                    foreach($caIDs as $caID) {
                        $calendars[] = Calendar::getByID($caID);
                    }
                    if (count($calendars) == 1) {
                        return $calendars[0];
                    } else {
                        return $calendars;
                    }
                }
            }
        }
    }

    public function view()
    {
        if (!$this->totalToRetrieve) {
            $this->set('totalToRetrieve', 9);
        }
        $this->requireAsset('font-awesome');
        $list = new EventOccurrenceList();
        $calendar = $this->getCalendarOrCalendars();
        if (is_object($calendar)) {
            $permissions = new \Permissions($calendar);
            $this->set('canViewCalendar', $permissions->canViewCalendar());
        } else if (is_array($calendar)) {
            $canViewCalendar = true;
            foreach($calendar as $c) {
                $permissions = new \Permissions($c);
                if (!$permissions->canViewCalendar()) {
                    $canViewCalendar = false;
                }
            }
            $this->set('canViewCalendar', $canViewCalendar);
        }
        if ($calendar) {
            $date = Core::make('date')->date('Y-m-d');
            $time = Core::make('date')->toDateTime($date . ' 00:00:00')->getTimestamp();
            if ($this->eventPeriod == 'past_events') {
              $list->filterByEndTimeBefore($time);
            } elseif ($this->eventPeriod == 'future_events') {
              $list->filterByEndTimeAfter($time);
            }
            //$list->filterByEndTimeAfter($time);
            $list->filterByCalendar($calendar);
            if ($this->filterByFeatured) {
                $list->filterByAttribute('is_featured', true);
            }
            if ($this->filterByTopicAttributeKeyID) {
                $ak = EventKey::getByID($this->filterByTopicAttributeKeyID);
                if (is_object($ak)) {
                    if (isset($this->filterByTopicID) && !empty($this->filterByTopicID)) {
                        $list->filterByAttribute($ak->getAttributeKeyHandle(), $this->filterByTopicID);
                    }
                }
            } elseif ($this->filterByPageTopicAttributeKeyHandle) {
                $c = \Page::getCurrentPage();
                $topic = $c->getAttribute($this->filterByPageTopicAttributeKeyHandle);
                if (!empty($topic[0]) && is_object($topic[0])) {
                    $list->filterByTopic($topic[0]->getTreeNodeID());
                }
            }
                        $months_array=array('January',
                        'February',
                        'March',
                        'April',
                        'May',
                        'June',
                        'July',
                        'August',
                        'September',
                        'October',
                        'November',
                        'December',
                    );
            if(isset($_REQUEST['month']) && @in_array($_REQUEST['month'],$months_array)){
                $current_y=strtotime($_REQUEST['month'].date(' t',strtotime($_REQUEST['month'].', '.date('Y'))).', '.date('Y'));
                if($current_y>time()){
                    $list->filterByMonth(date('m',$current_y),date('Y'));
                }else{
                    $list->filterByMonth(date('m',$current_y),(date('Y')+1));
                }

            }
            if ($this->eventOrder == 'most_recent_first') {
              $list->sortBy('eo.startTime', 'desc');
            } elseif ($this->eventOrder == 'oldest_first') {
              $list->sortBy('eo.startTime', 'asc');
            }


            $this->set('list', $list);
            $this->set('calendar', $calendar);
            if (isset($this->internalLinkCID)) {
                $calendarPage = \Page::getByID($this->internalLinkCID);
                if (is_object($calendarPage) && !$calendarPage->isError()) {
                    $this->set('calendarPage', $calendarPage);
                }
            }
            if ($this->linkToPage) {
                $this->set('linkToPage', \Page::getByID($this->linkToPage));
            }
            $this->loadKeys();
        }
        $formatter = $this->app->make(CalendarServiceProvider::class)->getDateFormatter();
        $linkFormatter = $this->app->make(CalendarServiceProvider::class)->getLinkFormatter();
        $this->set('formatter', $formatter);
        $this->set('linkFormatter', $linkFormatter);
    }

    public function export(\SimpleXMLElement $blockNode)
    {
        parent::export($blockNode);
        $data = $blockNode->data->record;

        if ($this->filterByTopicAttributeKeyID) {
            $ak = EventKey::getByID($this->filterByTopicAttributeKeyID);
            if (is_object($ak)) {
                unset($data->filterByTopicAttributeKeyID);
                $data->addChild('filterByTopicAttributeKey', $ak->getAttributeKeyHandle());
            }
        }
        if ($this->filterByTopicID) {
            $node = Node::getByID($this->filterByTopicID);
            if (is_object($node)) {
                unset($data->filterByTopicID);
                $data->addChild('filterByTopic', $node->getTreeNodeDisplayPath());
            }
        }
    }

    public function edit()
    {
        $calendars = array_filter(Calendar::getList(), function ($calendar) {
            $p = new \Permissions($calendar);

            return $p->canViewCalendarInEditInterface();
        });
        $calendarSelect = array('' => t('** Select a Calendar'));
        foreach ($calendars as $calendar) {
            $calendarSelect[$calendar->getID()] = $calendar->getName();
        }
        $keys = CollectionKey::getList();
        $pageAttributeKeys = array();
        foreach ($keys as $ak) {
            if ($ak->getAttributeTypeHandle() == 'topics') {
                $pageAttributeKeys[] = $ak;
            }
        }
        $this->set('pageAttributeKeys', $pageAttributeKeys);
        $this->set('calendars', $calendarSelect);
        $this->set('featuredAttribute', EventKey::getByHandle('is_featured'));
        $this->set('pageSelector', Core::make("helper/form/page_selector"));

        $number = new Numbers();
        if (isset($this->caID)) {
            if ($number->integer($this->caID)) {
                $this->set('caID', array($this->caID)); // legacy single calendar field.
            } else {
                $this->set('caID', json_decode($this->caID));
            }
        }
        $this->loadKeys();

        if (isset($this->filterByPageTopicAttributeKeyHandle) && !empty($this->filterByPageTopicAttributeKeyHandle)) {
            $this->set('filterByTopic', 'page_attribute');
        } elseif (isset($this->filterByTopicAttributeKeyID) && !empty($this->filterByTopicAttributeKeyID)) {
            $this->set('filterByTopic', 'specific');
        } else {
            $this->set('filterByTopic', 'none');
        }
    }

    /*
    public function validate($args)
    {
        $calendar = null;
        if ($args['caID']) {
            $calendar = Calendar::getByID($args['caID']);
        } else if ($args['calendarAttributeKeyHandle']) {
            $site = \Core::make('site')->getSite();
            $calendar = $site->getAttribute($args['calendarAttributeKeyHandle']);
        }

        $e = \Core::make('error');
        if (!is_object($calendar)) {
            $e->add(t('You must choose a valid calendar.'));
        }
        $p = new \Permissions($calendar);
        if (!$p->canViewCalendarInEditInterface()) {
            $e->add(t('You do not have access to select this calendar.'));
        }
        return $e;
    }*/

    protected function loadKeys()
    {
        $keys = EventKey::getList(array('atHandle' => 'topics'));
        $this->set('attributeKeys', array_filter($keys, function ($ak) {
            return $ak->getAttributeTypeHandle() == 'topics';
        }));
    }

    public function save($args)
    {
        if ($args['chooseCalendar'] == 'specific') {
            $args['caID'] = json_encode($args['caID']);
            $args['calendarAttributeKeyHandle'] = '';
        }
        if ($args['chooseCalendar'] == 'site') {
            $args['caID'] = 0;
            // pass through the attribute key handle to save.
        }

        if ($args['filterByTopic'] == 'none') {
            $args['filterByTopicID'] = 0;
            $args['filterByTopicAttributeKeyID'] = 0;
            $args['filterByPageTopicAttributeKeyHandle'] = '';
        }
        if ($args['filterByTopic'] == 'specific') {
            $args['filterByTopicID'] = intval($args['filterByTopicID']);
            $args['filterByTopicAttributeKeyID'] = intval($args['filterByTopicAttributeKeyID']);
            $args['filterByPageTopicAttributeKeyHandle'] = '';
        }
        if ($args['filterByTopic'] == 'page_attribute') {
            $args['filterByTopicID'] = 0;
            $args['filterByTopicAttributeKeyID'] = 0;
            // pass through the filterByPageTopicAttributeKeyHandle
        }

        $args['linkToPage'] = intval($args['linkToPage']);
        $args['filterByFeatured'] = intval($args['filterByFeatured'] ?? null);
        parent::save($args);
    }
}
