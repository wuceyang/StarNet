<?php
	namespace App\C\Admin;

	use \App\M\AdvImg;
	use \Request;
	use \Response;
	use \App\Helper\Enum;
	use \App\M\User;

	class Advertise extends Base{

		public function advImg(Request $req, Response $resp){

			$page     = max(1, intval($req->get('page')));
			
			$pagesize = intval($this->_pagesize);
			
			$adv      = new AdvImg();
			
			$totalAdv = 0;
			
			$where    = ['`Status` = ' . Enum::Status['normal']];
			
			$param    = [];
			
			$advList  = $adv->getList($where, $param, $page, $pagesize);

			if($advList){

				$totalAdv   = $adv->getTotal($where, $param);
				
				$uploaderId = array_column($advList, 'CreatorID');
				
				$user       = new User();

				$uploader   = $user->getInfoById($uploaderId);
			}

			$positionText = $this->getPositionText();

			$positionText[0] = '选择位置';

			$param 		= [];

			$pageParams = $this->getPageInfo('/admin/advertise/adv-img', $page, $totalAdv, $param, $pagesize);

			$htmlParam 	= [
							'pageParam'      => $pageParams,
							'total'        => $totalAdv,
							'list'         => $advList,
							'menuIdx'      => 0,
							'positionText' => $positionText,
							'uploader'     => $uploader,
						 ];

			return $resp->withVars($htmlParam)->withView('admin/advimg_list.html')->display();
		}

		public function addAdv(Request $req, Response $resp){

			$position = intval($req->post('position'));

			$url 	  = $req->post('image');

			if(!in_array($position, Enum::Position)){

				return $this->error('请选择发布的位置', 101);
			}

			if(!$url){

				return $this->error('请上传图片路径', 101);
			}

			$adv = new AdvImg();

			$advInfo = [
						'Path' 		=> $url,
						'Position' 	=> $position,
						'CreatorID' => $this->userinfo['ID'],
						];

			if(!$adv->insert($advInfo)){

				return $this->error('图片信息保存失败', 201);
			}

			return $this->success('图片信息保存成功', '/admin/advertise/adv-img');
		}

		public function removeAdv(Request $req, Response $resp){

			$advId 	= intval($req->get('id'));

			$adv 	= new AdvImg();

			$advInfo = $adv->getInfoById($advId);

			if(!$advInfo){

				return $this->error('找不到指定的图片信息', 202);
			}

			if($advInfo['Status'] == Enum::Status['disable']){

				return $this->success();
			}

			if(!$adv->setInfo('ID = ?', [$advId], ['Status' => Enum::Status['disable']])){

				return $this->error('删除图片出错', 201);
			}

			return $this->success();
		}
	}