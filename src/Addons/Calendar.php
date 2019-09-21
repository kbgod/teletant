<?php


namespace Askoldex\Teletant\Addons;


use Askoldex\Teletant\Context;

class Calendar
{
    private $menuHandler;
    private $allMonth;
    private $ignoreList = [];
    private $ctx;
    private $caption;

    const SELECT_MONTH = "calendar.m/{month:int}";
    const SELECT_DATE  = "calendar.d/{date:int}";
    const IGNORE  = "calendar.ignore";

    public function __construct(Context $ctx, $caption, $allMonth = true)
    {
        $this->ctx = $ctx;
        $this->caption = $caption;
        $this->menuHandler = new Keyboard(Keyboard::INLINE);
        $this->allMonth = $allMonth;
    }

    public function ignoreDateRange($startDate, $endDate)
    {
        $this->ignoreList[] = ['start' => $startDate, 'end' => $endDate];
    }

    public function ignoreDay($day)
    {
        $this->ignoreDateRange(mktime(0, 0, 0, date('m', $day), date('d', $day), date('Y', $day)), mktime(23, 59, 59, date('m', $day), date('d', $day), date('Y', $day)));
    }

    private function compareWithIgnoreList($date)
    {
        foreach ($this->ignoreList as $dateRange) {
            if ($dateRange['start'] <= $date and $date <= $dateRange['end']) return true; // попал в список игнорирования
        }
        return false; // не попал в список игнорирования
    }

    public function build($time = null)
    {
        $time = ($time == null) ? time() : $time;
        $today = mktime(0, 0, 0, date('m', $time), date('d', time()), date('Y', $time));
        $this->menuHandler->row(Keyboard::btn(date('F', $today) . ' ' . date('Y', $today), 'calendar.ignore'));
        $this->menuHandler->row(Keyboard::btn('Пн', 'calendar.ignore'), Keyboard::btn('Вт', 'calendar.ignore'), Keyboard::btn('Ср', 'calendar.ignore'), Keyboard::btn('Чт', 'calendar.ignore'), Keyboard::btn('Пт', 'calendar.ignore'), Keyboard::btn('Сб', 'calendar.ignore'), Keyboard::btn('Вс', 'calendar.ignore'));
        for ($posMonth = 1; $posMonth <= date('t', $today);) {
            $format = [
                'Mon' => '',
                'Tue' => '',
                'Wed' => '',
                'Thu' => '',
                'Fri' => '',
                'Sat' => '',
                'Sun' => '',
            ];
            foreach ($format as $day => $value) {
                if (date('D', mktime(0, 0, 0, date('m', $today), $posMonth, date('Y', $today))) == $day AND $posMonth <= date('t', $today)) {
                    //$format[$day] = Keyboard::btn($posMonth, 'calendar.d/'.mktime(0, 0, 0, date('m', $today), $posMonth, date('Y', $today)));
                    $currentDay = mktime(0, 0, 0, date('m', $today), $posMonth, date('Y', $today));
                    if ($this->compareWithIgnoreList($currentDay)) $format[$day] = Keyboard::btn(' ', 'calendar.ignore');
                    else $format[$day] = Keyboard::btn($posMonth, 'calendar.d/' . $currentDay);
                    if ($posMonth <= date('t', $today)) $posMonth++;
                } else $format[$day] = Keyboard::btn(' ', 'calendar.ignore');

            }
            $this->menuHandler->arrayRow($format);
        }
        if ($this->allMonth) {
            $this->menuHandler->row(Keyboard::btn('<', 'calendar.m/' . strtotime('-1 month', $today)), Keyboard::btn(' ', 'calendar/ignore'), Keyboard::btn('>', 'calendar.m/' . strtotime('+1 month', $today)));
        }
        return $this->menuHandler;
    }
}