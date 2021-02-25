<?

class rss
{
    var $channel_url;
    var $channel_title;
    var $channel_description;
    var $channel_lang;
    var $channel_copyright;
    var $channel_date;
    var $channel_creator;
    var $channel_subject;
    // image
    var $image_url;
    // items
    var $items = array();
    var $nritems = 0;

// FUNCTIONS
    // constructor
    function RSSFeed() {
         $this->nritems=0;
        $this->channel_url='';
        $this->channel_title='';
        $this->channel_description='';
        $this->channel_lang='';
        $this->channel_copyright='';
        $this->channel_date='';
        $this->channel_creator='';
        $this->channel_subject='';
        $this->image_url='';
    }
    // set channel vars
    function SetChannel($url, $title, $description, $lang, $copyright, $creator, $subject) {
        $this->channel_url=$url;
        $this->channel_title=$title;
        $this->channel_description=$description;
        $this->channel_lang=$lang;
        $this->channel_copyright=$copyright;
        $this->channel_date=date("Y-m-d").'T'.date("H:i:s").'+01:00';
        $this->channel_creator=$creator;
        $this->channel_subject=$subject;
    }
    // set image
    function SetImage($url) {
        $this->image_url=$url;
    }
    // set item
    function SetItem($url, $title, $description, $date) {
        $this->items[$this->nritems]['url']=$url;
        $this->items[$this->nritems]['title']=$title;
        $this->items[$this->nritems]['description']=$description;
		$this->items[$this->nritems]['date']=$date;
        $this->nritems++;
    }
    // output feed
    function Output() {
        $output =  '<?xml version="1.0" encoding="utf-8"?>'."\n";
        $output .= '<rss version="2.0">'."\n";
        $output .= '<channel>'."\n";
        $output .= '<title>'.$this->channel_title.'</title>'."\n";
        $output .= '<link>'.$this->channel_url.'</link>'."\n";
        $output .= '<description>'.$this->channel_description.'</description>'."\n";

        for($k=0; $k<$this->nritems; $k++) {
            $output .= '<item>'."\n";
			$output .= '<title>'.htmlspecialchars($this->items[$k]['title']).'</title>'."\n";
            $output .= '<link>'.$this->items[$k]['url'].'</link>'."\n";
			$output .= '<description><![CDATA['.nl2br(htmlspecialchars($this->items[$k]['description'])).']]></description>'."\n";
			$output .= '<pubDate>' . $this->items[$k]['date'] . '</pubDate>'."\n";
			$output .= '<guid>'.$this->items[$k]['url'].'</guid>'."\n";
            $output .= '</item>'."\n";
        };
		$output .= '</channel>'."\n";
        $output .= '</rss>'."\n";
        return $output;
    }
};