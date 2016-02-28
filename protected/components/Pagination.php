<?php
	class Pagination {
	private $total; //数据表中总记录数
		private $listRows; //每页显示行数
		private $limit;
		private $uri;
		private $pageNum; //页数
		private $config=array('header'=>"", "prev"=>"<", "next"=>">", "first"=>"首 页", "last"=>"尾 页");
		private $listNum=3;
		/*
		 * $total 
		 * $listRows
		 */
		public function __construct($total, $listRows=30, $pa=""){
			$this->total=$total;
			$this->listRows=$listRows;
			$this->uri=$this->getUri($pa);
			$this->page=!empty($_GET["page"]) ? $_GET["page"] : 1;
			$this->pageNum=ceil($this->total/$this->listRows);
			$this->limit=$this->setLimit();
		}

		private function setLimit(){
			return "Limit ".($this->page-1)*$this->listRows.", {$this->listRows}";
		}

		private function getUri($pa){
			$url=$_SERVER["REQUEST_URI"].(strpos($_SERVER["REQUEST_URI"], '?')?'':"?").$pa;
			$parse=parse_url($url);

		

			if(isset($parse["query"])){
				parse_str($parse['query'],$params);
				unset($params["page"]);
				$url=$parse['path'].'?'.http_build_query($params);
				
			}

			return $url;
		}

		function __get($args){
			if($args=="limit")
				return $this->limit;
			else
				return null;
		}

		private function start(){
			if($this->total==0)
				return 0;
			else
				return ($this->page-1)*$this->listRows+1;
		}

		private function end(){
			return min($this->page*$this->listRows,$this->total);
		}

		private function first(){
			if($this->page==1)
				$html.='';
			else
				$html.="<a href='{$this->uri}&page=1' class='btn btn-mini'>{$this->config["first"]}</a>";

			return $html;
		}

		private function prev(){
			if($this->page==1)
				$html.='';
			else
				$html.="<a href='{$this->uri}&page=".($this->page-1)."' class='btn btn-mini'>{$this->config["prev"]}</a>";

			return $html;
		}

		private function pageList(){
			$linkPage="";
			$inum=floor($this->listNum/2);
			for($i=$inum; $i>=1; $i--){
				$page=$this->page-$i;
				if($page<1)
					continue;
				$linkPage.="<a href='{$this->uri}&page={$page}' class='btn btn-mini'>{$page}</a>";
			}
		
			$linkPage.="<span class='btn btn-mini btn-success'>{$this->page}</span>";
			

			for($i=1; $i<=$inum; $i++){
				$page=$this->page+$i;
				if($page<=$this->pageNum)
					$linkPage.="<a href='{$this->uri}&page={$page}' class='btn btn-mini'>{$page}</a>";
				else
					break;
			}

			return $linkPage;
		}

		private function next(){
			
			if($this->page==$this->pageNum  || $this->page>$this->pageNum)
				$html.='';
			else
				$html.="<a href='{$this->uri}&page=".($this->page+1)."' class='btn btn-mini'>{$this->config["next"]}</a>";

			return $html;
		}

		private function last(){
			if($this->page==$this->pageNum  || $this->page>$this->pageNum)
				$html.='';
			else
				$html.="<a href='{$this->uri}&page=".($this->pageNum)."' class='btn btn-mini'>{$this->config["last"]}</a>";

			return $html;
		}

		private function goPage(){
			
			return '&nbsp;&nbsp;<input class="skipInput" type="text" style="height:32px; top:-1px; margin-right:5px; margin-left:-6px; line-height:30px; width:40px; -moz-border-radius:3px!important; -webkit-border-radius:3px!important;  border-radius:3px!important;" onkeydown="javascript:if(event.keyCode==13){var page=(this.value>'.$this->pageNum.')?'.$this->pageNum.':this.value;location=\''.$this->uri.'&page=\'+page+\'\'}" value="'.$this->page.'" style="width:30px"><input type="button" class="btn btn-success" style="height:30px; margin-right:0px; font-size:10.5px; vertical-align:top;" value="跳转" onclick="javascript:var page=(this.previousSibling.value>'.$this->pageNum.')?'.$this->pageNum.':this.previousSibling.value;location=\''.$this->uri.'&page=\'+page+\'\'">';
		}
		
		
		
		
		function fpage($display=array(0,1,2,3,4,5,6,7,8)){
			$html[0]="&nbsp;&nbsp;<span class='btn btn-mini' style='margin-right:0px;'>共{$this->total}{$this->config["header"]}</span>&nbsp;&nbsp;";
			/*$html[1]="&nbsp;&nbsp;每页显示<b class='btn btn-mini'>".($this->end()-$this->start()+1)."</b>条，本页< b class='btn btn-mini'>{$this->start()}-{$this->end()}</b>条&nbsp;&nbsp;";
			$html[2]="&nbsp;&nbsp;<b class='btn btn-mini'>{$this->page}/{$this->pageNum}</b>页&nbsp;&nbsp;";*/
			
			
			//$html[3]=$this->first(); //首页
			$html[4]=$this->prev(); //上一页
			$html[5]=$this->pageList();//页码
			$html[6]=$this->next();//下一页
			//$html[7]=$this->last();//最后一页
			$html[8]=$this->goPage();//跳转
			$fpage='';
			foreach($display as $index){
				$fpage.=$html[$index];
			}
			return $fpage;
		}
	}
