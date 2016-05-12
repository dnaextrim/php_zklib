<?php
    function reverseHex($hexstr) {
        $tmp = '';
        
        for ( $i=strlen($hexstr); $i>=0; $i-- ) {
            $tmp .= substr($hexstr, $i, 2);
            $i--;
        }
        
        return $tmp;
    }

    function zksettime($self, $t) {
        $command = CMD_SET_TIME;
        $command_string = pack('I',encode_time($t));
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
    
    function zkgettime($self) {
        $command = CMD_GET_TIME;
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
            return decode_time( hexdec( reverseHex( bin2hex( substr( $self->data_recv, 8 ) ) ) ) );
        } catch(ErrorException $e) {
            return FALSE;
        } catch(exception $e) {
            return False;
        }
    }
?>

