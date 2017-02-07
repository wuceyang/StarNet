<?php
    namespace App\C\Admin;

    use \Request;
    use \Response;
    use \App\M\Actor AS mActor;

    class Actor extends Base{

        public function addnew(Request $req, Response $resp){

            if(!$req->isPost()){

                return $resp->withView('admin/actor_add.html')->display();
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