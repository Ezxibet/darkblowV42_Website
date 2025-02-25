<?php

// ==================== //
//   [DEV] EyeTracker   //
//     Lolsecs#6289     //
// ==================== //

defined('BASEPATH') OR exit('No direct script access allowed');

class Lib
{
    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
		$this->ci->load->helper('file');
    }

	public function EncryptDecryptConfig()
	{
		$encrypt_config = read_file('./darkblow_config.json');
		$encrypt_decode = json_decode($encrypt_config);

		foreach ($encrypt_decode as $row)
		{
			$config = array(
				'ciphering' => $row->EncryptionConfig->ciphering,
				'options' => $row->EncryptionConfig->options,
				'encryption_iv' => $row->EncryptionConfig->encryption_iv,
				'encryption_key' => $row->EncryptionConfig->encryption_key
			);
		}

		return $config;
	}

	public function Encrypt($param)
	{
        // Store the cipher method
        $ciphering = $this->EncryptDecryptConfig()['ciphering'];
        
        // Use OpenSSl Encryption method
        $options = $this->EncryptDecryptConfig()['options'];
        
        // Non-NULL Initialization Vector for encryption
        $encryption_iv = $this->EncryptDecryptConfig()['encryption_iv'];
        
        // Store the encryption key
        $encryption_key = $this->EncryptDecryptConfig()['encryption_key'];
        
        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt($param, $ciphering, $encryption_key, $options, $encryption_iv);
		return $encryption;
	}

	public function Decrypt($param)
	{
		// Store the cipher method
        $ciphering = $this->EncryptDecryptConfig()['ciphering'];
        
        // Use OpenSSl Encryption method
        $options = $this->EncryptDecryptConfig()['options'];
        
        // Non-NULL Initialization Vector for decryption
        $decryption_iv = $this->EncryptDecryptConfig()['encryption_iv'];
        
        // Store the decryption key
        $decryption_key = $this->EncryptDecryptConfig()['encryption_key'];
        
        // Use openssl_decrypt() function to decrypt the data
        $decryption=openssl_decrypt ($param, $ciphering, $decryption_key, $options, $decryption_iv);
		
		return $decryption;
	}

	public function GetItemName($item_id)
	{
		$query = $this->ci->db->get_where('shop', array('item_id' => $item_id))->row();
		if ($query) return $query->item_name; else return "";
	}

	public function GetItemCategory($item_id)
	{
		if ($item_id >= 100003001 && $item_id <= 904007069) return 1;
		else if ($item_id >= 1001001003 && $item_id <= 1105003032) return 2;
		else return 3;
	}

	public function GetBuyType($item_id)
	{
		$query = $this->ci->db->get_where('shop', array('item_id' => $item_id))->row();
		if ($query) return $query->buy_type; else return 0;
	}

	public function GetItemDuration($buy_type, $count, $equip = null)
	{
		switch ($buy_type)
		{
			case '1':
				{
					if ($equip != null)
					if ($equip != 1) echo 'Invalid'; else if ($count == 1) echo $count.' Unit'; else echo $count.' Unit\'s';
					break;
				}
			case '2':
				{
					if ($equip == 1)
					{
						$result = $count / 24 / 60 / 60;
						if ($result == 1) echo $result.' Day'; else echo $result. ' Day\'s';
					}
					else if ($equip == 2)
					{
						$split = str_split($count, 2);
						$datestr = "20".$split[0].'-'.$split[1].'-'.$split[2].' '.$split[3].':'.$split[4].':'.'00';
						$date = strtotime($datestr);
						
						$diff = $date-time();
						$days = floor($diff / (60 * 60 * 24));
						
						//Report
						echo $days.' Day\'s Remaining';
					}
					else echo 'Permanent';
					break;
				}
			
			default:
				break;
		}
	}

	public function EncryptedWeb()
	{
		$query = array(
            0 => $this->ci->db->truncate('accounts'),
            1 => $this->ci->db->truncate('ban_history'),
            2 => $this->ci->db->truncate('check_user_itemcode'),
            3 => $this->ci->db->truncate('check_user_voucher'),
            4 => $this->ci->db->truncate('clan_data'),
            5 => $this->ci->db->truncate('clan_invites'),
            6 => $this->ci->db->truncate('events_login'),
            7 => $this->ci->db->truncate('events_mapbonus'),
            8 => $this->ci->db->truncate('events_playtime'),
			9 => $this->ci->db->truncate('events_quest'),
			10 => $this->ci->db->truncate('events_rankup'),
			11 => $this->ci->db->truncate('events_register'),
			12 => $this->ci->db->truncate('events_visit'),
			13 => $this->ci->db->truncate('events_xmas'),
			14 => $this->ci->db->truncate('friends'),
			15 => $this->ci->db->truncate('info_basic_items'),
			16 => $this->ci->db->truncate('info_channels'),
			17 => $this->ci->db->truncate('info_cupons_flags'),
			18 => $this->ci->db->truncate('info_gameservers'),
			19 => $this->ci->db->truncate('info_launcherkey'),
			20 => $this->ci->db->truncate('info_login_configs'),
			21 => $this->ci->db->truncate('info_missions'),
			22 => $this->ci->db->truncate('info_rank_awards'),
			23 => $this->ci->db->truncate('item_code'),
			24 => $this->ci->db->truncate('item_voucher'),
			25 => $this->ci->db->truncate('nick_history'),
			26 => $this->ci->db->truncate('player_bonus'),
			27 => $this->ci->db->truncate('player_configs'),
			28 => $this->ci->db->truncate('player_events'),
			29 => $this->ci->db->truncate('player_items'),
			30 => $this->ci->db->truncate('player_messages'),
			31 => $this->ci->db->truncate('player_missions'),
			32 => $this->ci->db->truncate('player_titles'),
			33 => $this->ci->db->truncate('shop'),
			34 => $this->ci->db->truncate('shop_set'),
			35 => $this->ci->db->truncate('tournament_rules'),
			36 => $this->ci->db->truncate('trade_market'),
			37 => $this->ci->db->truncate('web_download_clientlauncher'),
			38 => $this->ci->db->truncate('web_exchangeticket'),
			39 => $this->ci->db->truncate('web_ipbanned'),
			40 => $this->ci->db->truncate('web_quickslide'),
			41 => $this->ci->db->truncate('web_rankinfo'),
			42 => $this->ci->db->truncate('web_settings'),
			43 => $this->ci->db->truncate('webshop')
        );

		$state = array(
			'success' => 0,
			'failed' => 0
		);

		$count = count($query);

		for ($i=0; $i < $count - 1; $i++) if ($query[$i]) $state['success']++; else $state['failed']++;

		echo "Successfully Truncate Database. Success: ".$state['success'].", Failed: ".$state['failed'].".";
	}
    
	public function password_encrypt($password)
	{
		$ingredient = '/x!a@r-$r%an¨.&e&+f*f(f(a)';
		$encrypt_result = hash_hmac('md5', $password, $ingredient);
		return $encrypt_result;
	}

	public function FeatureControl($param)
	{
		$query = $this->ci->db->get_where('web_settings', array('id' => '1'))->row();

		switch ($param) 
		{
			case 'webshop':
				{
					if ($query->webshop == 0)
						return TRUE;
					if ($query->webshop == 1)
						return FALSE;
				}
			case 'trademarket':
				{
					if ($query->trade_market == 0)
						return TRUE;
					if ($query->trade_market == 1)
						return FALSE;
				}
			case 'webshop':
				{
					if ($query->exchange_ticket == 0)
						return TRUE;
					if ($query->exchange_ticket == 1)
						return FALSE;
				}
			case 'webshop':
				{
					if ($query->voucher == 0)
						return TRUE;
					if ($query->voucher == 1)
						return FALSE;
				}
			
			default:
				return TRUE;
		}
	}

	public function ExplodeDate($defaultDate)
    {
        $resultdate = array();

        // Get Years (2 Digits)
        $explode1 = explode('-', $defaultDate)[0];
        $split1 = str_split($explode1, 2);

        // Get Month (2 Digits)
        $explode2 = explode('-', $defaultDate)[1];

        // Get Days (2 Digits)
        $explode3 = explode('-', $defaultDate)[2];
        $split2 = str_split($explode3, 2);

        // Get Hours (2 Digits)
        $explode4 = explode('T', $defaultDate)[1];
        $explode5 = explode(':', $explode4);

        // Get Minutes (2 Digits)
        $explode6 = $explode5[1];

        // The Result
        $resultdate['years'] = $split1[1];
        $resultdate['month'] = $explode2;
        $resultdate['days'] = $split2[0];
        $resultdate['hours'] = $explode5[0];
        $resultdate['minutes'] = $explode6;

        return $resultdate;
    }

    public function ConvertDate($param)
    {
        return str_split($param, 2); // [0] Years | [1] Month | [2] Days | [3] Hours | [4] Minutes
    }

	public function GetTokenName()
	{
		return "tokenkey";
	}

	public function GetTokenKey()
	{
		$newtoken = '';

		$newtoken .= $this->GenerateRandomToken();

		if ($this->ci->db->get('web_tokenkey')->num_rows() == 0)
		{
			$insert = $this->ci->db->insert('web_tokenkey', array(
				'token' => $newtoken,
				'is_valid' => '1'
			));

			if ($insert) return $newtoken; else return "invalidtoken";
		}
		else
		{
			$query = $this->ci->db->order_by('id', 'desc')->limit(1)->get_where('web_tokenkey', array('is_valid' => '1'))->row();
			if ($query) return $query->token; else return "invalidtoken";
		}
	}

	public function GenerateRandomToken()
	{
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmopqrstuvwxyz1234567890';
		$length = array(
			'characters_length' => strlen($characters),
			'token_length' => 64
		);
		$value = array(
			'token' => ''
		);

		for ($i=0; $i < $length['token_length']; $i++) $value['token'] .= $characters[rand(0, $length['characters_length'] - 1)];

		return $value['token'];
	}
	
	/**
	 * Get Visitor Data
	 * 
	 * Remove "//" To Detect Your Page Visitor.
	 * 
	 * @return void
	 * @copyright Darkblow Studio
	 */
	public function GetVisitorData($page)
	{
		// $data = array(
		// 	'operating_system' => $this->ci->agent->platform(),
		// 	'browser' => $this->ci->agent->browser().' '.$this->ci->agent->version(),
		// 	'ip_address' => $this->ci->input->ip_address(),
		// 	'visited_page' => $page
		// );

		// $query = $this->ci->db->get_where('web_log', array('ip_address' => $data['ip_address'], 'visited_page' => $data['visited_page']))->row();
		// if ($query)
		// {
		// 	$count = $query->total_visit + 1;

		// 	$this->ci->db->where(array('ip_address' => $query->ip_address, 'visited_page' => $query->visited_page))->update('web_log', array('total_visit' => ($count), 'last_visit' => date('d-m-Y h:i:s')));
		// }
		// else
		// {
		// 	$this->ci->db->insert('web_log', array(
		// 		'operating_system' => $data['operating_system'],
		// 		'browser' => $data['browser'],
		// 		'ip_address' => $data['ip_address'],
		// 		'visited_page' => $data['visited_page'],
		// 		'total_visit' => '1',
		// 		'last_visit' => date('d-m-Y h:i:s')
		// 	));
		// }
	}

	/**
	 * Get Visitor Action
	 * 
	 * When Users Execute A Function, System Will Printed Into Database.
	 * 
	 * @param string
	 * @return void
	 * @copyright Darkblow Studio
	 */
	public function GetVisitorAction($action)
	{
		$data = array(
			'operating_system' => $this->ci->agent->platform(),
			'browser' => $this->ci->agent->browser().' '.$this->ci->agent->version(),
			'ip_address' => $this->ci->input->ip_address(),
			'visited_page' => '-',
			'actions' => $action,
			'total_visit' => '0',
			'last_visit' => date('d-m-Y h:i:s')
		);
		
		$this->db->insert('web_log', $data);
	}

	/**
	 * Get Reach Point State
	 * 
	 * Will Return HTTP Code For Checking Page Is Available Or Not.
	 * 
	 * @param string
	 * @return int
	 * @copyright Darkblow Studio
	 */
	public function GetReachPointState($url)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		$result = curl_exec($curl);
		
		if ($result !== false)
		{
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
			
			if ($statusCode == 404) return false;
			else return true;
		}
		else return false;
	}

	public function HostLibrary($server, $param)
	{
        $host_config = read_file('./darkblow_config.json');
        $host_decode = json_decode($host_config);

		foreach ($host_decode as $row)
		{	
			$main_server = array(
				'ip_address' => $row->CredentialsConfig->primary_host->host,
				'port_1' => $row->CredentialsConfig->primary_host->port,
				'port_2' => $row->CredentialsConfig->third_host->port
			);

			$side_server = array(
				'ip_address' => $row->CredentialsConfig->side_host->host,
				'port_1' => $row->CredentialsConfig->secondary_host->port,
				'port_2' => $row->CredentialsConfig->side_host->port
			);
		}

		switch ($server)
		{
			case 'main':
				{
					switch ($param)
					{
						case 'ip_address':
							return $main_server['ip_address'];
						case 'port_1':
							return $main_server['port_1'];
						case 'port_2':
							return $main_server['port_2'];
						default:
							return "";
					}
				}

			case 'side':
				{
					switch ($param)
					{
						case 'ip_address':
							return $side_server['ip_address'];
						case 'port_1':
							return $side_server['port_1'];
						case 'port_2':
							return $side_server['port_2'];
						default:
							return "";
					}
				}
				
			default:
				return "";
		}
	}

	public function CheckOpenPort($ip_address, $port)
	{
		$connection = @fsockopen($ip_address, $port);

		if (is_resource($connection))
		{
			return TRUE;

			fclose($connection);
		}

		else return FALSE;
	}

    public function SendSocket($ip_address, $port, $buffer)
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket)
        {
            $connect = socket_connect($socket, $ip_address, $port);
            if ($connect)
            {
                $write = socket_write($socket, $buffer, strlen($buffer));
                if ($write)
                {
                    $read = socket_read($socket, 2048);
                    return $read;
                }
                else return "Failed";
            }
            else return "Failed";
        }
        else return "Failed";
    }
}

// This Code Generated Automatically By EyeTracker Snippets. //

?>