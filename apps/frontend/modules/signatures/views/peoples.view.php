<style type="text/css">
#people_table {font:12px Verdana,Arial; margin:25px auto; background:#EAEAEA <? /*url(/static/images/icons/bg.gif) repeat-x */?>;color:#091f30}
#hidden_th {display:none;}
.sortable {width:540px; border-left:1px solid #c6d5e1; border-top:1px solid #c6d5e1; border-bottom:none; margin:0 auto 15px}
.sortable th {background:#913D3E<?/*url(/static/images/icons/header-bg.gif) */?>; text-align:left; color:#fc6; border:1px solid #EAEAEA; border-right:none}
.sortable th {font-size:12px; padding:6px 8px 8px; text-align:center;}
.sortable td {padding:4px 6px 6px; border-bottom:1px solid #c6d5e1; border-right:1px solid #c6d5e1}
.sortable .head b {background:url(/static/images/icons/sort.gif) 7px center no-repeat; cursor:pointer; padding-left:18px}
.sortable .desc, .sortable .asc {background:#6f191C;<?/*url(/static/images/icons/header-selected-bg.gif)*/?>}
.sortable .desc b {background:url(/static/images/icons/desc.gif) 7px center no-repeat; cursor:pointer; padding-left:18px}
.sortable .asc b {background:url(/static/images/icons/asc.gif) 7px  center no-repeat; cursor:pointer; padding-left:18px}
.sortable .head:hover, .sortable .desc:hover, .sortable .asc:hover {color:#EAEAEA}
.sortable .evenrow td {background:#F7F7F7}
.sortable .oddrow td {background:#F2F2F2}
.sortable td.evenselected {background:#F2F2F2}
.sortable td.oddselected {background:#EAEAEA}

#controls {width:520px; margin:0 auto; font:10px Verdana,Arial;}
#perpage {float:left; width:200px}
#perpage select {float:left; font-size:11px}
#perpage span {float:left; margin:2px 0 0 5px}
#navigation {float:left; width:100px; text-align:center}
#navigation img {cursor:pointer}
#text {float:left; width:200px; text-align:right; margin-top:2px}
</style>
<div>
   <div class="mt15 ml15"><a href="/signatures" class="cbrown bold fs12">&larr; <?=t('К таблице по регионам')?></a></div>
<table cellpadding="0" cellspacing="0" border="0" id="people_table" class="sortable">
		<thead>
			<tr>
				<th><b>Активіст</b></th>
				<th class="desc"><b>Підписів</b></th>
				<th id="hidden_tr" class="hide" style="display:none;"></th>
			</tr>
		</thead>
                <tbody>
<? 
$users_signatures=db::get_rows('SELECT "user_id", sum("fact") AS "count" FROM "public"."user_desktop_signatures_fact" GROUP BY user_id ORDER BY count DESC');
foreach ($users_signatures as $key=>$user_signatures)
{
        ?>
    <tr>   
        <td><?=user_helper::full_name($user_signatures['user_id'], true, array() , false , true)?></td>
        <td><?=$user_signatures['count']?></td>
        <td id="hidden_tr" class="hide" style="display:none;"></td>
   </tr>
<? } ?>	
                </tbody>
        </table>
    
    <div class="bold mb5 aright" style="margin-right:110px;"><?=t('Всего подписей')?> &nbsp; &nbsp; <?=db::get_scalar('SELECT sum("fact") FROM "public"."user_desktop_signatures_fact"')?></div>
    </div>
	<div id="controls">
		<div id="perpage" class="ml15">
			<select onchange="sorter.size(this.value)">
			<option value="5">5</option>
				<option value="10">10</option>
				<option value="20" selected="selected">20</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
			<span><?=t('Записей на страницу')?></span>
		</div>
		<div id="navigation">

			<img src="/static/images/icons/first.gif" width="16" height="16" alt="<?=t('')?>" onclick="sorter.move(-1,true)" />
			<img src="/static/images/icons/previous.gif" width="16" height="16" alt="<?=t('')?>" onclick="sorter.move(-1)" />
			<img src="/static/images/icons/next.gif" width="16" height="16" alt="<?=t('')?>" onclick="sorter.move(1)" />
			<img src="/static/images/icons/last.gif" width="16" height="16" alt="<?=t('')?>" onclick="sorter.move(1,true)" />
		</div>
		<div id="text"><?=t('Показано страниц')?> <span id="currentpage"></span> of <span id="pagelimit"></span></div>
	</div>
<script type="text/javascript">
var TINY={};

function T$(i){return document.getElementById(i)}
function T$$(e,p){return p.getElementsByTagName(e)}

TINY.table=function(){
	function sorter(n){this.n=n; this.pagesize=20; this.paginate=0}
	sorter.prototype.init=function(e,f){
		var t=ge(e), i=0; this.e=e; this.l=t.r.length; t.a=[];
		t.h=T$$('thead',T$(e))[0].rows[0]; t.w=t.h.cells.length;
		for(i;i<t.w;i++){
			var c=t.h.cells[i];
			if(c.className!='nosort'){
				c.className=this.head; c.onclick=new Function(this.n+'.wk(this.cellIndex)')
			}
		}
		for(i=0;i<this.l;i++){t.a[i]={}}
		if(f!=null){var a=new Function(this.n+'.wk('+f+')'); a()}
		if(this.paginate){this.g=1; this.pages()}
	};
	sorter.prototype.wk=function(y){
		var t=ge(this.e), x=t.h.cells[y], i=0;
		for(i;i<this.l;i++){
      t.a[i].o=i; var v=t.r[i].cells[y]; t.r[i].style.display='';
      while(v.hasChildNodes()){v=v.firstChild}
      t.a[i].v=v.nodeValue?v.nodeValue:''
    }
		for(i=0;i<t.w;i++){var c=t.h.cells[i]; if(c.className!='nosort'){c.className=this.head}}
		if(t.p==y){t.a.reverse(); x.className=t.d?this.asc:this.desc; t.d=t.d?0:1}
		else{t.p=y; t.a.sort(cp); t.d=0; x.className=this.asc}
		var n=document.createElement('tbody');
		for(i=0;i<this.l;i++){
			var r=t.r[t.a[i].o].cloneNode(true); n.appendChild(r);
			r.className=i%2==0?this.even:this.odd; var cells=T$$('td',r);
			for(var z=0;z<t.w;z++){cells[z].className=y==z?i%2==0?this.evensel:this.oddsel:''}
		}
		t.replaceChild(n,t.b); if(this.paginate){this.size(this.pagesize)}
	};
	sorter.prototype.page=function(s){
		var t=ge(this.e), i=0, l=s+parseInt(this.pagesize);
		if(this.currentid&&this.limitid){T$(this.currentid).innerHTML=this.g}
		for(i;i<this.l;i++){t.r[i].style.display=i>=s&&i<l?'':'none'}
	};
	sorter.prototype.move=function(d,m){
		var s=d==1?(m?this.d:this.g+1):(m?1:this.g-1);
		if(s<=this.d&&s>0){this.g=s; this.page((s-1)*this.pagesize)}
	};
	sorter.prototype.size=function(s){
		this.pagesize=s; this.g=1; this.pages(); this.page(0);
		if(this.currentid&&this.limitid){T$(this.limitid).innerHTML=this.d}
	};
	sorter.prototype.pages=function(){this.d=Math.ceil(this.l/this.pagesize)};
	function ge(e){var t=T$(e); t.b=T$$('tbody',t)[0]; t.r=t.b.rows; return t};
	function cp(f,c){
		var g,h; f=g=f.v.toLowerCase(), c=h=c.v.toLowerCase();
		var i=parseFloat(f.replace(/(\$|\,)/g,'')), n=parseFloat(c.replace(/(\$|\,)/g,''));
		if(!isNaN(i)&&!isNaN(n)){g=i,h=n}
		i=Date.parse(f); n=Date.parse(c);
		if(!isNaN(i)&&!isNaN(n)){g=i; h=n}
		return g>h?1:(g<h?-1:0)
	};
	return{sorter:sorter}
}();
  var sorter = new TINY.table.sorter("sorter");
	sorter.head = "head";
	sorter.desc = "desc";
	sorter.asc = "asc";
	sorter.even = "evenrow";
	sorter.odd = "oddrow";
	sorter.evensel = "evenselected";
	sorter.oddsel = "oddselected";
	sorter.paginate = true;
	sorter.currentid = "currentpage";
	sorter.limitid = "pagelimit";
	sorter.init("people_table",2);

</script>