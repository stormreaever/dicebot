<?php

// Edit these settings
$chan = "#TheBrikWarsChat"; // change to the name of your channel
$server = "irc.mibbit.net"; // change to the address of your server
$port = 6667;
$nick = "dicebot"; // nickname of bot

// STOP EDITING NOW.
$socket = fsockopen("$server", $port);
fputs($socket,"USER $nick $nick $nick $nick :$nick\n");
fputs($socket,"NICK $nick\n");

// Prevent PHP from stopping the script after 30 sec
set_time_limit(0);

while($logincount < 10) {
$logincount++;
$data = fgets($socket, 128);
echo nl2br($data);
// Separate all data
$ex = explode(' ', $data);

// Send PONG back to the server
if($ex[0] == "PING"){
fputs($socket, "PONG ".$ex[1]."\n");
}
flush();
} 
sleep(1);

fputs($socket,"JOIN ".$chan."\n");

$prev_args = '';

while(1) {
    while($data = fgets($socket)) {
			echo nl2br($data);
			//echo $data;
			flush();

			$ex = explode(' ', $data);
			$channel = $ex[2];
				if($ex[0] == "PING"){ // reply to server PING to stay connected
						fputs($socket, "PONG ".$ex[1]."\n");
				}

        $args = NULL; for ($i = 3; $i < count($ex); $i++) { $args .= $ex[$i] . ' '; }
				
				for ($i = 0; $i < count($ex); $i++) {
					$last_message .= $ex[$i] . ' '; 
				}
				echo $last_message;
				
				// --- start of bot commands ---
				
				// type !roll to roll 1d6
				// type !roll 2d8+2 or any other dice notation value to roll it
				// include the $nick of your bot in a message and the bot will respond
				
				if (strpos(strtolower(str_replace (":", " ", $args)),"!help") == 1) { // help message command
					$message = 'type !roll to roll dice';
					fputs($socket, "PRIVMSG ".$channel." :\x0310".$message."\x03 \n");
				}
				elseif (strpos(strtolower(str_replace (":", " ", $args)),"!roll") == 1) { // roll command
				
					$cmd = substr(strtolower(str_replace (":", " ", $args)), 1);// chop off starting colon and save var as $cmd ex: !roll 3d6+2
					// assign default roll variables: !roll 1d6
					$dice_count = 1;
					$dice_type = 6;
					$dice_mod = 0;
					
					// get pos of " "
					$pos_space = strpos($cmd, " ");
					
					// get roll value string like "1d4+7"
					if ($pos_space == strlen('!roll')) {
						$roll = substr($cmd, $pos_space + 1);
						
					} else {
						$roll = '1d6';
					}
					
					// interpret $roll
						
					// get pos of "d"
					$pos_d = strpos($roll, "d");
				
					if ($pos_d > 0) {
						$dice_count = substr($roll, 0, $pos_d);
						
						if ( $dice_count >= 1) { // convert non-standard values to default of 1d6
						} else {
							$dice_count = 1;
						}
						
					} else {
						$dice_count = 1;
					}
					$dice_type = substr($roll, $pos_d + 1);
					
					if ( $dice_type >= 1) {// convert non-standard values to default of 1d6
					} else {
						$dice_type = 6;
					}
					
					// get pos of "+" and "-"
					$pos_plus = strpos($dice_type, "+");
					$pos_minus = strpos($dice_type, "-");
					
					if (strpos($dice_type, "+") > 0) {
						$exp_type = explode("+", $dice_type);
						$dice_type = $exp_type[0];
						$dice_mod = $exp_type[1];
					} 
					elseif (strpos($dice_type, "-") > 0) {
						$exp_type = explode("-", $dice_type);
						$dice_type = $exp_type[0];
						$dice_mod = 0 - $exp_type[1];
					} 
					else {
						$dice_mod = 0; // no plus or minus sign means the modifier is 0
					}
					
					// set message for dice roll
					$mod_sign = '';
					if ($dice_mod >= 0) {
						$mod_sign = "+";
					}
					$message = "rolling " . $dice_count . "d" . $dice_type . $mod_sign . $dice_mod; // announce dice roll
					fputs($socket, "PRIVMSG ".$channel." :\x0310".$message."\x03 \n"); // send message to channel
					
					// defaults for addition
					$sum = 0;
					$message = '';
					
					// addition function
					for ($i = 0; $i < $dice_count; $i ++) {
						$this_die = rand(1,(int)$dice_type);
						if ($i < $dice_count - 1) { // check if this die is last
							$message .= $this_die . ' + '; // add plus sign
						} else {
							$message .= $this_die; // don't add plus sign
						}
						$sum += (int)$this_die;
					}
					if ($dice_mod > 0) {
						$message .= ' + ' . $dice_mod;
					}
					if ($dice_mod < 0) {
						$message .= ' - ' . substr($dice_mod, 1);
					}
					$sum += (int)$dice_mod;
					
					fputs($socket, "PRIVMSG ".$channel." :\x0310".$message."\x03 \n"); // send message to channel
					
					$message = '= ' . (string)$sum;
					$message .= ' ';
					
					fputs($socket, "PRIVMSG ".$channel." :\x0310".$message."\x03 \n"); // send message to channel
				}
				elseif (strpos(strtolower(str_replace (":", " ", $args)),$nick) !== false) { // respond to name
						$messages = ['It me.', 'Hello.', 'Hi.', 'Did you mention me?', 'Hey', 'What are we all talking about?'];
						$random = rand(0,count($messages)-1);
						$message = $messages[$random];
						fputs($socket, "PRIVMSG ".$channel." :\x0310".$message."\x03 \n");
				}
				
				$prev_args = $args;
    }
}

?>