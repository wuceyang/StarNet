<?php

	namespace App\C;

	use \Request;
	use \Response;
	use \Config;

	class BaseController extends Controller{

    	public function __construct(Request $req, Response $resp){

			$params = [
						'req'  => &$req,
						'resp' => &$resp,
					  ];

			$resp->withVars($params);
    	}
	}
