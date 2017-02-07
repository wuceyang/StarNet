<?php
    namespace App\M;

    class Content extends Model{

        public static $table = 'Actress_News';

        public function getContentList($newsType, $status, $page = 0, $pagesize = 20){

            $where = $param = [];

            if($newsType){

                $where[] = 'NewsType = ?';

                $param[] = intval($newsType);
            }

            if($status){

                $status = is_array($status) ? array_map('intval', $status) : [intval($status)];

                $where[] = 'status IN (' . implode(',', $status) . ')';
            }

            if($page && $pagesize){

                $this->page($page)->pagesize($pagesize);
            }

            return $this->orderBy(['ID DESC'])->getRows(implode(' AND ', $where), $param);
        }

        public function getContentTotal($newsType, $status, $page = 0, $pagesize = 20){

            $where = $param = [];

            if($newsType){

                $where[] = 'NewsType = ?';

                $param[] = intval($newsType);
            }

            if($status){

                $status = is_array($status) ? array_map('intval', $status) : [intval($status)];

                $where[] = 'status IN (' . implode(',', $status) . ')';
            }

            return $this->getCount(implode(' AND ', $where), $param);
        }

        public function addContent($title, $type, $image, $video, $date, $ishot, $content, $creatorid){

            $params = [
                'Title'       => $title,
                'ListImage'   => $image,
                'Attachment'  => $video,
                'NewsType'    => $type,
                'Content'     => $content,
                'IsRecommend' => intval($ishot),
                'CreatorID'   => $content,
                'CreateTime'  => intval($creatorid)
            ];

            return $this->insert($params);
        }

        public function setContentInfo($contentid, $contentInfo){

            return $this->update('id = ?', [intval($contentid)], $contentInfo);
        }
    }