<?php
	namespace App\C\Admin;

	use \Request;
    use \Response;
    use \App\M\Corperation;
    use \App\Helper\Enum;
    use \App\M\Actor;

	class business extends Base{

		public function index(Request $req, Response $resp){

			$page 			= max(1, intval($req->get('page')));

			$pagesize 		= 20;

			$totalpartner 	= 0;

			$corperation 	= new Corperation();

			$coperationlist = $corperation->getList(['`Status` = ?'], [Enum::Status['normal']], $page, $pagesize);

			if($coperationlist){

				$actressId 		= explode(',', implode(',', array_column($coperationlist, 'ActressID')));

				$actor 			= new Actor();

				$actressInfo  	= $actor->getInfoById($actressId);

				foreach ($coperationlist as $k => $v) {

					$actress = [];
					
					$actorId = explode(',', $v['ActressID']);

					foreach ($actorId as $sk => $sv) {
						
						if(isset($actressInfo[$sv])){

							$actress[] = $actressInfo[$sv]['TrueName'];
						}
					}

					$v['RecommendActress'] = implode(',', $actress);

					$coperationlist[$k] = $v;
				}

				$totalpartner 	= $corperation->getTotal(['`Status` = ?'], [Enum::Status['normal']]);
			}

			$param 		= [];

			$pageParams = $this->getPageInfo('/admin/business/index', $page, $totalpartner, $param, $pagesize);

			$htmlParam 	= [
							'pageParam'    => $pageParams,
							'total'        => $totalpartner,
							'list'         => $coperationlist,
						 ];

			return $resp->withVars($htmlParam)->withView('admin/business_index.html')->display();
		}

		public function addnew(Request $req, Response $resp){

			if(!$req->isPost()){

				return $resp->withView('admin/business_add.html')->display();
			}

			$proname = trim($req->post('proname'));

			$pubtime = trim($req->post('pubtime'));

			$actrees = $req->post('actrees');

			if(!$proname){

				return $this->error('请填写项目名称', 101);
			}

			if(!$pubtime || !strtotime($pubtime)){

				return $this->error('请填写发布时间', 101);
			}

			if(!$actrees){

				return $this->error('请填写推荐主播');
			}

			$actrees = array_map('intval', $actrees);

			$data 	 = [
						'ProjectName' => $proname,
						'PublishTime' => $pubtime,
						'ActressID'   => implode(',', $actrees),
						'CreatorID'   => $this->userinfo['ID'],
						];

			$corperation = new Corperation();

			if(!$corperation->insert($data)){

				return $this->error('合作信息录入失败', 201);
			}

			return $this->success('合作信息录入成功');
		}

		public function remove(Request $req, Response $resp){

			$id 		 = intval($req->post('id'));

			$corperation = new Corperation();

			$corpInfo    = $corperation->getInfoById($id);

			if(!$corpInfo){

				return $this->error('找不到指定的商务合作信息', 201);
			}

			if($corpInfo['Status'] != Enum::Status['disable'] && !$corperation->setInfo('ID = ?', [$id], ['`Status`' =>  Enum::Status['disable']])){

				return $this->error('删除商务合作信息发生错误' . var_export($corperation->getError(), true), 201);
			}

			return $this->success();
		}

		public function edit(Request $req, Response $resp){

			if(!$req->isPost()){

				$id 		 = intval($req->get('id'));

				$corperation = new Corperation();

				$corpInfo    = $corperation->getInfoById($id);

				if(!$corpInfo){

					return $this->error('找不到指定的商务合作信息', 201);
				}

				$actressId 		= explode(',', $corpInfo['ActressID']);

				$actor 			= new Actor();

				$actressInfo	= $actor->getInfoById($actressId);

				$data 			= ['corpInfo' => $corpInfo, 'actressInfo' => $actressInfo];

				return $resp->withVars($data)->withView('admin/business_edit.html')->display();
			}

			$id 	 = intval($req->post('id'));

			$proname = trim($req->post('proname'));

			$proname = trim($req->post('proname'));

			$pubtime = trim($req->post('pubtime'));

			$actrees = $req->post('actrees');

			if(!$proname){

				return $this->error('请填写项目名称', 101);
			}

			if(!$pubtime || !strtotime($pubtime)){

				return $this->error('请填写发布时间', 101);
			}

			if(!$actrees){

				return $this->error('请填写推荐主播');
			}

			$actrees = array_map('intval', $actrees);

			$data 	 = [
						'ProjectName' => $proname,
						'PublishTime' => $pubtime,
						'ActressID'   => implode(',', $actrees),
						];

			$corperation = new Corperation();

			if(!$corperation->setInfo('ID = ?', [$id], $data)){

				return $this->error('合作信息更新失败', 201);
			}

			return $this->success('合作信息更新成功');
		}
	}