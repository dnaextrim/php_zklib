<?php
    function getSizeAttendance($self) {
        /*Checks a returned packet to see if it returned CMD_PREPARE_DATA,
        indicating that data packets are to be sent

        Returns the amount of bytes that are going to be sent*/
        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr( $self->data_recv, 0, 8 ) ); 
        $command = hexdec( $u['h2'].$u['h1'] );
        
        if ( $command == CMD_PREPARE_DATA ) {
            $u = unpack('H2h1/H2h2/H2h3/H2h4', substr( $self->data_recv, 8, 4 ) );
            $size = hexdec($u['h4'].$u['h3'].$u['h2'].$u['h1']);
            return $size;
        } else
            return FALSE;
    }
    
    if ( function_exists('reverseHex') ) {
        function reverseHex($hexstr) {
            $tmp = '';
            
            for ( $i=strlen($hexstr); $i>=0; $i-- ) {
                $tmp .= substr($hexstr, $i, 2);
                $i--;
            }
            
            return $tmp;
        }
    }

    function zkgetattendance($self) {
        $command = CMD_ATTLOG_RRQ;
        $command_string = '';
        $chksum = 0;
        $session_id = $self->session_id;
        
        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr( $self->data_recv, 0, 8) );
        $reply_id = hexdec( $u['h8'].$u['h7'] );

        $buf = $self->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
        
        socket_sendto($self->zkclient, $buf, strlen($buf), 0, $self->ip, $self->port);
        
        //try {
        @socket_recvfrom($self->zkclient, $self->data_recv, 1024, 0, $self->ip, $self->port);
        
        if ( getSizeAttendance($self) ) {
            $bytes = getSizeAttendance($self);
            while ( $bytes > 0 ) {
                @socket_recvfrom($self->zkclient, $data_recv, 1032, 0, $self->ip, $self->port);
                array_push( $self->attendancedata, $data_recv);
                $bytes -= 1024;
            }
            
            $self->session_id =  hexdec( $u['h6'].$u['h5'] );
            @socket_recvfrom($self->zkclient, $data_recv, 1024, 0, $self->ip, $self->port);
        }
        
        $attendance = array();  
        if ( count($self->attendancedata) > 0 ) {
            # The first 4 bytes don't seem to be related to the user
            for ( $x=0; $x<count($self->attendancedata); $x++) {
                if ( $x > 0 )
                    $self->attendancedata[$x] = substr( $self->attendancedata[$x], 8 );
            }
            
            $attendancedata = implode( '', $self->attendancedata );
            $attendancedata = substr( $attendancedata, 10 );
            
            while ( strlen($attendancedata) > 40 ) {
                
                $u = unpack( 'H78', substr( $attendancedata, 0, 39 ) );
                //24s1s4s11s
                //print_r($u);

                //$uid = hexdec( substr( $u[1], 0, 6 ) );
                //$uid = explode(chr(0), $uid);
                //$uid = intval( $uid[0] );
                /*$u1 = hexdec( substr($u[1], 4, 2) );
                $u2 = hexdec( substr($u[1], 6, 2) );
                $uid = $u1+($u2*256);*/
                $uid = trim(substr( $attendancedata, 4, 14 ), "\x0");
                
                $id = intval( str_replace("\0", '', hex2bin( substr($u[1], 6, 8) ) ) );
                $state = hexdec( substr( $u[1], 56, 2 ) );
                $timestamp = decode_time( hexdec( reverseHex( substr($u[1], 58, 8) ) ) ); 
                
                # Clean up some messy characters from the user name
                #uid = unicode(uid.strip('\x00|\x01\x10x'), errors='ignore')
                #uid = uid.split('\x00', 1)[0]
                #print "%s, %s, %s" % (uid, state, decode_time( int( reverseHex( timestamp.encode('hex') ), 16 ) ) )
                
                array_push( $attendance, array( $uid, $id, $state, $timestamp ) );
                
                $attendancedata = substr( $attendancedata, 40 );
            }
            
        }
            
        return $attendance;
        //} catch(exception $e) {
            //return False;
        //}
    }
    
    function zkclearattendance($self) {
        $command = CMD_CLEAR_ATTLOG;
        $command_string = '';
        $chksum = 0;
        $session_id = $self->session_id;
        
        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr( $self->data_recv, 0, 8) );
        $reply_id = hexdec( $u['h8'].$u['h7'] );

        $buf = $self->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
        
        socket_sendto($self->zkclient, $buf, strlen($buf), 0, $self->ip, $self->port);
        
        try {
            @socket_recvfrom($self->zkclient, $self->data_recv, 1024, 0, $self->ip, $self->port);
            
            $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6', substr( $self->data_recv, 0, 8 ) );
            
            $self->session_id =  hexdec( $u['h6'].$u['h5'] );
            return substr( $self->data_recv, 8 );
        } catch(exception $e) {
            return False;
        }
    }
?>
