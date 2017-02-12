<?php
    namespace App\M;

    class Actor extends Model{

        public static $table = 'Qualified_Actress';

     	public function getQualifiedActress($gender, $mintall, $maxtall, $city, $talent, $page, $pagesize){

     		$where = $param = [];

     		if($gender){

     			$where[] = 'Gender = ?';

     			$param[] = intval($gender);
     		}

     		if($mintall){

     			$where[] = 'Height >= ?';

     			$param[] = intval($mintall);
     		}

     		if($maxtall){

     			$where[] = 'Height <= ?';

     			$param[] = intval($maxtall);
     		}

     		if($city){

     			$where[] = 'WorkCity LIKE ?';

     			$param[] = "%" . addcslashes($city, '%_') . "%";
     		}

     		if($talent){

     			$where[] = 'Talent LIKE ?';

     			$param[] = '%' . addcslashes($talent, '%_') . '%';
     		}

     		if($where){

     			$this->where(implode(' AND ', $where), $param);
     		}

     		return $this->orderBy(['ID DESC'])->page($page)->pagesize($pagesize)->getRows();
     	} 

     	public function getTotalQualifiedActress($gender, $mintall, $maxtall, $city, $talent){

     		$where = $param = [];

     		if($gender){

     			$where[] = 'Gender = ?';

     			$param[] = intval($gender);
     		}

     		if($mintall){

     			$where[] = 'Height >= ?';

     			$param[] = intval($mintall);
     		}

     		if($maxtall){

     			$where[] = 'Height <= ?';

     			$param[] = intval($maxtall);
     		}

     		if($city){

     			$where[] = 'WorkCity LIKE ?';

     			$param[] = "%" . addcslashes($city, '%_') . "%";
     		}

     		if($talent){

     			$where[] = 'Talent LIKE ?';

     			$param[] = '%' . addcslashes($talent, '%_') . '%';
     		}

     		if($where){

     			$this->where(implode(' AND ', $where), $param);
     		}

     		return $this->getCount();
     	} 

     	public function searchActress($kw, $number){

     		return $this->where('NickName LIKE ?', ['%' . addcslashes($kw, '%_') . '%'])->pagesize($number)->getRows();
     	}  
    }