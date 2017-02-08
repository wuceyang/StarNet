<?php
	namespace Cli;

	class Robot{

		public function start(){

			$cookiestr = 'ASP.NET_SessionId=yzhx0ex33nlhxq5yradwezby; .ASPXAUTH=636EA7E2A55ABAD1025DE40C05A9EFD554B0A3D1838FDE269584FDF12878B20B0ADB48B7712BDA7033CA04487C172E03F4121109ED237217B8B6C477B8FB3DB3A1982D2C002E10AD814944E88549118F9B2B3C022634D92BD9174B579943265756C053BA8239C40B821DA7610953DBDF92461382F539E2DA78E71A96070DCB674A9D3E70DB00A9E418842BC1F0411BC2';

			$url = 'http://v.sva.org.cn/Volunteer/Detail';

			$header = ['Cookie: ' . $cookiestr];

			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($ch, CURLOPT_HEADER, false);

			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

			$html = curl_exec($ch);

			echo $html;
		}
	}