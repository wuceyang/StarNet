<?php
	namespace App\C\Admin;

	use \App\C\BaseController;
	use \App\Helper\Enum;
	use \Request;
	use \Response;
	use \Config;
	use \Log;

	class Base extends BaseController{

		protected $_sesskey  = 'userinfo';
		
		protected $_retAjax  = true;
		
		protected $userinfo  = [];
		
		protected $_pagesize = 2;

		public function __construct(Request $req, Response $resp){

			parent::__construct($req, $resp);

			$this->_retAjax = $req->isAjax();

			$noLoginRequest = [

				'user' => [
					'login',
				],
			];

			$controller = strtolower($req->getController());

			$action 	= strtolower($req->getAction());

			$req->session()->start();

			$this->userinfo = $req->session()->get($this->_sesskey);

			$this->initViewVars($req, $resp);

			if(!isset($noLoginRequest[$controller]) || !in_array($action, $noLoginRequest[$controller])){

				$this->checkLogin($req, $resp);
			}

			if($this->userinfo){

				$resp->withVars(['userinfo' => $this->userinfo]);
			}
		}

		protected function initViewVars($req, $resp){

			define('__RESPATH__', Config::get('global.resPrefix'));

			$params = [
						'menu' 		=> $this->initSideBar(),
						'userinfo' 	=> $this->userinfo,
					  ];

			$resp->withVars($params);
		}
		//指定返回的数据类型
		protected function dataType($isAjax = true){

			$this->_retAjax = $isAjax;
		}

        //返回错误信息
        protected function error($message = '', $err_code = 101, $uri = 'javascript:history.back();'){

			$data = ['code' => $err_code, 'message' => $message, 'uri' => $uri];

			return $this->response($data);
		}

        //返回成功信息
		protected function success($message = '', $uri = '', $retdata = ''){

			$data = ['code' => 200, 'message' => $message, 'uri' => $uri];

			$data['data'] = $retdata;

			return $this->response($data);
		}
		//信息返回
		private function response($retdata){

			$this->_retAjax ? $this->retAjax($retdata) : $this->retHtml($retdata);

			exit;
		}
		//返回json
		private function retAjax($data){

			exit(json_encode($data, JSON_UNESCAPED_UNICODE));
		}
		//返回HTML信息提示页面
		private function retHtml($data){

			$req  = Request::getInstance();

			$resp = Response::getInstance($req);

			return $resp->withVars($data)->withView('admin/info.html')->display();
		}

		//检查用户登录
		protected function checkLogin($req, $resp, $return = false){

			$flag = true;

			if(!$this->userinfo){

				$flag = false;
			}

            if($return){

				return $flag;
			}

			if(!$return && !$flag){

				$this->error("用户登录过期，请重新登录", 101, '/admin/user/login');
			}
		}

		/**
		 * 获取分页信息
		 * @param  string  	$baseUrl     页面链接地址
		 * @param  int  	$currentPage 当前页码
		 * @param  int  	$totalRecord 记录总条数
		 * @param  array   	$params      url上传递的其他参数
		 * @param  int 		$pagesize    每页显示的记录数量
		 * @return array
		 */
		public function getPageInfo($baseUrl, $currentPage, $totalRecord, $params = [], $pagesize = 20){

			$baseUrl    = $baseUrl . ($params ? '?' . http_build_query($params) : '');

            $pageParams = [
                            'baseUrl'       => $baseUrl,

                            'curPage'       => $currentPage,

                            'totalPage'     => ceil($totalRecord / $pagesize),

                            'wrapTag'       => '',

                            'curpageClass'  => 'active',

                            'wrapTagClass'  => true,
                          ];

            $pageParams['pageStr'] = (new \App\Helper\Pager($pageParams))->getPageLinks();

            $pageParams['totalRecord'] = $totalRecord;

            return $pageParams;
		}

		/**
		 * 生成唯一token，防止重复提交表单
		 */
		public function setFormToken($req, $resp, $tokenName = 'token'){

			$token 						=  md5(uniqid());

			$this->userinfo['token'] 	= $token;

			$req->session()->set($this->_sesskey, $this->userinfo);

			$resp->withVars([$tokenName => $token]);
		}

		/**
		 * 验证表单中提交的token
		 * @param  Request 	$req       Request实例
		 * @param  Response $resp      Response实例
		 * @param  string 	$tokenName 表单中的token字段名
		 * @return bool
		 */
		public function formTokenValidate($req, $resp, $tokenName = 'token'){

			$flag  = false;
			
			$token = trim($req->post($tokenName, ""));

			if($token && isset($this->userinfo['token']) && $this->userinfo['token'] == $token){

				$flag = true;
			}
			//销毁token
			unset($this->userinfo['token']);

			$req->session()->set($this->_sesskey, $this->userinfo);

			return $flag;
		}
		/**
		 * 初始化菜单列表
		 * @return array
		 */
		private function initSideBar(){

			return  [
						[
							'text' => '海报管理',
							'icon' => '&#xe60d;',
							'flag' => 'advertise',
							'sub'  => [
										'头图管理' => '/admin/advertise/adv-img',
										'推荐主播' => '/admin/advertise/recommend',
										'合作伙伴' => '/admin/advertise/index',
									  ]
						],
						[
							'text' => '发布系统',
							'icon' => '&#xe63a;',
							'flag' => 'content',
							'sub'  => [
										'发布内容' => '/admin/content/publish',
										'最新资讯' => '/admin/content/latest',
										'精彩视频' => '/admin/content/video',
										'原创音乐' => '/admin/content/music',
									  ],
						],
						[
							'text' => '商务合作',
							'icon' => '&#xe62a;',
							'flag' => 'business',
							'sub'  => [

										'合作列表' => '/admin/business/index',
										'新增合作' => '/admin/business/addnew',
									  ],
						],
						[
							'text' => '主播管理',
							'icon' => '&#xe61b;',
							'flag' => 'actor',
							'sub'  => [
										'新增主播' => '/admin/actor/addnew',
										'主播列表' => '/admin/actor/index',
									  ],
						],
						[
							'text' => '应聘管理',
							'icon' => '&#xe63c;',
							'flag' => 'employee',
							'sub'  => [
										'应聘主播' => '/admin/employee/index'
									  ]
						],
						[
							'text' => '系统管理',
							'icon' => '&#xe631;',
							'flag' => 'user',
							'sub'  => [
										'系统管理' => '/admin/user/index',
										'修改密码' => '/admin/user/reset-pwd',
									  ]
						]
					];
		}

		//正文内容处理
		public function formatContent($str){

			$str = strip_tags($str);

			return str_replace('&nbsp;', ' ', $str);
		}

		//标题处理
		public function formatTitle($str){

			$str = strip_tags($str);

			return str_replace('&nbsp;', '', $str);
		}

		//获取位置的map，用来显示文本
		public function getPositionText(){

			return array_flip(Enum::Position);
		}

		public function getContentType(){

			return array_flip(Enum::ContentType);
		}
	}