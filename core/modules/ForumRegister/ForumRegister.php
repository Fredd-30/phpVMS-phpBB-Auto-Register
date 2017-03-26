<?php
class ForumRegister extends CodonModule
{
		public function __construct()
    {
			CodonEvent::addListener('ForumRegister');
    }

	 public function EventListener($eventinfo)
	 {
			if($eventinfo[0] == 'registration_complete')
			{
					$userinfo = $eventinfo[2];

					$fname = $userinfo['firstname'];
					$lname = $userinfo['lastname'];
					$pass = $userinfo['password1'];
					$email = $userinfo['email'];
					$ip_address = $userinfo['lastip'];
					$code = $userinfo['code'];


					$uinfo = PilotData::GetPilotByEmail($userinfo['email']);

					$code = PilotData::GetPilotCode($uinfo->code, $uinfo->pilotid);
					$pilot_id = $fname.' '.$lname;
					$pilot_id_klein = strtolower($pilot_id);
					$pilot_id_hyph = $fname.'-'.$lname;
					$pilot_id_klein_hyph = strtolower($pilot_id_hyph);

					$passMD5 = md5($pass);
					$pass_salt = md5(date('His'));

					$password = md5($passMD5 . $pass_salt);

					$tm = time();
					$member_group_id = '3'; // Change this setting specific to your forum.

					// Insert the user
					$query = "INSERT INTO core_members (name, member_group_id, email, ip_address, members_seo_name, members_pass_hash, members_pass_salt)
                    VALUES ('$pilot_id', '$member_group_id', '$email', '{$_SERVER['REMOTE_ADDR']}', '$pilot_id_klein_hyph', '$password', '$pass_salt')";
      		DB::query($query);
			}
	 }
}
