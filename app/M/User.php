<?php
    namespace App\M;

    class User extends Model{

        public static $table = 'User';

        public function passwdEncrypt($account, $passwd){

            return md5($passwd . '|' . md5($account . $passwd));
        }

        public function userLogin($account, $passwd){

            return $this->where('account = ? AND passwd = ?', [$account, $this->passwdEncrypt($account, $passwd)])->getRow();
        }

        public function addUser($username, $account, $passwd, $mobile){

            $params = [

                'Account'   => $account,

                'UserName'  => $username,

                'Passwd'    => $this->passwdEncrypt($account, $passwd),

                'Mobile'    => $mobile,
            ];

            return $this->insert($params);
        }

        /**
         *获取用户列表
         *@return array
         */ 
        public function userList($status = null, $page = 0, $pagesize = 20){

            $where = $param = [];

            if($status){

                $where = ['status = ' . intval($status)];
            }

            $this->orderBy(['id DESC']);

            if($where){

                $this->where(implode(' AND ', $where), $param);
            }

            if($page && $pagesize){

                $this->page($page)->pagesize($pagesize);
            }

            return $this->getRows();
        }

        public function updateUserInfo($userId, $updateInfo){

            return $this->where('id = ?', [intval($userId)])->update($updateInfo);
        }

        public function getTotalUser($status = null){

            $where = $param = [];

            if($status && is_numeric($status)){

                $where[] = 'status = ?';

                $param[] = intval($status);
            }

            if($where){

                $this->where(implode(' AND ', $where), $param);
            }

            return $this->getCount();
        }
    }