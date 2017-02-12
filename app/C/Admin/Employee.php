<?php
	namespace App\C\Admin;

	use \Request;
    use \Response;
    use \App\M\RegisterActress;

	class Employee extends Base{

		public function index(Request $req, Response $resp){

			$page 			= max(1, intval($req->get('page')));

			$pagesize 		= 20;

			$totalactress 	= 0;

			$actress 		= new RegisterActress();

			$actresslist 	= $actress->getList([], [], $page, $pagesize);

			if($actresslist){

				$totalactress = $actress->getTotal();
			}

			$param 		= [];

			$pageParams = $this->getPageInfo('/admin/business/index', $page, $totalactress, $param, $pagesize);

			$htmlParam 	= [
							'pageParam'    => $pageParams,
							'total'        => $totalactress,
							'list'         => $actresslist,
						 ];

			return $resp->withVars($htmlParam)->withView('admin/business_index.html')->display();
		}
	}