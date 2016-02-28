<?php
/**
 * ajax分页类
 */
class PaginationAjax {
	private $total; //数据表中总记录数
		private $listRows; //每页显示行数
		private $limit;
		private $pageTxt;//GET名
		private $jsName;//ajax分页的js函数名
		private $uri;
		private $pageNum; //页数
		private $config=array('header'=>"", "prev"=>"<", "next"=>">", "first"=>"首 页", "last"=>"尾 页");
		private $listNum=7;

		/**
		 * @param int    $total		数据总条数
		 * @param int    $listRows	偏移量
		 * @param string $pageTxt	页码变量名
		 * @param string $pageUrl	请求地址路径 相对路径
		 * @param string $jsName	分页JS函数名
         * @param string $type		参数请求方式
         */
		function __construct($total, $listRows=30,$pageTxt='page',$pageUrl="",$jsName="html",$type='GET'){
			$this->total=$total;
			$this->listRows=$listRows;
			$this->pageTxt=$pageTxt;
			$this->jsName=$jsName;
			$this->uri=$this->getUri($pageUrl);
			if($type=='GET'){
				$this->page=!empty($_GET[$pageTxt]) ? $_GET[$pageTxt] : 1;
			}elseif($type=='POST'){
				$this->page=!empty($_POST[$pageTxt]) ? $_POST[$pageTxt] : 1;
			}
			
			$this->pageNum=ceil($this->total/$this->listRows);
			$this->limit=$this->setLimit();
		}

		private function setLimit(){
			return "Limit ".($this->page-1)*$this->listRows.", {$this->listRows}";
		}

		private function getUri($pageUrl){
			$url=$_SERVER["REQUEST_URI"].(strpos($_SERVER["REQUEST_URI"], '?')?'':"?").$pageUrl;
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
				$html.="<a href='javascript:void(0)'  onclick=\"".$this->jsName."('1')\" class='btn btn-mini'>{$this->config["first"]}</a>";
			return $html;
		}

		private function prev(){
			if($this->page==1)
			$html.="<li class=\"prev disabled\"><a href='javascript:void(0)'  >{$this->config["prev"]}</a></li>";
		else
			$html.="<li class=\"prev \"><a href='javascript:void(0)'  onclick=\"".$this->jsName."('".($this->page-1)."')\" >{$this->config["prev"]}</a></li>";
			return $html;
		}

		private function pageList(){
			$linkPage="";
			$inum=floor($this->listNum/2);
			for($i=$inum; $i>=1; $i--){
				$page=$this->page-$i;
				if($page<1)
					continue;
				$linkPage.="<li><a href='javascript:void(0)'  onclick=\"".$this->jsName."('".$page."')\" >{$page}</a></li>";
			}
		
			$linkPage.="<li class=\"active\"><a href=\"javascript:void(0)\">{$this->page}</a></li>";

			for($i=1; $i<=$inum; $i++){
				$page=$this->page+$i;
				if($page<=$this->pageNum)
					$linkPage.="<li><a href='javascript:void(0)'  onclick=\"".$this->jsName."('".$page."')\" >{$page}</a></li>";
				else
					break;
			}

			return $linkPage;
		}

		private function next(){
			if($this->page==$this->pageNum || $this->page>$this->pageNum)
			$html.=" <li class=\"next disabled \"><a href='javascript:void(0)'   >{$this->config["next"]}</a></li>";
		else
			$html.=" <li class=\"next\"><a href='javascript:void(0)'  onclick=\"".$this->jsName."('".($this->page+1)."');\" >{$this->config["next"]}</a></li>";

			return $html;
		}

		private function last(){
			if($this->page==$this->pageNum  || $this->page>$this->pageNum)
				$html.='';
			else
				$html.="<a href='javascript:void(0)' onclick=\"".$this->jsName."('".($this->pageNum)."')\" class='btn btn-mini'>{$this->config["last"]}</a>";

			return $html;
		}

		private function goPage(){
			//return '&nbsp;&nbsp;<input class="skipInput" type="text" style="height:12px; width:20px; position:relative; top:5px;" onkeydown="javascript:if(event.keyCode==13){var '.$this->pageTxt.'=(this.value>'.$this->pageNum.')?'.$this->pageNum.':this.value;location=\''.$this->uri.'&'.$this->pageTxt.'=\'+'.$this->pageTxt.'+\'\'}" value="'.$this->page.'" style="width:25px"><input type="button" class="btn btn-success btn-mini" style="height:22px;" value="跳转" onclick="javascript:var '.$this->pageTxt.'=(this.previousSibling.value>'.$this->pageNum.')?'.$this->pageNum.':this.previousSibling.value;location=\''.$this->uri.'&'.$this->pageTxt.'=\'+'.$this->pageTxt.'+\'\'">';
			return '&nbsp;&nbsp;<input class="skipInput" type="text" style="height:32px; top:-1px;  top:0px; margin-right:5px; margin-left:-6px; line-height:30px; width:40px; -moz-border-radius:3px!important; -webkit-border-radius:3px!important;  border-radius:3px!important;" onkeydown="javascript:if(event.keyCode==13){var '.$this->pageTxt.'=(this.value>'.$this->pageNum.')?'.$this->jsName.'('.$this->pageNum.'):'.$this->jsName.'(this.previousSibling.value);}" value="'.$this->page.'" style="width:25px"><input type="button" class="btn btn-success" style="height:30px; margin-right:0px; font-size:10.5px; vertical-align:top;" value="跳转"  onclick="javascript:var '.$this->pageTxt.'=(this.previousSibling.value>'.$this->pageNum.')?'.$this->jsName.'('.$this->pageNum.'):'.$this->jsName.'(this.previousSibling.value)">';
		}
		

		function fpage($display=array(4,5,6)){
			//$html[0]="&nbsp;&nbsp;<span class='btn btn-mini' style='margin-right:0px;'>共{$this->total}{$this->config["header"]}</span>&nbsp;&nbsp;";
			//$html[1]="&nbsp;&nbsp;每页显示<b class='btn btn-mini'>".($this->end()-$this->start()+1)."</b>条，本页< b class='btn btn-mini'>{$this->start()}-{$this->end()}</b>条&nbsp;&nbsp;";
			//$html[2]="&nbsp;&nbsp;<b class='btn btn-mini'>{$this->page}/{$this->pageNum}</b>页&nbsp;&nbsp;";

			//$html[3]=$this->first(); //首页

			$html[4]=$this->prev(); //上一页
			$html[5]=$this->pageList();//页码
			$html[6]=$this->next();//下一页

			//$html[7]=$this->last();//最后一页
			//$html[8]=$this->goPage();//跳转
			$fpage='';

			foreach($display as $index){
				$fpage.=$html[$index];
			}

			$page_head='<div class="sui-pagination"><ul>';
			$page_foot='</ul></div>';
			return $page_head.$fpage.$page_foot;
		}
	}



