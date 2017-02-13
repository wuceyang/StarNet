<?php
    namespace App\C\Admin;

    use \Request;
    use \Response;
    use \App\Helper\Enum;
    use \App\M\Content AS mContent;

    class Content extends Base{

        public function latest(Request $req, Response $resp){

            return $this->newslist('最新资讯', $req, $resp);
        }

        public function video(Request $req, Response $resp){

            return $this->newslist('精彩视频', $req, $resp);
        }

        public function music(Request $req, Response $resp){

            return $this->newslist('原创音乐', $req, $resp);
        }

        protected function newslist($type, &$req, &$resp){

            $page       = max(1, intval($req->get('page')));

            $pagesize   = 20;

            $contentType= Enum::ContentType[$type];

            $content    = new mContent();

            $list       = $content->getContentList($contentType, Enum::Status['normal'], $page, $pagesize);

            $total      = $content->getContentTotal($contentType, Enum::Status['normal']);

            $param      = [];

            $pageParams = $this->getPageInfo($req->server('REQUEST_URI'), $page, $total, $param, $pagesize);

            $param['pageParam'] = $pageParams;

            $param['type'] = $type;

            $param['list'] = $list;

            return $resp->withVars($param)->withView('admin/content_list.html')->display();
        }

        public function publish(Request $req, Response $resp){

            if(!$req->isPost()){

                $params = ['type' => $this->getContentType()];

                return $resp->withVars($params)->withView('admin/content_add.html')->display();
            }

            $title   = trim($req->post('title'));

            $type    = intval($req->post('type'));

            $image   = trim($req->post('image'));

            $video   = trim($req->post('video'));

            $date    = trim($req->post('date'));

            $ishot   = trim($req->post('ishot'));

            $text    = trim($req->post('content'));

            if(!$title){

                return $this->error("标题不能为空", 101, 'javascript:history.back()');
            }

            $newsTypes = $this->getContentType();

            $title    = htmlspecialchars($title);

            if(!isset($newsTypes[$type])){

                return $this->error("请选择文章类型", 101, 'javascript:history.back()');
            }

            if(!$image){

                return $this->error("请上传文章配图", 101, 'javascript:history.back()');
            }

            if(!$text){

                return $this->error("请填写文章内容", 101, 'javascript:history.back()');
            }

            if(!$date){

                $date = date('Y-m-d');
            }

            $content = new mContent();

            if(!$content->addContent($title, $type, $image, $video, $date, $ishot == 'on' ? 1 : 0, $text, $this->userinfo['ID'])){

                return $this->error("文章录入失败", "javascript:history.back()");
            }

            return $this->success("文章录入成功", "javascript:history.back()");
        }
    }