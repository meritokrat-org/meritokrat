<?

class pager_helper
{
    public static function get_full_ajax(
        $list,
        $current = null,
        $per_page = null,
        $show_pages = 5,
        $q = '',
        $srch = 0,
        $team = 0
    ) {
        if ($list instanceof pager) {
            $pager = $list;
        } else {
            $pager = self::get_pager($list, $current, $per_page);
        }

        $html = '';

        $start = $pager->get_page() - floor($show_pages / 2);
        if ($start < 1) {
            $start = 1;
        }

        $end = $start + $show_pages;
        if ($end > $pager->get_pages()) {
            $start = $start - ($end - $pager->get_pages());
            if ($start < 1) {
                $start = 1;
            }

            $end = $pager->get_pages();
        }

        if ($pager->get_previous()) {
            $html .= '<a ' . self::onclick_page(1, $q, $srch, $team) . '>&larr;</a>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $html .= '<a ' . ($i == $pager->get_page() ? 'class="selected"' : '') . self::onclick_page(
                    $i,
                    $q,
                    $srch,
                    $team
                ) . '>' . $i . '</a>';
        }

        if ($pager->get_next()) {
            $html .= '<a ' . self::onclick_page($current + ($show_pages / 2), $q, $srch, $team) . '>&rarr;</a>';
        }

        return $html;
    }

    /**
     * @return pager
     */
    public static function get_pager($list, $current, $per_page)
    {
        return new pager($list, $current, $per_page);
    }

    public function onclick_page($page = 1, $q = '', $srch = 0, $team)
    {
        if ($srch) {
            return "onclick=\"Application.goToSrchPage('$q',$page)\" ";
        } elseif ($team) {
            return "onclick=\"Application.goToTeamPage('$q',$page)\" ";
        } else {
            return "onclick=\"Application.goToPage('$q',$page)\" ";
        }
    }

    public static function get_full($list, $current = null, $per_page = null, $show_pages = 5)
    {
        if ($list instanceof pager) {
            $pager = $list;
        } else {
            $pager = self::get_pager($list, $current, $per_page);
        }

        $html = '';

        $start = $pager->get_page() - floor($show_pages / 2);
        if ($start < 1) {
            $start = 1;
        }

        $end = $start + $show_pages;
        if ($end > $pager->get_pages()) {
            $start = $start - ($end - $pager->get_pages());
            if ($start < 1) {
                $start = 1;
            }

            $end = $pager->get_pages();
        }

        if ($pager->get_previous()) {
            $html .= '<a href="' . $pager->get_previous_link() . '">&larr;</a>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $html .= '<a ' . ($i == $pager->get_page() ? 'class="selected"' : '') . ' href="' . $pager->get_uri(
                    $i
                ) . '">' . $i . '</a>';
        }

        if ($pager->get_next()) {
            $html .= '<a href="' . $pager->get_next_link() . '">&rarr;</a>';
        }

        return $html;
    }

    public static function get_short($list, $current = null, $per_page = null)
    {
        if ($list instanceof pager) {
            $pager = $list;
        } else {
            $pager = self::get_pager($list, $current, $per_page);
        }

        $html = '';

        if ($pager->get_previous()) {
            $html .= '<a href="' . $pager->get_previous_link() . '">&larr; Назад</a>';
        }
        if ($pager->get_next()) {
            $html .= '<a href="' . $pager->get_next_link() . '">Вперед &rarr;</a>';
        }

        return $html;
    }

    public static function get_long($list, $current = null, $per_page = null, $show_pages = 6)
    {
        if ($list instanceof pager) {
            $pager = $list;
        } else {
            $pager = self::get_pager($list, $current, $per_page);
        }

        $html = '';

        $start = $pager->get_page() - floor($show_pages / 2);
        if ($start < 1) {
            $start = 1;
        }

        $end = $start + $show_pages;
        if ($end > $pager->get_pages()) {
            $start = $start - ($end - $pager->get_pages());
            if ($start < 1) {
                $start = 1;
            }

            $end = $pager->get_pages();
        }

        if ($pager->get_pages() > $show_pages && $pager->get_current() != 1) {
            $html .= '<a href="' . $pager->get_uri(1) . '">&lt;&lt;</a>';
        }

        if ($pager->get_previous()) {
            $html .= '<a href="' . $pager->get_uri(
                    self::check_page($pager->get_current() - $show_pages - 1)
                ) . '">&lt;</a>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $html .= '<a ' . ($i == $pager->get_page() ? 'class="selected"' : '') . ' href="' . $pager->get_uri(
                    $i
                ) . '">' . $i . '</a>';
        }

        if ($pager->get_next()) {
            $html .= '<a href="' . $pager->get_uri(
                    self::check_page($pager->get_current() + $show_pages + 1, 1, $pager)
                ) . '">&gt;</a>';
        }

        if ($pager->get_pages() > $show_pages && $pager->get_current() != $pager->get_pages()) {
            $html .= '<a href="' . $pager->get_uri($pager->get_pages()) . '">&gt;&gt;</a>';
        }

        return $html;
    }

    private static function check_page($page, $direction = null, $pager = false)
    {
        if ($direction) {
            if ($page > $pager->get_pages()) {
                return $pager->get_pages();
            } else {
                return $page;
            }
        } else {
            if ($page < 1) {
                return 1;
            } else {
                return $page;
            }
        }
    }
}

class pager
{
    private $list     = 0;
    private $current  = 0;
    private $per_page = 0;
    private $pages;
    private $total    = 0;

    public function __construct($list, $current, $per_page)
    {
        $this->list     = $list;
        $this->current  = $current > 1 ? $current : 1;
        $this->per_page = $per_page;
        $this->total    = count($list);
        $this->pages    = ceil($this->total / $per_page);
    }

    public function get_next_link()
    {
        return $this->get_uri($this->get_next());
    }

    public function get_uri($page)
    {
        $uri = preg_replace('/[?&]page=[0-9]+/', '', $_SERVER['REQUEST_URI']);
        if ($page > 1) {
            $uri .= strpos($uri, '?') ? '&' : '?';
            $uri .= 'page=' . $page;
        }

        return $uri;
    }

    public function get_next()
    {
        return $this->current < $this->pages ? $this->current + 1 : null;
    }

    public function get_previous_link()
    {
        return $this->get_uri($this->get_previous());
    }

    public function get_previous()
    {
        return $this->current > 1 ? $this->current - 1 : null;
    }

    public function get_page()
    {
        return $this->current;
    }

    public function get_pages()
    {
        return $this->pages;
    }

    public function get_total()
    {
        return $this->total;
    }

    public function get_list()
    {
        return array_slice($this->list, ($this->current - 1) * $this->per_page, $this->per_page);
    }

    public function get_current() #invzim
    {
        return $this->current;
    }
}
