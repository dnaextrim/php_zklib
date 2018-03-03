<?php
    function getSizeUser($self) {
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
    
    function zksetuser($self, $uid, $userid, $name, $password, $role) {
        $command = CMD_SET_USER;
        // $command_string = str_pad(chr( $uid ), 2, chr(0)).chr($role).str_pad($password, 8, chr(0)).str_pad($name, 28, chr(0)).str_pad(chr(1), 9, chr(0)).str_pad($userid, 8, chr(0)).str_repeat(chr(0),16);
        // $byte1 = chr((int)($uid % 256));
        // $byte2 = chr((int)($uid >> 8));
        // $command_string = $byte1.$byte2.chr($role).str_pad($password, 8, chr(0)).str_pad($name, 28, chr(0)).str_pad(chr(1), 9, chr(0)).str_pad($userid, 8, chr(0)).str_repeat(chr(0),16);
        $command_string = pack('axaa8a28aa7xa8a16', chr($uid), chr($role), $password, $name, chr(1), '', $userid, '');
        $chksum = 0;
        $session_id = $self->session_id;
        
        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr( $self->data_recv, 0, 8) );
        $reply_id = hexdec( $u['h8'].$u['h7'] );

        $buf = $self->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
        
        socket_sendto($self->zkclient, $buf, strlen($buf), 0, $self->ip, $self->port);
        
        try {
            @socket_recvfrom($self->zkclient, $self->data_recv, 1024, 0, $self->ip, $self->port);
//            file_put_contents('set_user_binary_log', $self->data_recv.PHP_EOL.'========================='.PHP_EOL, FILE_APPEND);

            $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6', substr( $self->data_recv, 0, 8 ) );
            
            $self->session_id =  hexdec( $u['h6'].$u['h5'] );
            return substr( $self->data_recv, 8 );
        } catch(ErrorException $e) {
            return FALSE;
        } catch(exception $e) {
            return False;
        }
    }
    
    function zkgetuser($self) {
        $command = CMD_USERTEMP_RRQ;
        // $command = CMD_DB_RRQ;
        $command_string = chr(5);
        $chksum = 0;
        $session_id = $self->session_id;
        
        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr( $self->data_recv, 0, 8) );
        $reply_id = hexdec( $u['h8'].$u['h7'] );

        $buf = $self->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
        
        socket_sendto($self->zkclient, $buf, strlen($buf), 0, $self->ip, $self->port);
        
        try {
            @socket_recvfrom($self->zkclient, $self->data_recv, 1024, 0, $self->ip, $self->port);
//            file_put_contents('read_user_binary_log', $self->data_recv.'========', FILE_APPEND);

            $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6', substr( $self->data_recv, 0, 8 ) );
            
            if ( getSizeUser($self) ) {
                $bytes = getSizeUser($self);
                
                while ( $bytes > 0 ) {
                    @socket_recvfrom($self->zkclient, $data_recv, 1032, 0, $self->ip, $self->port);
//                    file_put_contents('read_user_binary_log', $data_recv.'========', FILE_APPEND);
                    array_push( $self->userdata, $data_recv);
                    $bytes -= 1024;
                }
                
                $self->session_id =  hexdec( $u['h6'].$u['h5'] );
                @socket_recvfrom($self->zkclient, $data_recv, 1024, 0, $self->ip, $self->port);
//                file_put_contents('read_user_binary_log', $data_recv.'========', FILE_APPEND);

            }
            
            
            $users = array();
            if ( count($self->userdata) > 0 ) {
                //The first 4 bytes don't seem to be related to the user
                for ( $x=0; $x<count($self->userdata); $x++) {
                    if ( $x > 0 )
                        $self->userdata[$x] = substr( $self->userdata[$x], 8 );
                }
                
                $userdata = implode( '', $self->userdata );
                
                $userdata = substr( $userdata, 11 );
                
                while ( strlen($userdata) > 72 ) {
                    
                    $u = unpack( 'H144', substr( $userdata, 0, 72) );

                    //$uid = hexdec( substr($u[1], 0, 4) ).' ';
                    $u1 = hexdec( substr($u[1], 2, 2) );
                    $u2 = hexdec( substr($u[1], 4, 2) );
                    $uid = $u1+($u2*256);
                    $cardno = hexdec( substr($u[1], 78, 2).substr($u[1], 76, 2).substr($u[1], 74, 2).substr($u[1], 72, 2) ).' '; 
                    $role = hexdec( substr($u[1], 4, 4) ).' '; 
                    $password = hex2bin( substr( $u[1], 8, 16 ) ).' '; 
                    $name = hex2bin( substr( $u[1], 24, 74 ) ). ' '; 
                    $userid = hex2bin( substr( $u[1], 98, 72) ).' ';
                    
                    //Clean up some messy characters from the user name
                    $password = explode( chr(0), $password, 2 );
                    $password = $password[0];
                    $userid = explode( chr(0), $userid, 2);
                    $userid = $userid[0];
                    $name = explode(chr(0), $name, 3);
                    $name = utf8_encode($name[0]);
                    $cardno = str_pad($cardno,11,'0',STR_PAD_LEFT);
                    
                    if ( $name == "" )
                        $name = $uid;
                    
                    $users[$uid] = array($userid, $name, $cardno, $uid,intval( $role ), $password);
                    
                    $userdata = substr( $userdata, 72 );
                }
            }
            
            return $users;
        } catch(ErrorException $e) {
            return FALSE;
        } catch(exception $e) {
            return False;
        }
    }
    
    function zkgetfp($self) {
        $command = chr(227).chr(17).chr(18);
        // $command = CMD_DB_RRQ;
        $command_string = chr(80).chr(16).chr(3).chr(253).chr(132).chr(64).chr(0).chr(0);
        $chksum = 0;
        $session_id = $self->session_id;
        
        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr( $self->data_recv, 0, 8) );
        $reply_id = hexdec( $u['h8'].$u['h7'] );

        $buf = $self->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
//        file_put_contents('request_finger', $self->data_recv.'========', FILE_APPEND);
        socket_sendto($self->zkclient, $buf, strlen($buf), 0, $self->ip, $self->port);
        
        try {
            @socket_recvfrom($self->zkclient, $self->data_recv, 1024, 0, $self->ip, $self->port);
//            file_put_contents('read_finger', $self->data_recv.'========', FILE_APPEND);
            
            return '';
        } catch(ErrorException $e) {
            return FALSE;
        } catch(exception $e) {
            return False;
        }
    }

    function zkclearuser($self) {
        $command = CMD_CLEAR_DATA;
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
        } catch(ErrorException $e) {
            return FALSE;
        } catch(exception $e) {
            return False;
        }
    }
    
    function zkclearadmin($self) {
        $command = CMD_CLEAR_ADMIN;
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
        } catch(ErrorException $e) {
            return FALSE;
        } catch(exception $e) {
            return False;
        }
    }

    function zkenrolluser($self, $userid) {
        $command = CMD_STARTENROLL;
        $command_string = pack("a*", $userid);
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
        } catch(ErrorException $e) {
            return FALSE;
        } catch(exception $e) {
            return False;
        }
    }
?>
