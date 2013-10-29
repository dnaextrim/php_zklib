<?php
    define( 'USHRT_MAX', 65535 );


    define( 'CMD_CONNECT', 1000 );
    define( 'CMD_EXIT', 1001 );
    define( 'CMD_ENABLEDEVICE', 1002 );
    define( 'CMD_DISABLEDEVICE', 1003 );

    define( 'CMD_ACK_OK', 2000 );
    define( 'CMD_ACK_ERROR', 2001 );
    define( 'CMD_ACK_DATA', 2002 );

    define( 'CMD_PREPARE_DATA', 1500 );
    define( 'CMD_DATA', 1501 );

    define( 'CMD_USERTEMP_RRQ', 9 );
    define( 'CMD_ATTLOG_RRQ', 13 );
    define( 'CMD_CLEAR_DATA', 14 );
    define( 'CMD_CLEAR_ATTLOG', 15 );

    define( 'CMD_WRITE_LCD', 66 );

    define( 'CMD_GET_TIME', 201 );
    define( 'CMD_SET_TIME', 202 );

    define( 'CMD_VERSION', 1100 );
    define( 'CMD_DEVICE', 11 );

    define( 'CMD_CLEAR_ADMIN', 20 );
    define( 'CMD_SET_USER', 8 );

    define( 'LEVEL_USER', 0 );
    define( 'LEVEL_ADMIN', 14 );

    function encode_time($t) {
        /*Encode a timestamp send at the timeclock

        copied from zkemsdk.c - EncodeTime*/
        $d = ( ($t->year % 100) * 12 * 31 + (($t->month - 1) * 31) + $t->day - 1) *
             (24 * 60 * 60) + ($t->hour * 60 + $t->minute) * 60 + $t->second;

        return $d;
    }

    function decode_time($t) {
        /*Decode a timestamp retrieved from the timeclock

        copied from zkemsdk.c - DecodeTime*/
        $second = $t % 60;
        $t = $t / 60;

        $minute = $t % 60;
        $t = $t / 60;

        $hour = $t % 24;
        $t = $t / 24;

        $day = $t % 31+1;
        $t = $t / 31;

        $month = $t % 12+1;
        $t = $t / 12;

        $year = floor( $t + 2000 );

        $d = date("Y-m-d H:i:s", strtotime( $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':'.$second) );
        
        return $d;
    }
?>
