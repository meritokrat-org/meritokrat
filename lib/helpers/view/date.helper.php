<?

class date_helper
{
	public static function get_day_name( $number )
	{
		$days = array(
			1 => t('Понедельник'),
			2 => t('Вторник'),
			3 => t('Среда'),
			4 => t('Четверг'),
			5 => t('Пятница'),
			6 => t('Суббота'),
			7 => t('Воскресенье')
		);

		return $days[$number];
	}
        public static function get_month_name($month_id=false) 
        {
                 $months=array(
                        '',
                        t('января'),
                        t('февраля'),
                        t('марта'),
                        t('апреля'),
                        t('мая'),
                        t('июня'),
                        t('июля'),
                        t('августа'),
                        t('сентября'),
                        t('октября'),
                        t('ноября'),
                        t('декабря')
                     );
                 if ($month_id) return $months[$month_id];
                 else return $months;
        }

	public static function human( $ts, $separator = '<br />' )
	{
		return self::get_day_name(date('N', $ts)) . $separator . '<b>' . date('H:i', $ts) . '</b>' . $separator . date('d/m/y', $ts);
	}

        public static function get_format_date($date, $time=false)
        {
                 $month=self::get_month_name(date('n',$date));
                 if($time && date('H',$date)!='00') $more = ' '.date('H',$date).':'.date('i',$date);
                 return date('j',$date).' '.$month.' '.date('Y',$date).$more;
        }

        public static function get_date_range($date1,$date2)
        { 
                 $date1 = explode(' ',self::get_format_date($date1,true));
                 $date2 = explode(' ',self::get_format_date($date2,true));
 
                 if($date1[3])$more1 = ' '.$date1[3];
                 if($date2[3])$more2 = ' '.$date2[3];
                 if($more1 && $more2 && $more1==$more2)$more = $more1;
                 elseif($more1 && $more2 && $more1!=$more2)$more = ' ('.trim($more1).' -'.$more2.')';

                 if($date1[0]==$date2[0] && $date1[1]==$date2[1] && $date1[2]==$date2[2])
                 {
                     return $date2[0].' '.$date2[1].' '.$date2[2].$more;
                 }
                 elseif($date1[1]==$date2[1] && $date1[2]==$date2[2])
                 {
                    return $date1[0].'-'.$date2[0].' '.$date2[1].' '.$date2[2].$more;
                 }
                 elseif($date1[1]!=$date2[1] && $date1[2]==$date2[2])
                 {
                     return $date1[0].' '.$date1[1].' - '.$date2[0].' '.$date2[1].' '.$date2[2].$more;
                 }
                 else
                 {
                     return $date1[0].' '.$date1[1].' '.$date1[2].' - '.$date2[0].' '.$date2[1].' '.$date2[2].$more;
                 }
        }

}