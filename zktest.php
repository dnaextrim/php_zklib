<html>
    <head>
        <title>ZK Test</title>
    </head>
    
    <body>
<?php
    include("zklib/zklib.php");
    
    $zk = new ZKLib("192.168.2.2", 4370);
    
    $ret = $zk->connect();
    sleep(1);
    if ( $ret ): 
        $zk->disableDevice();
        sleep(1);
    ?>
        
        <table border="1" cellpadding="5" cellspacing="2">
            <tr>
                <td><b>Status</b></td>
                <td>Connected</td>
                <td><b>Version</b></td>
                <td><?php echo $zk->version() ?></td>
                <td><b>OS Version</b></td>
                <td><?php echo $zk->osversion() ?></td>
                <td><b>Platform</b></td>
                <td><?php echo $zk->platform() ?></td>
            </tr>
            <tr>
                <td><b>Firmware Version</b></td>
                <td><?php echo $zk->fmVersion() ?></td>
                <td><b>WorkCode</b></td>
                <td><?php echo $zk->workCode() ?></td>
                <td><b>SSR</b></td>
                <td><?php echo $zk->ssr() ?></td>
                <td><b>Pin Width</b></td>
                <td><?php echo $zk->pinWidth() ?></td>
            </tr>
            <tr>
                <td><b>Face Function On</b></td>
                <td><?php echo $zk->faceFunctionOn() ?></td>
                <td><b>Serial Number</b></td>
                <td><?php echo $zk->serialNumber() ?></td>
                <td><b>Device Name</b></td>
                <td><?php echo $zk->deviceName(); ?></td>
                <td><b>Get Time</b></td>
                <td><?php echo $zk->getTime() ?></td>
            </tr>
        </table>
        <hr />
        <table border="1" cellpadding="5" cellspacing="2" style="float: left; margin-right: 10px;">
            <tr>
                <th colspan="5">Data User</th>
            </tr>
            <tr>
                <th>UID</th>
                <th>ID</th>
                <th>Name</th>
                <th>Role</th>
                <th>Password</th>
            </tr>
            <?php
            try {
                
                //$zk->setUser(1, '1', 'Admin', '', LEVEL_ADMIN);
                $user = $zk->getUser();
                sleep(1);
                while(list($uid, $userdata) = each($user)):
                    if ($userdata[2] == LEVEL_ADMIN)
                        $role = 'ADMIN';
                    elseif ($userdata[2] == LEVEL_USER)
                        $role = 'USER';
                    else
                        $role = 'Unknown';
                ?>
                <tr>
                    <td><?php echo $uid ?></td>
                    <td><?php echo $userdata[0] ?></td>
                    <td><?php echo $userdata[1] ?></td>
                    <td><?php echo $role ?></td>
                    <td><?php echo $userdata[3] ?>&nbsp;</td>
                </tr>
                <?php
                endwhile;
            } catch (Exception $e) {
                header("HTTP/1.0 404 Not Found");
                header('HTTP', true, 500); // 500 internal server error                
            }
            //$zk->clearAdmin();
            ?>
        </table>
        
        <table border="1" cellpadding="5" cellspacing="2">
            <tr>
                <th colspan="6">Data Attendance</th>
            </tr>
            <tr>
                <th>Index</th>
                <th>UID</th>
                <th>ID</th>
                <th>Status</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
            <?php
            $attendance = $zk->getAttendance();
            sleep(1);
            while(list($idx, $attendancedata) = each($attendance)):
                if ( $attendancedata[2] == 14 )
                    $status = 'Check Out';
                else
                    $status = 'Check In';
            ?>
            <tr>
                <td><?php echo $idx ?></td>
                <td><?php echo $attendancedata[0] ?></td>
                <td><?php echo $attendancedata[1] ?></td>
                <td><?php echo $status ?></td>
                <td><?php echo date( "d-m-Y", strtotime( $attendancedata[3] ) ) ?></td>
                <td><?php echo date( "H:i:s", strtotime( $attendancedata[3] ) ) ?></td>
            </tr>
            <?php
            endwhile
            ?>
        </table>
        
        <fieldset>
            <legend><b>Example Using: </b></legend>
            
<pre style='color:#000000;background:#ffffff;'><pre>
<span style='color:#5f5035;'>&lt;?php</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>include</span><span style='color:#808030;'>(</span><span style='color:#0000e6;'>"zklib/zklib.php"</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span> </span><span style='color:#808030;'>=</span><span> </span><span style='color:#800000;font-weight:bold; '>new</span><span> ZKLib</span><span style='color:#808030;'>(</span><span style='color:#0000e6;'>"192.168.1.201"</span><span style='color:#808030;'>,</span><span> </span><span style='color:#008c00;'>4370</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$ret</span><span> </span><span style='color:#808030;'>=</span><span> </span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>connect</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>disableDevice</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>version</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>osversion</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>platform</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>fmVersion</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>workCode</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>ssr</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>pinWidth</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>faceFunctionOn</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>serialNumber</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>deviceName</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$user</span><span> </span><span style='color:#808030;'>=</span><span> </span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>getUser</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>while</span><span style='color:#808030;'>(</span><span> </span><span style='color:#800000;font-weight:bold; '>list</span><span style='color:#808030;'>(</span><span style='color:#797997;'>$uid</span><span style='color:#808030;'>,</span><span> </span><span style='color:#797997;'>$userdata</span><span style='color:#808030;'>)</span><span> </span><span style='color:#808030;'>=</span><span> </span><span style='color:#400000;'>each</span><span style='color:#808030;'>(</span><span style='color:#797997;'>$user</span><span style='color:#808030;'>)</span><span> </span><span style='color:#808030;'>)</span><span> </span><span style='color:#800080;'>{</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>if</span><span> </span><span style='color:#808030;'>(</span><span style='color:#797997;'>$userdata</span><span style='color:#808030;'>[</span><span style='color:#008c00;'>2</span><span style='color:#808030;'>]</span><span> </span><span style='color:#808030;'>=</span><span style='color:#808030;'>=</span><span> LEVEL_ADMIN</span><span style='color:#808030;'>)</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$role</span><span> </span><span style='color:#808030;'>=</span><span> </span><span style='color:#0000e6;'>'ADMIN'</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>elseif</span><span style='color:#808030;'>(</span><span style='color:#797997;'>$userdata</span><span style='color:#808030;'>[</span><span style='color:#008c00;'>2</span><span style='color:#808030;'>]</span><span> </span><span style='color:#808030;'>=</span><span style='color:#808030;'>=</span><span> LEVEL_USER</span><span style='color:#808030;'>)</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$role</span><span> </span><span style='color:#808030;'>=</span><span> </span><span style='color:#0000e6;'>'USER'</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>else</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$role</span><span> </span><span style='color:#808030;'>=</span><span> </span><span style='color:#0000e6;'>'Unknown'</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>echo</span><span> </span><span style='color:#0000e6;'>'UID: '</span><span style='color:#808030;'>.</span><span style='color:#797997;'>$uid</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>echo</span><span> </span><span style='color:#0000e6;'>'ID: '</span><span style='color:#808030;'>.</span><span style='color:#797997;'>$userdata</span><span style='color:#808030;'>[</span><span style='color:#008c00;'>0</span><span style='color:#808030;'>]</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>echo</span><span> </span><span style='color:#0000e6;'>'Name: '</span><span style='color:#808030;'>.</span><span style='color:#797997;'>$userdata</span><span style='color:#808030;'>[</span><span style='color:#008c00;'>1</span><span style='color:#808030;'>]</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>echo</span><span> </span><span style='color:#0000e6;'>'Role: '</span><span style='color:#808030;'>.</span><span style='color:#797997;'>$role</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>echo</span><span> </span><span style='color:#0000e6;'>'Password: '</span><span style='color:#808030;'>.</span><span style='color:#797997;'>$userdata</span><span style='color:#808030;'>[</span><span style='color:#008c00;'>3</span><span style='color:#808030;'>]</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800080;'>}</span><span></span>
<span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$attendance</span><span> </span><span style='color:#808030;'>=</span><span> </span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>getAttendance</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>while</span><span style='color:#808030;'>(</span><span> </span><span style='color:#800000;font-weight:bold; '>list</span><span style='color:#808030;'>(</span><span style='color:#797997;'>$idx</span><span style='color:#808030;'>,</span><span> </span><span style='color:#797997;'>$attendancedata</span><span style='color:#808030;'>)</span><span> </span><span style='color:#808030;'>=</span><span> </span><span style='color:#400000;'>each</span><span style='color:#808030;'>(</span><span style='color:#797997;'>$attendance</span><span style='color:#808030;'>)</span><span> </span><span style='color:#808030;'>)</span><span> </span><span style='color:#800080;'>{</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>echo</span><span> </span><span style='color:#0000e6;'>'Index: '</span><span style='color:#808030;'>.</span><span style='color:#797997;'>$idx</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>echo</span><span> </span><span style='color:#0000e6;'>'ID: '</span><span style='color:#808030;'>.</span><span style='color:#797997;'>$attendancedata</span><span style='color:#808030;'>[</span><span style='color:#008c00;'>0</span><span style='color:#808030;'>]</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>echo</span><span> </span><span style='color:#0000e6;'>'Status: '</span><span style='color:#808030;'>.</span><span style='color:#797997;'>$attendancedata</span><span style='color:#808030;'>[</span><span style='color:#008c00;'>1</span><span style='color:#808030;'>]</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>echo</span><span> </span><span style='color:#0000e6;'>'Date: '</span><span style='color:#808030;'>.</span><span style='color:#400000;'>date</span><span style='color:#808030;'>(</span><span style='color:#0000e6;'>"d-m-Y"</span><span style='color:#808030;'>,</span><span> </span><span style='color:#400000;'>strtotime</span><span style='color:#808030;'>(</span><span style='color:#797997;'>$attendancedata</span><span style='color:#808030;'>[</span><span style='color:#008c00;'>2</span><span style='color:#808030;'>]</span><span style='color:#808030;'>)</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800000;font-weight:bold; '>echo</span><span> </span><span style='color:#0000e6;'>'Time: '</span><span style='color:#808030;'>.</span><span style='color:#400000;'>date</span><span style='color:#808030;'>(</span><span style='color:#0000e6;'>"H:i:s"</span><span style='color:#808030;'>,</span><span> </span><span style='color:#400000;'>strtotime</span><span style='color:#808030;'>(</span><span style='color:#797997;'>$attendancedata</span><span style='color:#808030;'>[</span><span style='color:#008c00;'>2</span><span style='color:#808030;'>]</span><span style='color:#808030;'>)</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#800080;'>}</span><span></span>
<span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>getTime</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>enableDevice</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='color:#797997;'>$zk</span><span style='color:#808030;'>-</span><span style='color:#808030;'>></span><span>disconnect</span><span style='color:#808030;'>(</span><span style='color:#808030;'>)</span><span style='color:#800080;'>;</span><span></span>
<span style='color:#5f5035;'>?></span>
</pre>
        
        </fieldset>
    <?php
        $zk->enrollUser('123');
        $zk->setUser(123, '123', 'Shubhamoy Chakrabarty', '', LEVEL_USER);
        $zk->enableDevice();
        sleep(1);
        $zk->disconnect();
    endif
?>
    </body>
</html>
