<?php
    namespace App\C\Admin;

    use \Request;
    use \Response;
    use \App\Helper\Enum;
    use \App\M\UserGroup;
    use \App\M\User as mUser;

    class User extends Base{

        public function login(Request $req, Response $resp){

            if(!$req->isPost()){

                return $resp->withView("admin/login.html")->display();
            }

            $account = trim($req->post('account'));

            $passwd  = trim($req->post('passwd'));

            if(!$account){

                return $this->error("登录帐号不能为空", 101);
            }

            if(!$passwd){

                return $this->error("登录密码不能为空", 101);
            }

            $user        = new mUser();

            $userProfile = $user->userLogin($account, $passwd);

            if(!$userProfile){

                return $this->error("登录帐号或密码错误",102);
            }

            if($userProfile && $userProfile['Status'] != 1){

                return $this->error("当前帐号已经被禁用",102);
            }

            $req->session()->set($this->_sesskey, $userProfile);

            return $this->success("登录成功", '/admin/advertise/adv-img');
        }

        public function logout(Request $req, Response $resp){

            $req->session()->destroy();

            $this->success("您已退出登录", "/admin/user/login");
        }

        public function resetPwd(Request $req, Response $resp){

            if(!$req->isPost()){

                return $resp->withView('admin/reset_passwd.html')->display();
            }

            $oldPass = trim($req->post('oldpwd'));

            $newPass = trim($req->post('newpwd'));

            $cnfPass = trim($req->post('cnfpwd'));

            if(!$oldPass){

                return $this->error("原始密码不能为空", 101);
            }

            if(!$newPass){

                return $this->error("新密码不能为空", 101);
            }

            if($newPass != $cnfPass){

                return $this->error("新密码和确认密码不一致", 101);
            }

            if(strlen($newPass) < 6 || strlen($newPass) > 16){

                return $this->error("新密码长度必须为6-16位", 101);
            }

            $user = new mUser();

            if($this->userinfo['Passwd'] != $user->passwdEncrypt($this->userinfo['Account'],$oldPass)){

                return $this->error("原始密码错误，请检查输入", 102);
            }

            if(!$user->updateUserInfo($this->userinfo['ID'], ['Passwd' => $user->passwdEncrypt($this->userinfo['Account'], $newPass)])){

                return $this->error("密码更新失败", 201);
            }

            return $this->success("密码更新成功,您需要重新登录", "/admin/user/logout");
        }

        public function index(Request $req, Response $resp){

            if(!$this->userinfo['IsFixed']){

                return $this->error('您无权限访问当前页面', 403);
            }

            $page       = intval($req->get('page'));

            $page       = max(1, $page);

            $pagesize   = 20;

            $user       = new mUser();

            $groupId    = [];

            $userList   = $user->getList([], [], $page, $pagesize);

            $totalUser  = $user->getTotal([], []);

            $pageParam  = $this->getPageInfo("/admin/user/index", $page, $totalUser, [], $pagesize);

            $params = [
                        'list'      => $userList,
                        'pageParam' => $pageParam,
                        'total'     => $totalUser,
                      ];

            return $resp->withVars($params)->withView("admin/user_list.html")->display();

        }

        public function addNew(Request $req, Response $resp){

            if(!$this->userinfo['IsFixed']){

                return $this->error('您无权限访问当前页面', 403);
            }

            $truename = trim($req->post('truename'));
            
            $account  = trim($req->post('account'));

            $mobile   = trim($req->post('mobile'));

            $passwd   = trim($req->post('passwd'));

            if(!$truename){

                return $this->error('用户姓名不能为空', 101, 'javascript:history.back();');
            }

            if(!$account){

                return $this->error('登录帐号不能为空', 101, 'javascript:history.back();');
            }

            if(!$passwd || strlen($passwd) < 6){

                return $this->error('请输入登录密码，不能少于6个自负', 101, 'javascript:history.back();');
            }

            $user    = new mUser();

            $passwd  = $user->passwdEncrypt($account, $passwd);

            if(!$user->addUser($truename, $account, $passwd, $mobile)){

                return $this->error('帐号添加失败', 101, 'javascript:history.back();');
            }

            return $this->success('帐号添加成功', '/admin/user/index');
        }

        public function editUser(Request $req, Response $resp){

            $userid   = intval($req->post('userid'));

            $username = trim($req->post('username'));
            
            $account  = trim($req->post('account'));

            $passwd   = trim($req->post('passwd'));
            
            $groupid  = $req->post('groupid');
            
            $status   = intval($req->post('status'));

            if(!$username){

                return $this->error('用户姓名不能为空', 101, 'javascript:history.back();');
            }

            if(!$account){

                return $this->error('登录帐号不能为空', 101, 'javascript:history.back();');
            }

            if(!$groupid){

                return $this->error('请给帐号设置分组', 101, 'javascript:history.back();');
            }

            $groupid = array_map('intval', $groupid);

            $groupid = array_unique($groupid);

            if(!in_array($status, [Enum::STATUS_NORMAL, Enum::STATUS_DISABLED])){

                return $this->error('帐号状态不正确', 101, 'javascript:history.back();');
            }

            $user = new mUser();

            $updateInfo = [
                            'account'  => $account,
                            'username' => $username,
                            'status'   => $status,
                            'group_id' => ',' . implode(',', $groupid) . ',',
                          ];

            if($passwd){

                $updateInfo['passwd'] = $user->passwdEncrypt($account, $passwd);
            }

            if(!$user->updateUserInfo($userid, $updateInfo)){

                return $this->error('帐号信息更新失败', 101, 'javascript:history.back();');
            }

            return $this->success('帐号信息更新成功', 'javascript:history.back();');
        }

        public function switchStatus(Request $req, Response $resp){

            $userid   = intval($req->get('id'));
            
            $status   = intval($req->get('status'));

            $user     = new mUser();

            if(!in_array($status, [Enum::Status['normal'], Enum::Status['disable']])){

                return $this->error('帐号目标状态不正确', 101, 'javascript:history.back();');
            }

            if(!$user->updateUserInfo($userid, ['status' => $status])){

                return $this->error('帐号状态更新失败', 101, 'javascript:history.back();');
            }

            return $this->success('帐号状态更新成功', 'javascript:history.back();');
        }
    }