<?php
    define( 'USHRT_MAX', 65535 );


    define( 'CMD_CONNECT', 1000 ); // Conecction request
    define( 'CMD_EXIT', 1001 ); // Disconnection request
    define( 'CMD_ENABLEDEVICE', 1002 );  // Ensure the machine to be at normal work condition
    define( 'CMD_DISABLEDEVICE', 1003 ); // Make the machine to be at the shut-down condition, generally demontration in the work on LCD
    define( 'CMD_RESTART', 1004 ); // Restart the machine
    define( 'CMD_POWEROFF', 1005 ); // Shut-down power source
    define( 'CMD_SLEEP', 1006 ); // Ensure the machine to be at the idle state
    define( 'CMD_RESUME', 1007 ); // Awakens the sleep machine (temporarily not to support)
    
    define( 'CMD_CAPTUREFINGER', 1009 ); // Captures fingerprints picture
    define( 'CMD_TEST_TEMP', 1011 ); // Test some fingerprint exist or does not
    define( 'CMD_CAPTUREIMAGE', 1012 ); // Capture the entire image
    
    define( 'CMD_REFRESHDATA', 1013 ); // Refresh the machine interior data
    define( 'CMD_REFRESHOPTION', 1014 ); // Refresh the configuration parameter

    define( 'CMD_TESTVOICE', 1017 ); // Play voice

    define( 'CMD_VERSION', 1100 ); // Obtain the firmware edition
    define( 'CMD_CHANGE_SPEED', 1101 ); // Change transmission speed
    define( 'CMD_AUTH', 1102 ); // Connection authorizations
    
    define( 'CMD_PREPARE_DATA', 1500 ); // Prepare to transmit the data
    define( 'CMD_DATA', 1501 ); // Transmit a data packet
    define( 'CMD_FREE_DATA', 1502 ); // Clear machine opened buffer
    
    define( 'CMD_DB_RRQ', 7 ); // Read in some kind of data from the machine
    define( 'CMD_DB_WRQ', 8 ); // Upload th user information

    define( 'CMD_USERTEMP_RRQ', 9 ); // Read some fingerprint template or some kind of data entirely
    define( 'CMD_USERTEMP_WRQ', 10 ); // Upload some fingerprint template
    define( 'CMD_OPTIONS_RRQ', 11 ); // Read in the machine some configuration parameter
    define( 'CMD_OPTIONS_WRQ', 12 ); // Set machine configuration parameter
    define( 'CMD_ATTLOG_RRQ', 13 ); // Read all attendance record
    define( 'CMD_CLEAR_DATA', 14 ); // Celar data
    define( 'CMD_CLEAR_ATTLOG', 15 ); // Clear attendance records
    define( 'CMD_DELETE_USER', 18 ); // Delete some user
    define( 'CMD_DELETE_USERTEMP', 19 ); // Delete some fingerprint template
    define( 'CMD_CLEAR_ADMIN', 20 ); // Cancel the manager

    define( 'CMD_USERGRP_RRQ', 21 ); // Read the user grouping
    define( 'CMD_USERGRP_WRQ', 22 ); // Set users grouping
    
    define( 'CMD_USERTZ_RRQ', 23 ); // Read the user Time Zone set
    define( 'CMD_USERTZ_WRQ', 24 ); // Write the user Time Zone set
    
    define( 'CMD_GRPTZ_RRQ', 25 ); // Read the group Time Zone Set
    define( 'CMD_GRPTZ_WRQ', 26 ); // Write the group Time Zone Set

    define( 'CMD_TZ_RRQ', 27 ); // Read Time Zone Set
    define( 'CMD_TZ_WRQ', 27 ); // Write Time Zone
    
    define( 'CMD_ULG_RRQ', 29 ); // Read unlocks combination
    define( 'CMD_ULG_WRQ', 30 ); // Write unlocks combination
    
    define( 'CMD_UNLOCK', 31 ); // Unlock
    define( 'CMD_CLEAR_ACC', 32 ); // Restore Access Control set to the default condition

    define( 'CMD_CLEAR_OPLOG', 33 ); // Delete attendance machine all attendance record
    define( 'CMD_OPLOG_RRQ', 34 ); // Read manages the record
    define( 'CMD_GET_FREE_SIZES', 50 ); // Obtain machines condition, like user recording number and so on

    define( 'CMD_ENABLE_CLOCK', 57 ); // Ensure the machine to be at the normal work condition
    define( 'CMD_STARTVERIFY', 60 ); // Ensure the machine to be at the authentication condition
    define( 'CMD_STARTENROLL', 61 ); // Start to enroll some user, ensure the machines to be at the registration user condition
    
    define( 'CMD_CANCELCAPTURE', 62 ); // Make the machine to be at the waiting order status, please refers to the CMD_STARTENROLL description
    define( 'CMD_STATE_RRQ', 64 ); // Gain the machine the condition
    define( 'CMD_WRITE_LCD', 66 ); // Write LCD
    define( 'CMD_CLEAR_LCD', 67 ); // Clear the LCD captions (clear sreen)
    define( 'CMD_GET_PINWIDTH', 69 ); // Obtain the length of user's serial number

    define( 'CMD_SMS_WRQ', 70 ); // Upload the short message
    define( 'CMD_SMS_RRQ', 71 ); // Download the short message
    define( 'CMD_DELETE_SMS', 72 ); // Delete the short message

    define( 'CMD_UDATA_WRQ', 73 ); // Set user's short message
    define( 'CMD_DELETE_UDATA', 74 ); // Delete user's short message
    
    define( 'CMD_DOORSTATE_RRQ', 75 ); // Obtain the door condition

    define( 'CMD_WRITE_MIFARE', 76 ); // Write the MiFare card
    define( 'CMD_EMPTY_MIFARE', 78 ); // Clear the MiFare card
    
    define( 'CMD_GET_TIME', 201 ); // Obtain the machine time
    define( 'CMD_SET_TIME', 202 ); // Set the machine time
    
    define( 'CMD_REG_EVENT', 500 ); // Register the Event


    define( 'EF_ATTLOG', 1 ); // Be real-time to verify successfully
    define( 'EF_FINGER', 1 << 1 ); // Be real-time to press fingerprint (be real-time to return data type sign)
    define( 'EF_ENROLLUSER', 1 << 2 ); // Be real-time to enroll user
    define( 'EF_ENROLLFINGER', 1 << 3 ); // Be real-time to enroll fingerprint
    define( 'EF_BUTTON', 1 << 4 ); // Be real-time to press button
    define( 'EF_UNLOCK', 1 << 5 ); // Be real-time to unlock
    define( 'EF_VERIFY', 1 << 7 ); // Be real-time to verify fingerprint
    define( 'EF_FPFTR', 1 << 8 ); // Be real-time capture fingerprint
    define( 'EF_ALARM', 1 << 9 ); // Alarm signal
    
    define( 'CMD_ACK_OK', 2000 ); // Return value for order perform successfully
    define( 'CMD_ACK_ERROR', 2001 ); // Return value for order perform failed
    define( 'CMD_ACK_DATA', 2002 ); // Return Data
    define( 'CMD_ACK_RETRY', 2003 ); // Registered event occorred
    define( 'CMD_ACK_REPEAT', 2004 );
    define( 'CMD_ACK_UNAUTH', 2005 ); // Connection unauthorized
    define( 'CMD_ACK_UNKNOWN', 0xffff ); // Unknown order
    define( 'CMD_ACK_ERROR_CMD', 0xfffd ); // order false
    define( 'CMD_ACK_ERROR_INIT', 0xfffc ); // Not Initialized
    define( 'CMD_ACK_ERROR_DATA', 0xfffb );



    // define( 'CMD_WRITE_LCD', 66 );

    // define( 'CMD_GET_TIME', 201 );
    // define( 'CMD_SET_TIME', 202 );

    define( 'CMD_DEVICE', 11 );

    
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
