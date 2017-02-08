<?php
    namespace App\C\Admin;

    use \Request;
    use \Response;
    use \App\Helper\Enum;
    use \App\M\Actor AS mActor;
    use \App\M\Talent;
    use \App\M\LivePlatform;

    class Actor extends Base{

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

            $birthDate = trim($req->posrt('birthday'));

            $tall      = intval($req->post('tall'));

            $mobile    = trim($req->post('mobile'));

            $wechat    = trim($req->post('wechat'));

            $platform  = $req->post('platform');

            $hotdegree = intval($req->post('hotdegree'));

            $followers = intval($req->post('follower'));

            $talent    = $req->post('talent');

            $experience= $req->post('expr');

            $photos    = $req->post('photos');

            $video     = $req->post('video'); 

            if(!$actorName){

                return $this->resp->error("主播姓名不能为空", 101);
            }

            if(!strtotime($startTime)){

                return $this->resp->error("入职时间不能为空", 101);
            }

            if(!$nickName){

                return $this->resp->error("主播昵称不能为空", 101);
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

                return $this->resp->error("主播信息保存失败", 201);
            }

            return $this->resp->success('','');
        }

        public function search(Request $req, Response $resp){

            $kw = trim($req->post('kw'));

            if(!$kw){

                return $this->success('','',[]);
            }

            $actor      = new mActor();

            $actors     = $actor->actorSearch($kw);

            $actorlist  = [];

            foreach($actors as $k => $v){

                $actorlist[] = [
                    'id'    => $v['aid'],
                    'name'  => $v['nickname'],
                ];
            }

            return $this->success('','', $actorlist);
        }
    }