<?php
    namespace App\Helper;

    class Enum{

        const Status = [
						'all'     => 0, //全部
						'normal'  => 1, //正常
						'disable' => 2, //禁用
        				];

        const Position = [
        					'首页'   => 1,
                            '详情页' => 2,
                            '活动页' => 3,
        				 ];
    }