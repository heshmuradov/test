<?php 
	$date='2013-06-29';
			{
                $day = date("d", strtotime($date));
				$month= date("m", strtotime($date));
                if ($day < 31 && $month!=7) {
				
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +7 month");
                    $enddate = date("Y-m-d", $date);
                    $mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
                    $start = date("$yy-$mm-01"); //t -> last day of month
                    $start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);
				//	echo $enddate;
                } else {
                    $date = strtotime(date("Y-m-d", strtotime($date)));
                    $enddate = date("Y-m-d", $date);
					$mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
					$mm= $mm+7;
					if ($mm>12) {$yy=$yy+1; $mm=$mm-12;} 
					$start = date("$yy-$mm-01");
					$start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);
                }

            }
			echo $enddate;
?>