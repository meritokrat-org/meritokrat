<?php

class user_desktop_received_help_peer extends db_peer_postgre
{
    protected $table_name  = 'user_desktop_received_help';
    protected $primary_key = 'id';

    /**
     * @return user_data_peer
     */
    public static function instance()
    {
        return parent::instance('user_desktop_received_help_peer');
    }

    public static function getMounth()
    {

        return array(
            '1'  => t('Январь'),
            '2'  => t('Февраль'),
            '3'  => t('Март'),
            '4'  => t('Апрель'),
            '5'  => t('Май'),
            '6'  => t('Июнь'),
            '7'  => t('Июль'),
            '8'  => t('Август'),
            '9'  => t('Сентябрь'),
            '10' => t('Октябрь'),
            '11' => t('Ноябрь'),
            '12' => t('Декабрь'),
        );
    }

    public static function getYears()
    {
        return range(date('Y', $_SERVER["REQUEST_TIME"]), date("Y", $_SERVER['REQUEST_TIME'] + 10));
    }
}

?>