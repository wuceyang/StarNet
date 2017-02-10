<?php
    namespace App\C\Admin;

    use \Request;
    use \Response;
    use \App\Helper\Enum;
    use \App\M\Actor AS mActor;
    use \App\M\Talent;
    use \App\M\LivePlatform;

    class Actor extends Base{

        public function index(Request $req, Response $resp){

            $pagesize = 20;

            $page    = max(1, intval($req->get('gender')));

            $gender  = intval($req->get('gender'));

            $mintall = intval($req->get('mintall'));

            $maxtall = intval($req->get('maxtall'));

            $city    = trim($req->get('city'));

            $talent  = intval($req->get('talent'));

            $mTalent = new mTalent();

            $talents = $mTalent->getList();

            $actor   = new mActor();

            $total   = 0;

            $actress = $actor->getQualifiedActress($gender, $mintall, $maxtall, $city, $talent, $page, $pagesize);

            if($actress){

                $total = $actor->getTotalQualifiedActress($gender, $mintall, $maxtall, $city, $talent);
            }

            $param = [];

            if($gender){

                $param['gender'] = $gender;
            }

            if($mintall){

                $param['mintall'] = $mintall;
            }

            if($maxtall){

                $param['maxtall'] = $maxtall;
            }

            if($city){

                $param['city'] = $city;
            }

            if($talent){

                $param['talent'] = $talent;
            }

            $pageParams = $this->getPageInfo('/admin/actor/index', $page, $total, $param, $pagesize);

            $htmlParam  = [
                        'pageParam' => $pageParams,
                        'total'     => $total,
                        'list'      => $actress,
                    ];

            return $resp->withView('admin/actor_list.html')->withVars($htmlParam)->display();
        }

        public function addnew(Request $req, Response $resp){

            if(!$req->isPost()){

                $talent     = new Talent();

                $talents    = $talent->getList();

                $platform   = new LivePlatform();

                $platforms  = $platform->getList();

                $param      = [
                                
                                'talent'    => $talents,

                                'platform'  => $platforms,

                                'gender'    => Enum::Gender,
                              
                              ];

                return $resp->withVars($param)->withView('admin/actor_add.html')->display();
            }

            $actorName = trim($req->post('name'));

            $gender    = intval($req->post('gender'));

            $startTime = trim($req->post('start'));

            $signYear  = intval($req->post('year'));

            $nickName  = trim($req->post('nick'));

            $stayCity  = trim($req->post('city'));

            $birthDate = trim($req->post('birthday'));

            $tall      = intval($req->post('tall'));

            $mobile    = trim($req->post('mobile'));

            $wechat    = trim($req->post('wechat'));

            $platform  = $req->post('platform');

            $hotdegree = intval($req->post('hotdegree'));

            $followers = intval($req->post('follower'));

            $talent    = $req->post('talent');

            $experience= $req->post('expr');

            $photos    = $req->post('image');

            $video     = $req->post('video'); 

            if(!$actorName){

                return $this->error("主播姓名不能为空", 101);
            }

            if(!strtotime($startTime)){

                return $this->error("入职时间不能为空" . $startTime, 101);
            }

            if(!$nickName){

                return $this->error("主播昵称不能为空", 101);
            }

            $actorInfo = [
                            'TrueName'      => $actorName,
                            'HireDate'      => $startTime,
                            'Gender'        => $gender,
                            'ContractYears' => $signYear,
                            'NickName'      => $nickName,
                            'WorkCity'      => $stayCity,
                            'Birthday'      => $birthDate,
                            'Height'        => $tall,
                            'Mobile'        => $mobile,
                            'Wechat'        => $wechat,
                            'LivePlatform'  => implode(',', $platform),
                            'HotDegree'     => $hotdegree,
                            'TotalFans'     => $followers,
                            'Talent'        => implode(',', $talent),
                            'Experientce'   => $experience,
                            'ImageAttach'   => json_encode($photos),
                            'ImageVideo'    => $video,
                            'CreatorID'     => $this->userinfo['ID'],
                         ];

            $actor = new mActor();

            if(!$actor->insert($actorInfo)){

                return $this->error("主播信息保存失败", 201);
            }

            return $this->success('','');
        }

        public function newTalent(Request $req, Response $resp){

            $talent = trim($req->post('talent'));

            if(!$talent){

                return $this->success('','',[]);
            }

            $param = ['TalentName' => $talent];

            $mTalent = new Talent();

            if(!$mTalent->insert($param)){

                return $this->error('特长添加失败', 201);
            }

            return $this->success('','');
        }

        public function newPlatform(Request $req, Response $resp){

            $platform = trim($req->post('platform'));

            if(!$platform){

                return $this->success('','',[]);
            }

            $param = ['Platform' => $platform];

            $mPlatform = new LivePlatform();

            if(!$mPlatform->insert($param)){

                return $this->error('直播平台添加失败' . var_export($mPlatform->getError(), true), 201);
            }

            return $this->success('','');
        }
    }