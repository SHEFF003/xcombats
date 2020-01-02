
function ImagePreloader(images,call_back)
{this.call_back=call_back;this.nLoaded=0;this.nProcessed=0;this.aImages=[];this.nImages=images.length;for(var i=0;i<images.length;i++)
this.preload(images[i]);}
ImagePreloader.prototype.preload=function(image)
{var oImage=new Image;this.aImages.push(oImage);oImage.onload=ImagePreloader.prototype.onload;oImage.onerror=ImagePreloader.prototype.onerror;oImage.onabort=ImagePreloader.prototype.onabort;oImage.oImagePreloader=this;oImage.bLoaded=false;oImage.src=image;}
ImagePreloader.prototype.onComplete=function()
{this.nProcessed++;if(this.nProcessed==this.nImages)
{if(this.call_back!=null)
{this.call_back(this.aImages,this.nLoaded);}}}
ImagePreloader.prototype.onload=function()
{this.bLoaded=true;this.oImagePreloader.nLoaded++;this.oImagePreloader.onComplete();}
ImagePreloader.prototype.onerror=function()
{this.bError=true;this.oImagePreloader.onComplete();}
ImagePreloader.prototype.onabort=function()
{this.bAbort=true;this.oImagePreloader.onComplete();}
function KnownDungeonEntender(d)
{var rendered=false;d.divId='dungeon_div_'+d.Name;d.floorTabsId='dungeon_floor_tabs_'+d.Name;d.mapLookId='dungeon_'+d.Name+'_mapLook';d.zoomerId='dungeon_'+d.Name+'_zoomer';d.activeFloorIndex=0;d.rotation=0;d.imagesToBePreloaded=[];var scriptsToLoadCount=0;d.innerDataLoaded=function()
{this.data.ui=this;this.IsDungeon=this.data.IsDungeon;if(this.IsDungeon)
{this.createDescriptionPage();this.createSettingsPage();this.prepareJobPage();}
else
{this.data.Floors.push(new CustomFloor(this.data,0,'$customDesc',this.Description,this.NewLineToBR));}
this.createShopPages();if(this.imagesToBePreloaded.length>0)
{this.preloader=new ImagePreloader(this.imagesToBePreloaded,null);}}
d.dataLoaded=function(f)
{this.data=underground.loadedDungeons[this.Name];var scriptsLoaded=function()
{d.innerDataLoaded();f();}
if(underground.offline)
{scriptsLoaded();return;}
var scriptLoaded=function()
{scriptsToLoadCount--;if(scriptsToLoadCount==0)
{scriptsLoaded(f);}}
var scripts=[];for(var i in this.data.RequiredScriptNames)
{var sn=this.data.RequiredScriptNames[i];if(!(sn in underground.loadedScripts))
{scripts.push(underground.settings.jsBase+sn);}}
scriptsToLoadCount=scripts.length;if(scriptsToLoadCount>0)
{for(var i in scripts)
{var sn=scripts[i];loadScript(sn,function(){underground.loadedScripts[sn]=sn;scriptLoaded();});}}
else
{scriptsLoaded();}}
d.loadData=function(f)
{if(underground.offline)
{this.dataLoaded(f);}
else
{loadScript(underground.settings.jsBase+'dungeon.city.'+this.Name+'.js',function(){d.dataLoaded(f);});}}
d.createDescriptionPage=function()
{var p={Name:'$description',Caption:'Описание',NewLineToBR:this.NewLineToBR,ExternalUrl:'',hidden:true};p.Description=this.Description;this.data.Pages.push(p);this.descriptionPageIndex=this.data.Pages.length-1;}
d.createSettingsPage=function()
{}
d.createShopPages=function()
{for(var i in this.data.Shops)
{this.createShopPage(this.data.Shops[i]);}}
d.getShopItemHtml=function(item,imageBasePath)
{var img=[imageBasePath,item.Name,'.gif'].join('');this.imagesToBePreloaded.push(img);var html=['<tr><td width="',item.W,'">','<img lowsrc="',underground.settings.loadingImageUrl,'" src="',img,'" border="0" width="',item.W,'" height="',item.H,'" />','</td><td><nobr><b>',item.Caption,'</b>'];if(item.Binding>0)
{html.push(' <img src="http://img.combats.ru/i/destiny.gif" width="16" height="18" border="0" alt=""');this.imagesToBePreloaded.push('http://img.combats.ru/i/destiny.gif');switch(item.Binding)
{case 1:html.push(' style="filter: invert();" title="Этот предмет будет связан общей судьбой с первым, кто получит его. Никто другой не сможет его использовать."');break;case 2:html.push(' style="filter: gray();" title="Этот предмет будет связан общей судьбой с первым, кто оденет его. Никто другой не сможет его использовать."');break;case 3:html.push(' title="Этот предмет связан общей судьбой."');break;}
html.push(' />');}
if(item.IsGift)
{this.imagesToBePreloaded.push('http://img.combats.ru/i/podarok.gif');html.push(' <img src="http://img.combats.ru/i/podarok.gif" width="16" height="18" border="0" alt="" title="Подарок" />');}
html=html.concat(['</nobr><br />',item.Description.join(item.NewLineToBR?'<br />':''),'</td></tr>']);return html.join('');}
var showShopPage=function()
{var shop=this.Shop;shop.tdId='$shoptd$'+this.Dungeon.Name+'$'+shop.Name;var firstCat=true;var data=[];var chapters=['<div><ul>'];for(var i in shop.Categories)
{var cat=shop.Categories[i];cat.divId='$shopcat$'+this.Dungeon.Name+'$'+shop.Name+'$'+cat.Name;var st=firstCat?'':'display: none;';data=data.concat(['<div id="',cat.divId,'" style="',st,'"><div>','<table width="560" class="proshka"><tr><th colspan="2">']);var title=[cat.Caption];if(cat.Description.length>0)
{title=title.concat(['<div style="font-size: 80%; font-weight: normal; border-top: 1px dashed #999; padding: 2px; margin: 0;">',cat.Description.join('<br />'),'</div>']);}
data=data.concat(title);data.push('</th></tr>');for(var j in cat.Items)
{data.push(this.Dungeon.getShopItemHtml(cat.Items[j],shop.ImageBasePath));}
data.push('</table></div></div>');chapters=chapters.concat(['<li><a class="fblike" style="display: block; width: 256px;" href="javascript:;" onclick="ui_changeShopCategory(\'',shop.tdId,'\', \'',cat.divId,'\')"><nobr>',cat.Caption,'</nobr></a></li>']);firstCat=false;}
chapters.push('</ul></div>');var html=[shop.Description.join(shop.NewLineToBR?'<br />':''),'<hr />','<table class="hintview" cellpadding="4" width="100%"><tr><td valign="top">'];html=html.concat(chapters);html=html.concat(['</td><td valign="top" id="',shop.tdId,'">']);html=html.concat(data);html.push('</td></tr></table>');this.Description=html;}
d.createShopPage=function(shop)
{var sp={Name:'$shop$'+shop.Name,Caption:shop.Caption,NewLineToBR:false,ExternalUrl:'',Description:[],OnShow:showShopPage,Dungeon:this,Shop:shop};this.data.Pages.push(sp);}
var getCollectiveJobData=function(job)
{var title=['<b>',job.Caption,'</b>'].join('');var desc=job.Description.join('<br />');var image=job.ImageUrl;var bonus=job.ItemReward;var count=job.DefCount;var maxFactor=1.25;return{title:title,desc:desc,image:image,bonus:bonus,count:count,maxFactor:maxFactor,iw:60,ih:60};}
var getCapturedJobData=function(job)
{var title=['<b>',job.Caption,'</b>'].join('');var desc=job.Description.join('<br />');var image=job.ImageUrl;var bonus=job.ItemReward;var count=job.DefCount;var maxFactor=1;return{title:title,desc:desc,image:image,bonus:bonus,count:count,maxFactor:maxFactor,iw:60,ih:60};}
var getKillingJobData=function(job)
{var mob=underground.mobs[job.MobName];var align=job.Unique?9:0;var title=['<b><img width="12" height="15" border="0" src="http://img.combats.ru/i/align',align,'.gif" />',mob.Caption,'</b>'].join('');var desc=job.Description.join('<br />');var image=mob.ImageUrl;if(msie&&!msie7)
{image=replacestr(image,'/chars/d/','/chars/');image=replacestr(image,'.png','.gif');}
var bonus=(mob.Instances.length==1)?mob.Instances[0].Reward:0;var count=job.Unique?1:job.DefCount;var maxFactor=1;return{title:title,desc:desc,image:image,bonus:bonus,count:count,maxFactor:maxFactor,mob:mob,iw:60,ih:110};}
var jobDataProviders=[getCollectiveJobData,getCapturedJobData,getKillingJobData];d.getJobHtml=function(i,job,catIndex)
{var data=jobDataProviders[catIndex](job);job.divId=['$job$',d.Name,'$cat_',catIndex,'$index_',i].join('');job.descDivId=job.divId+'$desc';job.formDivId=job.divId+'$form';this.imagesToBePreloaded.push(data.image);var html=['<div>',data.title,'<blockquote><div class="incqbox" id="',job.descDivId,'"><table cellspacing="8" cellpadding="0" border="0" width="100%"><tr><td valign="top"',' width="',data.iw,'" valign="top"><img lowsrc="',underground.settings.loadingImageUrl,'"',underground.pngClsAttr,' border="0" src="',data.image,'" width="',data.iw,'" height="',data.ih,'" />','</td><td valign="top"><div style="padding-bottom: 4px;">',data.desc,'</div>','<div class="incqbox" id="',job.formDivId,'">'];if('mob'in data)
{var rewardid=job.divId+'$reward';var handler=['setTimeout(&quot;ui_recalculateMobReward(\'',rewardid,'\''];var lastmii=0;for(var mii=0;mii<data.mob.Instances.length;mii++)
{var mi=data.mob.Instances[mii];if(mi.Reward==0)continue;var countid=job.divId+'$i'+mii+'$count';handler=handler.concat([', \'',countid,'\', ',mi.Reward]);lastmii=mii;}
handler.push(')&quot;, 10)');handler=handler.join('');html.push('<table cellspacing="8" cellpadding="0" border="0"><tr><td valign="top">');html.push('<table cellspacing="0" cellpadding="0" border="0">');for(var mii=0;mii<data.mob.Instances.length;mii++)
{var mi=data.mob.Instances[mii];if(mi.Reward==0)continue;var countid=[job.divId,'$i',mii,'$count'].join('');html=html.concat(['<tr><td valign="top"><label for="',countid,'"><b>[',mi.Level,']</b></label>: </td><td valign="top">']);if(job.Unique)
{html=html.concat(['<b id="',countid,'">1</b>']);}
else
{html=html.concat(['<input class="inpText" type="text" size="4" maxlength="3" id="',countid,'" onfocus="document.getElementById(\'',countid,'\').select();"',' onkeypress="',handler,'" onchange="',handler,'" value="',((mii==lastmii)?job.DefCount:0),'" />']);}
html.push(' шт.</td></tr>');}
html.push('</table></td><td valign="center">');html.push('Вы получите <span id="');html.push(rewardid);html.push('">');html.push(job.Unique?['<b>',data.mob.Instances[0].Reward,'</b>'].join(''):(job.DefCount*data.mob.Instances[lastmii].Reward));html.push('</span> ед. награды.');html.push('</td></tr></table>');if(job.Unique)
{html.push('<br /><span style="color: gray">Уникальное задание, выпадает не более одного раза за круг.</span>');}}
else
{var countid=job.divId+'$count';var rewardid=job.divId+'$reward';var maxcountid=job.divId+'$maxcount';var maxrewardid=job.divId+'$maxreward';var handler=['setTimeout(&quot;ui_recalculateUsualReward(\'',countid,'\', \'',rewardid,'\', \'',maxcountid,'\', ',data.bonus,', ',data.maxFactor,', \'',maxrewardid,'\')&quot, 10)'].join('');html=html.concat(['За <input class="inpText" type="text" size="4" maxlength="3" id="',countid,'" onfocus="document.getElementById(\'',countid,'\').select();"',' value="',job.DefCount,'" onkeypress="',handler,'" onchange="',handler,'" /> шт. Вы получите <span id="',rewardid,'">',job.DefCount*data.bonus,'</span> ед. награды.']);if(data.maxFactor>1)
{var maxc=Math.floor(job.DefCount*data.maxFactor);html=html.concat(['<br />Можно перевыполнить задание, собрав до <span id="',maxcountid,'">',maxc,'</span> шт., и получив до <span id="',maxrewardid,'">',maxc*data.bonus,'</span> ед. награды.']);}}
html.push('</div></td></tr></table></div></blockquote></div>');return html.join('');}
var jobPageShow=function()
{var jobs=this.Dungeon.data.Jobs;this.jobTdId='$jobtd$'+this.Dungeon.Name+'$jobs';var html=[];this.jobCats=[{Name:'CollectiveJobs',Caption:'Собирательные задания',Items:jobs.CollectiveJobs},{Name:'CapturedJobs',Caption:'Задания на трофеи',Items:jobs.CapturedJobs},{Name:'KillingJobs',Caption:'Задания на убийство',Items:jobs.KillingJobs}];html.push('<h3>Задания</h3>');html.push('<hr />');var firstCat=true;var chapters=[];var data=[];chapters.push('<div><ul>');for(var i in this.jobCats)
{var cat=this.jobCats[i];if(cat.Items.length==0)continue;cat.divId='$jobcat$'+this.Name+'$jobs$'+cat.Name;var st=firstCat?'':'display: none;';data.push('<div id="');data.push(cat.divId);data.push('" style="');data.push(st);data.push('">');data.push('<h3>');data.push(cat.Caption);data.push('</h3>');data.push('<hr class="dashed" />');for(var j in cat.Items)
{data.push(this.Dungeon.getJobHtml(j,cat.Items[j],i));}
data.push('</div>');chapters.push('<li><a class="fblike" style="display: block; width: 256px;" href="javascript:;" onclick="ui_changeJobsCategory(\'');chapters.push(this.jobTdId);chapters.push('\', \'');chapters.push(cat.divId);chapters.push('\')"><nobr>');chapters.push(cat.Caption);chapters.push('</nobr></a></li>');firstCat=false;}
chapters.push('</ul></div>');html.push('<table class="hintview" cellpadding="4" width="100%"><tr><td valign="top">');html.push(chapters.join(''));html.push('</td><td valign="top" id="');html.push(this.jobTdId);html.push('">');html.push(data.join(''));html.push('</td></tr></table>');this.Description=html;}
d.prepareJobPage=function()
{var jobs=this.data.Jobs;if(jobs.CollectiveJobs.length==0&&jobs.CapturedJobs.length==0&&jobs.KillingJobs.length==0)
{return;}
var sp={Name:'$jobs$',Dungeon:this,Caption:'Задания',NewLineToBR:false,ExternalUrl:'',Description:[],OnShow:jobPageShow};this.data.Pages.push(sp);}
d.getFloorTabsHtml=function()
{if(!this.IsDungeon)return'';var html=[];html.push('<li><a onclick="underground.activeDungeon.openDescription()" href="javascript:;">Описание</a></li>');html.push('<li><span style="float:left;padding: 0px 10px;color: #666; margin: 1px;">|</span></li>');for(var i in this.data.Floors)
{var floor=this.data.Floors[i];html.push('<li');if(i==this.activeFloorIndex)html.push(' class="activeLink"');html.push('><a onclick="underground.changeFloor(');html.push(i);html.push(')" href="javascript:;">');html.push(floor.Caption);html.push('</a></li>');}
html.push('<li><span style="float:left;padding: 0px 10px;color: #666; margin: 1px;">|</span></li>');if(msie)html.push(this.getZoomText());html.push(this.getLookText());return html.join('');}
d.openDescription=function()
{this.openPage(this.descriptionPageIndex);}
d.openSettings=function()
{}
d.getStatistics=function()
{var r={};for(var i in this.data.Floors)
{var floor=this.data.Floors[i];var fr=floor.getStatistics();for(var n in fr)
{if(!(n in r))
{r[n]={};}
var frd=fr[n];for(var level in frd)
{if(!(level in r[n]))
{r[n][level]=0;}
r[n][level]+=frd[level];}}}
return r;}
d.getStatisticsHtml=function()
{if(!this.IsDungeon)return'';var stats=this.getStatistics();var html=[];html.push(getStatisticsHtml('Статистика по подземелью',stats,''));return html.join('');}
d.needData=function(f)
{if(!('data'in this))
{this.loadData(f);}
else
{f();}}
d.hidePages=function()
{for(var i in this.data.Pages)
{var page=this.data.Pages[i];var e=document.getElementById(page.divId);if(e!=null)
{e.style.display='none';}}
document.getElementById('ads').style.display='';}
d.getPageDivCloserHtml=function()
{var html=[];html.push('<div class="dtab" style="float: right; width: 100%;"><ul class="dtab">');html.push('<li><a href="javascript:;" onclick="underground.hideDungeonPages()">Закрыть документ</a></li>');html.push('</ul></div>');return html.join('');}
d.getNewPageDivHtml=function(id,content)
{var html=[];var closer=this.getPageDivCloserHtml();html.push('<div class="dungeonPage" id="');html.push(id);if(msie7)
{html.push('" style="filter: alpha(opacity = 94, style = 4) progid:DXImageTransform.Microsoft.Shadow(color=\'#666666\', Direction=135, Strength=2);');}
html.push('">');html.push(closer);html.push(content);html.push(closer);html.push('</div>');return html.join('');}
d.getChapterHtml=function()
{var html=[];rendered=true;this.handleHash2();underground.settings.look=this.data.Floors[this.activeFloorIndex].LookDirection;html.push('<div style="position: relative; width: 100%;" id="');html.push(this.divId);html.push('">');html.push('<h4>');html.push(d.Caption);html.push('</h4>');html.push('<div class="dtab" style="background-color: #e6e6e6;"><ul id="');html.push(this.floorTabsId);html.push('" class="dtab">');html.push(this.getFloorTabsHtml());html.push('</ul>');html.push('</div>');html.push('<div>');html.push(this.data.getMapHtml());html.push('</div>');html.push('<div>');html.push(this.getStatisticsHtml());html.push('</div>');html.push('</div>');return html.join('');}
d.getZoomText=function()
{var html=[];html.push('<li>Масштаб: <input id="');html.push(this.zoomerId);html.push('" type="text" maxlength="3" size="3" class="tool" style="text-align: right;" value="');html.push(underground.settings.zoom);html.push('" onfocus="this.select()" onchange="ui_zoomChanged()" onkeypress="ui_zoomChanged()" />%</li>');return html.join('');}
d.changeFloor=function(floorIndex)
{hideMenu();hidePopup2();var oldActiveFloorIndex=this.activeFloorIndex;this.activeFloorIndex=floorIndex;if(!rendered)return;this.data.Floors[oldActiveFloorIndex].highlightPoint(null);underground.settings.look=this.data.Floors[floorIndex].LookDirection;document.getElementById(this.mapLookId).value=underground.settings.lookTexts[underground.settings.look];document.getElementById(this.floorTabsId).innerHTML=this.getFloorTabsHtml();for(var i in this.data.Floors)
{var floor=this.data.Floors[i];var dm=(this.activeFloorIndex==i)?'':'none';document.getElementById(floor.mapId).style.display=dm;}}
d.checkStatsMob=function(mobName,mobLevel)
{this.data.Floors[this.activeFloorIndex].checkStatsMob(mobName,mobLevel);}
d.afterConstruction=function()
{this.data.afterConstruction();hideHTextsOf(document.getElementById(this.divId));document.getElementById('ads').style.display='';}
d.getHash=function()
{var hash=this.Name;hash+='.'+(this.activeFloorIndex+1);if('data'in this)
{hash+=this.data.Floors[this.activeFloorIndex].getHash();}
return hash;}
var doNothing=function()
{}
d.handleHash2=doNothing;d.handleHash=function(suffix)
{var floorPart=suffix;var dotIndex=floorPart.indexOf('&');if(dotIndex>=0)
{floorPart=floorPart.substr(0,dotIndex);}
var floorIndex=parseInt(floorPart)-1;if(floorIndex>=0&&floorIndex<this.FloorNames.length)
{this.activeFloorIndex=floorIndex;}
var fhash=suffix.substr(dotIndex+1);this.handleHash2=function()
{this.data.Floors[this.activeFloorIndex].handleHash(fhash);this.handleHash2=doNothing;}
if('data'in this)
{this.handleHash2();}}
d.getUndergroundTabsHtml=function()
{var html=[];for(var i in this.data.Pages)
{var page=this.data.Pages[i];page.divId=this.divId+'_page'+i;if('hidden'in page)continue;html.push('<li><a href="javascript:;" onclick="underground.activeDungeon.openPage(');html.push(i);html.push(')">');html.push(page.Caption);html.push('</a></li>');}
return html.join('');}
d.pagePreloaded=function(content)
{this.imagesToBePreloaded=[];var basee=document.getElementById(this.divId);if(basee.insertAdjacentHTML)
{basee.insertAdjacentHTML('beforeEnd',content);}
else
{basee.innerHTML=basee.innerHTML+content;}}
d.openPage=function(pageIndex)
{this.hidePages();this.imagesToBePreloaded=[];document.getElementById('ads').style.display='none';if(pageIndex<0)return;var page=this.data.Pages[pageIndex];var e=document.getElementById(page.divId);if(e==null)
{var sep=(page.NewLineToBR)?'<br />':'';if('OnShow'in page)
{page.OnShow();}
var content=page.Description.join(sep);if(content==''&&page.ExternalUrl!='')
{content=loadXMLDoc2(page.ExternalUrl);}
content=this.getNewPageDivHtml(page.divId,content);if(msie&&!msie7&&this.imagesToBePreloaded.length>0)
{this.preloader=new ImagePreloader(this.imagesToBePreloaded,function(){d.pagePreloaded(content);});}
else
{d.pagePreloaded(content);}
return;}
e.style.display='';}
d.getCellContentMenuHtml=function(cellDivId,x,y)
{return this.data.Floors[this.activeFloorIndex].getCellContentMenuHtml(cellDivId,x,y);}
d.hotSpot=function(cellDivId)
{this.data.Floors[this.activeFloorIndex].hotSpot(cellDivId);}
d.hideHotSpots=function()
{if(this.IsDungeon&&('data'in this))
{this.data.Floors[this.activeFloorIndex].hideHotSpots();}}
d.getLookOption=function(index,caption)
{var html=[];html.push('<option value="');html.push(index);html.push('"');if(index==underground.settings.look)
{html.push(' selected="yes"');}
html.push('>');html.push(caption);html.push('</option>');return html.join('');}
d.getLookText=function()
{if(!this.IsDungeon)return'';var html=[];html.push('<li>Смотрим на <input class="tool" id="');html.push(this.mapLookId);html.push('" disabled="yes" size="6" maxlength="6" value="');html.push(underground.settings.lookTexts[underground.settings.look]);html.push('" >');html.push('</span></li>');return html.join('');}
d.getProposedZoom=function()
{var v=document.getElementById(this.zoomerId).value;var pv=underground.settings.zoom;if(!isNaN(v))
{pv=parseInt(v);if(isNaN(pv))pv=underground.settings.zoom;if(pv<10)pv=10;if(pv>300)pv=300;}
if(pv!=v)document.getElementById(this.zoomerId).value=pv;return pv;}
d.applyZoom=function(zoomFactor)
{if(!('data'in this))return;document.getElementById(this.zoomerId).value=zoomFactor;for(var i in this.data.Floors)
{this.data.Floors[i].applyZoom(zoomFactor);}}
d.highlightPoint=function(point)
{this.data.Floors[this.activeFloorIndex].highlightPoint(point);}}
function FloorExtender(d,i,f)
{f.dungeon=d;f.index=i;var baseId='dungeon_'+d.Name+'_floor_'+f.Name+'_';f.mapId=baseId+'map';f.mapViewId=baseId+'mapView';f.xaxisId=baseId+'xaxis';f.yaxisId=baseId+'yaxis';f.cellPrefix=baseId+'cell';f.statisticsCheckId=baseId+'statsCheck';f.highlightPointId=baseId+'highlightPoint';f.rightPartId=baseId+'rightPart';f.markers=[];f.highlightPoint=function(point)
{var hd=document.getElementById(this.highlightPointId);if(hd==null)return;var hdStyle=hd.style;if(point==null)
{hdStyle.visibility='hidden';return;}
var pt=underground.getIPoint(point);hdStyle.left=((pt.x+1)*underground.settings.cellWidth-underground.settings.cellLocationShift).toString()+'px';hdStyle.top=((pt.y+1)*underground.settings.cellHeight-underground.settings.cellLocationShift).toString()+'px';hdStyle.visibility='';if(document.body.createTextRange)
{var trange=document.body.createTextRange();if(trange.moveToElementText&&trange.scrollIntoView)
{trange.moveToElementText(hd);trange.scrollIntoView();}}}
f.getLookPoint=function(x,y)
{var pt={x:x,y:y};switch(underground.settings.rotation)
{case 1:pt.x=y;pt.y=this.Height-x;break;case 2:pt.x=this.Width-x;pt.y=this.Height-y;break;case 3:pt.x=this.Width-y;pt.y=x;break;case 0:break;default:break;}
return pt;}
f.getLookDim=function()
{switch(underground.settings.rotation)
{case 0:return{xalpha:true,xinv:false,yinv:false,w:this.Width,h:this.Height};case 3:return{xalpha:false,xinv:false,yinv:true,w:this.Height,h:this.Width};case 1:return{xalpha:false,xinv:true,yinv:false,w:this.Height,h:this.Width};case 2:return{xalpha:true,xinv:true,yinv:true,w:this.Width,h:this.Height};}}
f.getMapHtml=function()
{var dm=(this.dungeon.ui.activeFloorIndex==this.index)?'':'none';var dim=this.getLookDim();var html=['<div id="',this.mapId,'" style="display:',dm,'">'];if(underground.offline)
{html.push('<table background="i/ugetc/cellbg.gif" cellspacing="0" cellpadding="0" border="0"><tr><td>');}
html=html.concat(['<div class="dmap" id="',this.mapViewId,'" style="width:',(dim.w+1)*underground.settings.cellWidth,'px; height: ',(dim.h+1)*underground.settings.cellHeight,'px;']);if(underground.settings.zoom!=100)
{html=html.concat(['zoom:',underground.settings.zoom,'%;']);}
html.push('">');html.push('<div id="');html.push(this.xaxisId);html.push('" class="dxaxis" style="position:absolute;left:');html.push(underground.settings.cellWidth);html.push('px;top:0;height:');html.push(underground.settings.cellHeight);html.push('px;width:');html.push(underground.settings.cellWidth*this.Width);html.push('px;');html.push('">');var xshift=(!msie||msie7)?10:0;var startLetterCode='A'.charCodeAt(0);for(var i=0;i<dim.w;i++)
{var vi=(dim.xinv)?(dim.w-i):(i+1);var vs=(dim.xalpha)?String.fromCharCode(startLetterCode+vi-1):vi.toString();html.push('<div class="xaxislabel" style="position:absolute;top:0;width:');html.push(underground.settings.cellWidth-1);html.push('px;height:');html.push(underground.settings.cellHeight-xshift-1);html.push('px;');html.push('color:');html.push(this.dungeon.CellBorderColorString);html.push(';');html.push('left:');html.push(i*underground.settings.cellWidth);html.push('px;" id="');html.push(this.xaxisId);html.push('_');html.push(i);html.push('">');html.push(vs);html.push('</div>');}
html=html.concat(['<div class="xaxisline"><img src="',underground.settings.blankImageUrl,'" width="1" height="1" border="0" /></div>','</div>']);html.push('<div id="'+this.yaxisId+'" class="dyaxis" style="position:absolute;left:0;top:'+underground.settings.cellHeight+'px;width:'+underground.settings.cellWidth+'px;height:'+(underground.settings.cellHeight*this.Height)+'px;">');var yshift=(!msie||msie7)?10:0;for(var i=0;i<dim.h;i++)
{var vi=(dim.yinv)?(dim.h-i):(i+1);var vs=(!dim.xalpha)?String.fromCharCode(startLetterCode+vi-1):vi.toString();html.push('<div class="yaxislabel" style="position:absolute;left:0;width:');html.push(underground.settings.cellWidth-8);html.push('px;height:');html.push(underground.settings.cellHeight-yshift-1);html.push('px;');html.push('color:');html.push(this.dungeon.CellBorderColorString);html.push(';');html.push('top:');html.push(i*underground.settings.cellHeight);html.push('px;" id="');html.push(this.yaxisId);html.push('_');html.push(i);html.push('">');html.push(vs);html.push('</div>');}
html=html.concat(['<div class="yaxisline"><img src="',underground.settings.blankImageUrl,'" width="1" height="1" border="0" /></div>','</div>']);for(var i in this.Cells)
{html.push(this.getCellHtml(this.Cells[i]));}
html.push('<div id="');html.push(this.highlightPointId);html.push('" style="position:absolute;left:0;top:0;width:40px;height:40px;visibility:hidden;padding:0;margin:0;">');html.push('<img src="');html.push(underground.settings.objectImagesUrl);html.push(underground.settings.highlightObjectImage);html.push('" width="40" height="40" border="0" alt="" title="Вы попали сюда" /></div>');for(var i=0;i<this.markers.length;i++)
{var marker=this.markers[i];marker.cellDivId=this.getCellDivId(marker.x,marker.y);html.push(this.getCellMarkerHtml(i,marker.cellDivId,marker.x,marker.y,marker.text));}
html.push('</div>');if(underground.offline)
{html.push('</td></tr></table>');}
html.push('<div id="');html.push(this.rightPartId);html.push('" class="dright" style="position:absolute;left:');var pos=(dim.w+2)*underground.settings.cellWidth;if(msie&&underground.settings.zoom!=100)
{pos=Math.floor((pos*underground.settings.zoom)/100);}
html.push(pos);html.push('px; top: 60px;">');html.push(this.getRightPartHtml());html.push('</div>');html.push('<div style="width:');html.push((dim.w+1)*underground.settings.cellWidth);html.push('px;">');html.push(this.getStatisticsHtml());html.push('</div>');html.push('</div>');return html.join('');}
f.getRightPartHtml=function()
{var html=[];html.push('<div style="padding: 2px; text-align: center;"><img width="120" height="121" border="0"');html.push(underground.pngClsAttr);html.push(' src="');html.push(underground.settings.compassLocation);html.push(underground.settings.compass[underground.settings.look]);html.push('.png" /></div>');this.floorNavId=baseId+'mapsNav';html.push('<div><a style="display:block;" onclick="ui_toggleMapsNav()" href="javascript:;">Этажи</a></div><div id="')
html.push(this.floorNavId);html.push('" class="mapsNav" style="display:');html.push(underground.settings.mapsNavDisplay);html.push(';">');for(var i in underground.knownDungeons)
{var kd=underground.knownDungeons[i];var floorCount=kd.FloorNames.length;if(floorCount>1)
{html.push('<div><b>');html.push(kd.Caption);html.push('</b></div><blockquote><ul>');for(var fi=0;fi<floorCount;fi++)
{var onclick;if(kd==this.dungeon.ui)
{onclick=['underground.changeFloor(',fi,')'];}
else
{onclick=['ui_cellObjectAction(1, \'',kd.Name,'.',(fi+1),'\', \'\')'];}
if(kd==this.dungeon.ui&&fi==this.index)
{html.push('<li style="list-style-type: square;"><b><nobr><a class="fblike" style="display: block;font-size:11px;" href="javascript:;">');html.push(kd.FloorNames[fi]);html.push('</a></nobr></b></li>');}
else
{html.push('<li style="list-style-type: square;"><nobr><a class="fblike" style="display: block;font-size:11px;" href="javascript:;" onclick="');html.push(onclick.join(''));html.push('">');html.push(kd.FloorNames[fi]);html.push('</a></nobr></li>');}}
html.push('</ul></blockquote>');}
else
{if(kd==this.dungeon.ui)
{html.push('<div><b><nobr><a class="fblike" style="display: block;" href="javascript:;">');html.push(kd.Caption);html.push('</a></nobr></b></div>');}
else
{html.push('<div><nobr><a class="fblike" style="display: block;" href="javascript:;" onclick="underground.openDungeon(');html.push(i);html.push(')">');html.push(kd.Caption);html.push('</a></nobr></div>');}}}
html.push('<div><a style="display:block;" onclick="ui_toggleMapsNav()" href="javascript:;">Скрыть</a></div>')
html.push('</div>');return html.join('');}
f.applyZoom=function(zoomFactor)
{document.getElementById(this.mapViewId).style.zoom=(zoomFactor+'%');var dim=this.getLookDim();var pos=(dim.w+2)*underground.settings.cellWidth;pos=Math.floor((pos*zoomFactor)/100);document.getElementById(this.rightPartId).style.left=pos+'px';}
f.getStatisticsHtml=function()
{var stats=this.getStatistics();return getStatisticsHtml('Статистика по этажу',stats,this.statisticsCheckId);}
f.getBorderStyleHtml=function(code,size)
{return['border-',code,':',size,'px ',((size<1)?'none':'solid'),' ',this.dungeon.CellBorderColorString,';','padding-',code,':',(2-size),'px;'].join('');}
f.getCellDivId=function(x,y)
{return[this.cellPrefix,x,'.',y].join('');}
f.getCellHtml=function(cell)
{cell.divId=this.getCellDivId(cell.X,cell.Y);var pt=this.getLookPoint(cell.X,cell.Y);var st=['background-color:',((cell.BackColorString!='Transparent')?cell.BackColorString:this.dungeon.CellBackgroundColorString),'; position: absolute;border-collapse: collapse;','left:',((pt.x+1)*underground.settings.cellWidth-underground.settings.cellLocationShift),'px;','top:',((pt.y+1)*underground.settings.cellHeight-underground.settings.cellLocationShift),'px;','width:',(underground.settings.cellWidth-underground.settings.cellSizeShift),'px;','height:',(underground.settings.cellHeight-underground.settings.cellSizeShift),'px;',this.getBorderStyleHtml('left',cell.Border.Left),this.getBorderStyleHtml('top',cell.Border.Top),this.getBorderStyleHtml('right',cell.Border.Right),this.getBorderStyleHtml('bottom',cell.Border.Bottom)];var html=['<div id="',cell.divId,'" style="'];html=html.concat(st);var mouseover=['document.getElementById(\'',this.xaxisId,'_',cell.X,'\').className = \'xaxislabel hotxaxislabel\';','document.getElementById(\'',this.yaxisId,'_',cell.Y,'\').className = \'yaxislabel hotyaxislabel\';'];var mouseout=['document.getElementById(\'',this.xaxisId,'_',cell.X,'\').className = \'xaxislabel\';','document.getElementById(\'',this.yaxisId,'_',cell.Y,'\').className = \'yaxislabel\';'];if(cell.HotSpots.length>0)
{mouseover=mouseover.concat(['underground.hotSpot(\'',cell.divId,'\');']);mouseout.push('hidePopup2();');}
html.push('" onmouseover="');html.push(mouseover.join(''));html.push(' return false" onmouseout="');html.push(mouseout.join(''));html.push('" oncontextmenu="underground.showCellContextMenu(\'');html.push(cell.divId);html.push('\',');html.push(cell.X);html.push(',');html.push(cell.Y);html.push('); return false">');for(var i in cell.ObjectRefs)
{var objref=cell.ObjectRefs[i];objref.divId=cell.divId+'_obj'+i;var oidata=underground.findObjectAndInstance(objref.ObjectName,objref.InstanceName);var o=oidata.obj;st=['position: absolute;z-index:1;','left:',o.ML,'px;','top:',o.MT,'px;','width:',o.MW,'px;','height:',o.MH,'px;'];var popuphtml=';';var mouseouthtml=';';if(oidata.instance.Description.length>0||o.Description.length>0)
{popuphtml=['underground.showObjRefPopup(\'',objref.ObjectName,'\', \'',objref.InstanceName,'\')'].join('');mouseouthtml='hidePopup2()';}
var image=underground.getCellObjectInstanceImage(o,oidata.instance);if(o.Importance==0)
{st.push(underground.decoratedOpacityStyle);}
if(oidata.instance.HighlightColorString!='Transparent')
{if(msie)
{st=st.concat(['filter:glow(color=\'',oidata.instance.HighlightColorString,'\', strength=4);']);}
else
{st=st.concat(['border: 1px solid ',oidata.instance.HighlightColorString,';']);}}
html=html.concat(['<img id="',objref.divId,'" onmouseover="',popuphtml,'" onmouseout="',mouseouthtml,'" src="',image,'" width="32" height="32" border="0"',' style="']);html=html.concat(st);html.push('"');if(oidata.instance.ClickAction!=0)
{html=html.concat([' onclick="ui_cellObjectAction(',oidata.instance.ClickAction,', \'',oidata.instance.ClickChapter,'\', \'',oidata.instance.ClickPoint,'\')"']);}
html.push(' />');}
for(var i in cell.MobRefs)
{var mpt=getMobPoint(i,cell.MobRefs.length);st=[];var mobref=cell.MobRefs[i];mobref.divId=cell.divId+'_mob'+i;var midata=underground.findMobAndInstance(mobref.Name,mobref.Level,mobref.Align,mobref.Tag);var image=underground.getMobInstanceImage(midata.mob,midata.mi);html=html.concat(['<img id="',mobref.divId,'" onmouseout="hidePopup2()" style="','position: absolute;z-index:2;','left:',mpt.x,'px;','top:',mpt.y,'px;','" src="',image,'" width="17" height="26" border="0"',' onmouseover="','underground.showMobRefPopup(\'',mobref.Name,'\', \'',mobref.Level,'\', \'',mobref.Align,'\', \'',mobref.Tag,'\', ',mobref.Count,')','" />']);}
html.push('</div>');return html.join('');}
f.highlightMobs=function(cell,mobName,mobLevel,v)
{var color=underground.settings.levelColors[mobLevel];var filterStr=['glow(color=\'',color,'\', strength=5)'].join('');for(var i in cell.MobRefs)
{var mobref=cell.MobRefs[i];if(mobref.Name!=mobName||mobref.Level!=mobLevel)
{continue;}
var div=document.getElementById(mobref.divId);if(v)
{if(msie)
{div.style.filter=filterStr;}
else
{div.style.borderStyle='solid';div.style.borderWidth='2px';div.style.borderColor=color;}}
else
{if(msie)
{div.style.filter='';}
else
{div.style.borderStyle='none';div.style.borderWidth='0px';div.style.borderColor='transparent';}}}}
f.checkStatsMob=function(mobName,mobLevel,depth)
{var stats=this.getStatistics();if(!(mobName in stats))return;if(mobLevel!=0&&!(mobLevel in stats[mobName]))return;var cid=getStatisticsCheckId(this.statisticsCheckId,mobName,mobLevel);var v=document.getElementById(cid).checked;if(mobLevel==0)
{for(var lvl in stats[mobName])
{var chkl=document.getElementById(getStatisticsCheckId(this.statisticsCheckId,mobName,lvl));if(chkl==null)continue;chkl.checked=v;this.checkStatsMob(mobName,lvl,true);}
return;}
for(var i in this.Cells)
{this.highlightMobs(this.Cells[i],mobName,mobLevel,v);}
if(depth!=null&&mobLevel!=0)
{var ok=true;var gid=getStatisticsCheckId(this.statisticsCheckId,mobName,0);for(var lvl in stats[mobName])
{var id=getStatisticsCheckId(this.statisticsCheckId,mobName,mobLevel);if(!document.getElementById(id).checked)
{ok=false;break;}}
document.getElementById(gid).checked=ok;}}
f.getStatistics=function()
{var r={};for(var i in this.Cells)
{var cell=this.Cells[i];for(var j in cell.MobRefs)
{var mobref=cell.MobRefs[j];if(!(mobref.Name in r))
{r[mobref.Name]={};}
if(!(mobref.Level in r[mobref.Name]))
{r[mobref.Name][mobref.Level]=0;}
r[mobref.Name][mobref.Level]+=mobref.Count;}}
return r;}
f.showCellMarkerPopup=function(markerIndex)
{showPopup('<div class="hintviewcaption">Заметки на полях</div>'+htmlstring(this.markers[markerIndex].text));}
f.getHash=function()
{var r=[];for(var i=0;i<this.markers.length;i++)
{var marker=this.markers[i];if(marker.hidden)continue;r.push('&');r.push(marker.x);r.push('.');r.push(marker.y);r.push('&');r.push(escape(marker.text));}
return r.join('');}
f.handleHash=function(hash)
{var sa=hash.split('&');for(var i=0;i<sa.length-1;i+=2)
{var xy=sa[i].split('.');var x=parseInt(xy[0]);var y=parseInt(xy[1]);var text=unescape(sa[i+1]);if(!isNaN(x)&&!isNaN(y))
{this.markers.push({x:x,y:y,cellDivId:null,text:text,hidden:false});}}}
f.getCellMarkerHtml=function(markerIndex,cellDivId,x,y,text)
{var markerId=cellDivId+'_marker';var mouseover=['document.getElementById(\'',this.xaxisId,'_',x,'\').className = \'xaxislabel hotxaxislabel\';','document.getElementById(\'',this.yaxisId,'_',y,'\').className = \'yaxislabel hotyaxislabel\';','ui_showCellMarkerPopup(',markerIndex,'); return false'];var mouseout=['document.getElementById(\'',this.xaxisId,'_',x,'\').className = \'xaxislabel\';','document.getElementById(\'',this.yaxisId,'_',y,'\').className = \'yaxislabel\';','hidePopup2(); return false'];var content=['<div id="',markerId,'" style="position:absolute;left:',((x+1)*underground.settings.cellWidth-underground.settings.cellLocationShift).toString(),'px;top:',((y+1)*underground.settings.cellHeight-underground.settings.cellLocationShift).toString(),'px;width:40px;height:40px;padding:0;margin:0;z-index:3;" oncontextmenu="underground.showCellContextMenu(\'',cellDivId,'\',',x,',',y,'); return false"><img src="',underground.settings.cellMarkImageUrl,'" width="40" height="40" border="0" onmouseout="',mouseout.join(''),'" onmouseover="',mouseover.join(''),'" /></div>'].join('');return content;}
f.toggleCellMarker=function(cellDivId,x,y)
{var markerId=cellDivId+'_marker';var mdiv=document.getElementById(markerId);if(mdiv!=null&&mdiv.style.visibility=='')
{for(var i=0;i<this.markers.length;i++)
{var marker=this.markers[i];if(marker.x==x&&marker.y==y)
{marker.hidden=true;break;}}
mdiv.style.visibility='hidden';underground.setHash();return;}
var text=window.prompt('Укажите пояснительный текст:');if(text==null)return;if(mdiv==null)
{var content=this.getCellMarkerHtml(this.markers.length,cellDivId,x,y,text);var mapDiv=document.getElementById(this.mapViewId);if(mapDiv.insertAdjacentHTML)
{mapDiv.insertAdjacentHTML('beforeEnd',content);}
else
{mapDiv.innerHTML=mapDiv.innerHTML+content;}
mdiv=document.getElementById(markerId);this.markers.push({x:x,y:y,cellDivId:cellDivId,text:text,hidden:false});}
else
{for(var i=0;i<this.markers.length;i++)
{var marker=this.markers[i];if(marker.x==x&&marker.y==y)
{marker.hidden=false;marker.text=text;break;}}}
mdiv.style.visibility='';underground.setHash();}
f.getCellContentMenuHtml=function(cellDivId,x,y)
{var html=[];html.push('<table cellspacing="0" cellpadding="0" border="0"><tr>');html.push(getRowMenuItemHtml('Поставить/снять маркер','ui_toggleCellMarker(\''+cellDivId+'\','+x+','+y+')'));html.push(getRowMenuSeparatorHtml());html.push(getRowMenuItemHtml('Закрыть','hideMenu()'));html.push('</table>');return html.join('');}
f.findCellByDivId=function(cellDivId)
{for(var i in this.Cells)
{var cell=this.Cells[i];if(cell.divId==cellDivId)
{return cell;}}
return null;}
f.findCellByRef=function(cellRef)
{for(var i in this.Cells)
{var cell=this.Cells[i];if(cell.X==cellRef.X&&cell.Y==cellRef.Y)
{return cell;}}
return null;}
f.hotSpot=function(cellDivId)
{var cell=this.findCellByDivId(cellDivId);if(cell==null)return;for(var i in cell.HotSpots)
{var hotSpot=cell.HotSpots[i];hotSpot.divId=cellDivId+'_hotSpot'+i;var opac=hotSpot.Opacity;var color=hotSpot.HighlightColorString;var imageUrl=hotSpot.ImageUrl;if(imageUrl=='')
{imageUrl=underground.settings.blankImageUrl;}
for(var j in hotSpot.CellRefs)
{var markerId=hotSpot.divId+'_mark'+j;var marker=document.getElementById(markerId);if(marker!=null)
{marker.style.visibility='';continue;}
var html=[];var cref=hotSpot.CellRefs[j];var tcell=this.findCellByRef(cref);var te=document.getElementById(tcell.divId);if(te==null)continue;html.push('<div id="');html.push(markerId);html.push('" style="position: absolute; padding: 0; border: 0; z-index:10; left: ');html.push(underground.settings.cellLocationShift);html.push('px; background-color: ');html.push(color);html.push('; top: ');html.push(underground.settings.cellLocationShift);html.push('px;');if(opac<1)
{html.push(' opacity: ');html.push(opac);html.push('; -moz-opacity: ');html.push(opac);html.push('; KhtmlOpacity: ');html.push(opac);html.push(';filter:alpha(opacity = ');html.push(Math.floor(opac*100));html.push(', style = 4);');}
html.push(' width: ');html.push(underground.settings.cellWidth-tcell.Border.Left-tcell.Border.Right);html.push('; height: ');html.push(underground.settings.cellHeight-tcell.Border.Top-tcell.Border.Bottom);html.push(';"><img src="');html.push(imageUrl);html.push('" border="0" width="');html.push(underground.settings.cellWidth-tcell.Border.Left-tcell.Border.Right);html.push('" height="');html.push(underground.settings.cellHeight-tcell.Border.Top-tcell.Border.Bottom);html.push('" /></div>');html=html.join('');if(te.insertAdjacentHTML)
{te.insertAdjacentHTML('beforeEnd',html);}
else
{te.innerHTML=te.innerHTML+html;}}}}
f.hideHotSpots=function()
{for(var i in this.Cells)
{var cell=this.Cells[i];for(var i in cell.HotSpots)
{var hotSpot=cell.HotSpots[i];if(!('divId'in hotSpot))continue;var hotSpotId=hotSpot.divId;for(var j in hotSpot.CellRefs)
{var markerId=hotSpot.divId+'_mark'+j;var marker=document.getElementById(markerId);if(marker!=null)
{marker.style.visibility='hidden';}}}}}}
function LoadedDungeonEntender(d)
{d.IsDungeon&=(d.Floors.length>0);for(var i in d.Floors)
{var f=d.Floors[i];FloorExtender(d,i,f);}
d.getMapHtml=function()
{var html=[];for(var i in this.Floors)
{var f=d.Floors[i];html.push(f.getMapHtml());}
return html.join('');}
d.afterConstruction=function()
{}}
function CustomResource()
{this.Description=[];this.NewLineToBR=true;this.activeFloorIndex=0;this.rotation=0;this.FloorCount=1;this.getChapterHtml=function()
{return'';}
this.afterConstruction=function()
{}
this.getHash=function()
{return this.Name;}
this.handleHash=function(suffix)
{}
this.changeFloor=function(floorIndex)
{}
this.getUndergroundTabsHtml=function()
{return'';}
this.hidePages=function()
{}
this.needData=function(f)
{f();}
this.getCellContentMenuHtml=function(cellDivId,x,y)
{return'';}
this.hotSpot=function(cellDivId)
{}
this.hideHotSpots=function()
{}
this.getProposedZoom=function()
{return underground.settings.zoom;}
this.applyZoom=function(zoomFactor)
{}
this.highlightPoint=function(point)
{}}
function ExternalResource(url,caption,desc,newLineToBR)
{this.Url=url;this.Caption=caption;this.Description=desc;this.NewLineToBR=newLineToBR;this.desc=desc.join(newLineToBR?'<br />':'');}
function CustomFloor(d,i,name,description,newLineToBR)
{this.dungeon=d;this.index=i;this.Name=name;this.Description=description;this.NewLineToBR=newLineToBR;this.mapId='dungeon_'+d.Name+'_floor_'+this.Name+'_map';this.getMapHtml=function()
{var html=[];var dm=(this.dungeon.ui.activeFloorIndex==this.index)?'':'none';html.push('<div id="');html.push(this.mapId);html.push('" style="display:');html.push(dm);html.push('">');html.push(this.Description.join(this.NewLineToBR?'<br />':''));html.push('</div>');return html.join('');}
this.getStatisticsHtml=function()
{return'';}
this.getStatistics=function()
{return{};}
this.getCellContentMenuHtml=function(cellDivId,x,y)
{var html=[];html.push('<table cellspacing="0" cellpadding="0" border="0"><tr>');html.push(getRowMenuItemHtml('Закрыть','hideMenu()'));html.push('</table>');return html.join('');}
this.hotSpot=function(cellDivId)
{}
this.hideHotSpots=function()
{}
this.getHash=function()
{return'';}
this.handleHash=function(hash)
{}}
function WelcomeDungeon()
{this.baseConstructor=CustomResource;this.baseConstructor();this.Name='welcome';this.Caption='Стартовая';this.showDescription=function(s)
{document.getElementById('welcomeDungeon_descriptionArea').innerHTML=s;}
this.getMenuItemHtml=function(d,di,popupFuncName,openFuncName,url)
{var target=' target="_blank"';if(url==null){url='javascript:;';target='';}
return['<li style="margin: 5px;"><a',target,' class="fblike" style="display: block; width: 256px;" onmouseover="',popupFuncName,'(',di,')" onmouseout="underground.welcomeDungeon.showDescription(\'&amp;nbsp;\')" href="',url,'" onclick="',openFuncName,'(',di,')">',d.Caption,'</a></li>'].join('');}
this.getChapterHtml=function()
{var html=[];html.push('<table cellpadding="0" cellspacing="0" width="100%" border="0"><tr><td width="250" valign="top"><br /><br /><p>Добро пожаловать в ресурс о Подземельях Бойцовского Клуба.</p>');html.push('<p>Пожалуйста, выберите одно из подземелий:</p>');html.push('<blockquote><ul style="margin: 5px; font-size: larger;">');for(var di in underground.knownDungeons)
{var d=underground.knownDungeons[di];if(!d.IsDungeon)continue;html.push(this.getMenuItemHtml(d,di,'underground.showDungeonPopup','underground.openDungeon'));}
html.push('</ul></blockquote>');html.push('<p>Одно из сопутствующих мест:</p>');html.push('<blockquote><ul style="margin: 5px; font-size: larger;">');for(var di in underground.knownDungeons)
{var d=underground.knownDungeons[di];if(d.IsDungeon)continue;html.push(this.getMenuItemHtml(d,di,'underground.showDungeonPopup','underground.openDungeon'));}
html.push('</ul></blockquote>');html.push('<p>Или один из дополнительных ресурсов:</p>');html.push('<blockquote><ul style="margin: 5px; font-size: larger;">');for(var di in underground.resources)
{var d=underground.resources[di];html.push(this.getMenuItemHtml(d,di,'underground.showResourcePopup','underground.openResource'));}
html.push('</ul></blockquote>');html.push('<p>Также рекомендуем:</p>');html.push('<blockquote><ul style="margin: 5px; font-size: larger;">');for(var di in underground.externals)
{var d=underground.externals[di];html.push(this.getMenuItemHtml(d,di,'underground.showExternalPopup','javascript:;',d.Url));}
html.push('</ul></blockquote></td><td valign="top"><div id="welcomeDungeon_descriptionArea" style="padding: 4px; margin: 4px; margin-right: 40px; margin-top: 70px; border: 1px solid #ccc;">&nbsp;</div></td></tr></table>');return html.join('');}}
function DiggerConst()
{this.baseConstructor=CustomResource;this.baseConstructor();this.Description=['Устали рыться в пергаментах с рецептами, постоянно путаетесь в мешочках с ингредиентами, потеряли ступку с колбами?','Хм, что ж. В помощь юному алхимику подготовлен удобный ресурс, с полной базой рецептов и предметов.','Завтра, включив компьютер, вы будете точно знать, что еще необходимо собрать.'];this.Name='diggerconst';this.Caption='Конструктор диггера';this.tabIndex=0;this.imagesToBePreloaded=[];this.divId='diggerConstructorAll';this.tabsId='diggerConstructorTabs';this.knapsackId='diggerConstructorKnapsack';this.recipesId='diggerConstructorRecipes';this.allRecipes1Id='diggerConstructorAllRecipes1';this.allRecipesView1Id='diggerConstructorAllRecipesView1';this.allRecipesItem1Id='diggerConstructorAllRecipesItem1';this.allRecipes2Id='diggerConstructorAllRecipes2';this.allRecipesView2Id='diggerConstructorAllRecipesView2';this.allRecipesItem2Id='diggerConstructorAllRecipesItem2';this.allItemsId='diggerConstructorAllItems';this.allItemsViewId='diggerConstructorAllItemsView';this.allItemsItemId='diggerConstructorAllItemsItem';this.basketId='diggerConstructorBasket';this.basketItemsViewId='diggerConstructorBasketItemsView';this.basketSummaryId='diggerConstructorBasketSummaryId';this.tabIds=[this.knapsackId,this.recipesId,this.allRecipes1Id,this.allRecipes2Id,this.allItemsId,this.basketId];this.itemViewIds=[null,null,this.allRecipesItem1Id,this.allRecipesItem2Id,this.allItemsItemId,null];this.knapsackViewId='diggerConstructorKnapsackView';this.knapsackImportDialogId='diggerConstructorKnapsackImportDialog';this.knapsackImportDialogTextId='diggerConstructorKnapsackImportDialogText';this.knapsackImportDialogLabelId='diggerConstructorKnapsackImportDialogLabel';this.knapsackAddIngrDialogId='diggerConstructorKnapsackAddIngrDialogId';this.knapsackAddIngrDialogLabelId='diggerConstructorKnapsackAddIngrDialogLabelId';this.knapsackAddIngrDialogIngrId='diggerConstructorKnapsackAddIngrDialogIngrId';this.knapsackAddIngrDialogCountId='diggerConstructorKnapsackAddIngrDialogCountId';this.knapsackTableId='diggerConstructorKnapsackTable';this.availIngrId='diggerConstructor_availIngr';this.availRecipesId='diggerConstructor_availRecipes';this.chosenIngrId='diggerConstructor_chosenIngr';this.chosenRecipesId='diggerConstructor_chosenRecipes';this.isChest=false;this.toInvPhrase='В инвентарь ';this.wearPhrase='надеть  ';this.reCount=/^\(X([0-9]+)\)/;this.knapsack={};this.items={};this.components={};this.availi={};this.availr=[];this.choseni={};this.chosenr=[];this.recipes=[];this.basket=[];this.loadCostructorData=function(d)
{this.data=d;this.collectDiggerItems();}
this.collectDiggerItems=function()
{for(var cati=this.data.Categories.length-1;cati>=0;cati--)
{var cat=this.data.Categories[cati];cat.hasRecipes=false;for(var i=cat.Items.length-1;i>=0;i--)
{var itm=cat.Items[i];itm.Category=cat;cat.hasRecipes|=(itm.Recipes.length>0);this.items[itm.Name]=itm;}}
for(var i in this.items)
{var itm=this.items[i];for(var ri=itm.Recipes.length-1;ri>=0;ri--)
{var r=itm.Recipes[ri];r.Item=itm;this.recipes.push(r);for(var pi=r.RecipeParts.length-1;pi>=0;pi--)
{var rp=r.RecipeParts[pi];this.components[rp.Item]=this.items[rp.Item];if(!('usedIn'in this.components[rp.Item]))
{this.components[rp.Item].usedIn={};}
this.components[rp.Item].usedIn[itm.Name]={recipe:r,count:rp.Count};}}}}
this.dataLoaded=function(f)
{this.data.ui=this;if(this.imagesToBePreloaded.length>0)
{this.preloader=new ImagePreloader(this.imagesToBePreloaded,null);}
f();}
this.loadData=function(f)
{var d=this;var sn='dungeon.constructor.js';if(!underground.offline&&!(sn in underground.loadedScripts))
{loadScript(underground.settings.jsBase+sn,function(){underground.loadedScripts[sn]=sn;d.dataLoaded(f);});}
else
{d.dataLoaded(f);}}
this.needData=function(f)
{if(!('data'in this))
{this.loadData(f);}
else
{f();}}
this.finalizeAvailableRecipes=function()
{}
this.probeRecipe=function(r)
{if(r.RecipeParts.length<=0)return;var count=65535;for(var i=r.RecipeParts.length-1;i>=0;i--)
{var rp=r.RecipeParts[i];var mater=this.findDiggerItem(rp.Item);if(!mater.IsGift&&(mater.Binding==0)&&((rp.Binding==0||rp.Binding==4)))
{continue;}
if(!(rp.Item in this.availi))return;var rpCount=this.availi[rp.Item]/rp.Count;if(rpCount<count)
{count=rpCount;if(count<1)break;}}
count=Math.floor(count);if(count>0)
{this.availr.push({recipe:r,count:count});}}
this.prepareAvailableRecipes=function()
{this.availi={};this.choseni={};for(var n in this.knapsack)
{this.availi[this.knapsack[n].name]=this.knapsack[n].total;}
this.finalizeAvailableRecipes();this.availr=[];this.chosenr=[];for(var i=this.recipes.length-1;i>=0;i--)
{this.probeRecipe(this.recipes[i]);}
this.refreshRecipesTab();}
this.changeDiggerTab=function(index)
{this.tabIndex=index;var activeDivId=this.tabIds[index];var divs=document.getElementById(this.divId).childNodes;for(var i=0;i<divs.length;i++)
{var div=divs[i];var dv=(div.id==activeDivId)?'':'none';div.style.display=dv;}
document.getElementById(this.tabsId).innerHTML=this.getDiggerTabsHtml();if(index==1)
{this.prepareAvailableRecipes();}}
this.getDiggerTabHtml=function(caption,i)
{var html=[];html.push('<li');if(i==this.tabIndex)
{html.push(' class="activeLink"');}
html.push('><a href="javascript:;"');if(i!=this.tabIndex)
{html.push(' onclick="ui_changeDiggerTab(');html.push(i);html.push(')"');}
html.push('>');html.push(caption);html.push('</a></li>');return html.join('');}
this.loadKnapsack=function(isChest)
{this.isChest=isChest;document.getElementById(this.knapsackImportDialogLabelId).innerHTML=isChest?'сундука':'рюкзака';document.getElementById(this.knapsackImportDialogTextId).value='';document.getElementById(this.knapsackAddIngrDialogId).style.visibility='hidden';document.getElementById(this.knapsackImportDialogId).style.visibility='';}
this.loadKnapsackCancel=function()
{document.getElementById(this.knapsackImportDialogTextId).value='';document.getElementById(this.knapsackImportDialogId).style.visibility='hidden';}
this.loadKnapsackOK=function()
{this.parseKnapsackFor(this.isChest,document.getElementById(this.knapsackImportDialogTextId).value);this.loadKnapsackCancel();}
this.addToKnapsack=function(isChest)
{this.isChest=isChest;document.getElementById(this.knapsackAddIngrDialogLabelId).innerHTML=isChest?'сундук':'рюкзак';document.getElementById(this.knapsackAddIngrDialogCountId).value='1';document.getElementById(this.knapsackImportDialogId).style.visibility='hidden';document.getElementById(this.knapsackAddIngrDialogId).style.visibility='';}
this.addKnapsackOK=function()
{var so=document.getElementById(this.knapsackAddIngrDialogIngrId);var cov=document.getElementById(this.knapsackAddIngrDialogCountId).value;if(so.selectedIndex>=0&&!isNaN(cov)&&parseInt(cov)>0)
{this.addIngr(this.isChest,so.options[so.selectedIndex].value,parseInt(cov));}
this.addKnapsackCancel();}
this.addKnapsackCancel=function()
{document.getElementById(this.knapsackAddIngrDialogId).style.visibility='hidden';}
this.getDiggerTabsHtml=function()
{var html=[];html.push('<div class="dtab" style="background-color: #e6e6e6;"><ul class="dtab">');html.push(this.getDiggerTabHtml('Ваш инвентарь',0));html.push(this.getDiggerTabHtml('Подбор рецептов',1));html.push(this.getDiggerTabHtml('Рецепты по месту сбора',2));html.push(this.getDiggerTabHtml('Рецепты по категории предметов',3));html.push(this.getDiggerTabHtml('Все предметы',4));html.push(this.getDiggerTabHtml('Корзина',5));html.push('</ul></div>');return html.join('');}
this.findDiggerItem=function(id)
{return this.items[id];}
this.cleanKnapsackFor=function(isChest,refresh)
{var r={};var vn=isChest?'chest':'knapsack';for(var i in this.knapsack)
{var itm=this.knapsack[i];itm.total-=itm[vn];itm[vn]=0;if(itm.total==0)
{continue;}
r[i]=itm;}
this.knapsack=r;if(refresh)
{this.refreshKnapsackTab();}}
this.parserTrim=function(value)
{return value.replace(/^\s+|\s*([\+\,>\s;:])\s*|\s+$/g,"$1");}
this.refreshKnapsackTab=function()
{document.getElementById(this.knapsackViewId).innerHTML=this.getKnapsackTableHtml();}
this.parseKnapsackFor=function(isChest,text)
{var vn=isChest?'chest':'knapsack';this.cleanKnapsackFor(isChest,false);var lines=text.split("\n");for(var i=0;i<lines.length;i++)
{var line=lines[i];line=this.parserTrim(line);var phraseFound=true;while(phraseFound)
{phraseFound=false;if(line.indexOf(this.wearPhrase)==0)
{line=line.substr(this.wearPhrase.length);phraseFound=true;}
if(line.indexOf(this.toInvPhrase)==0)
{line=line.substr(this.toInvPhrase.length);phraseFound=true;}}
var upperLine=line.toUpperCase();for(var ii in this.components)
{var c=this.components[ii];var cuc=c.Caption.toUpperCase();if(upperLine.indexOf(cuc)==0)
{if(!(c.Name in this.knapsack))
{this.knapsack[c.Name]={name:c.Name,knapsack:0,chest:0,total:0};}
var count=1;var trailer=this.parserTrim(upperLine.substr(cuc.length));var m=trailer.match(this.reCount);if(m!=null)
{count=parseInt(m[1]);}
this.knapsack[c.Name][vn]+=count;this.knapsack[c.Name].total+=count;break;}}}
this.refreshKnapsackTab();}
this.addIngr=function(toChest,name,count)
{var vn=toChest?'chest':'knapsack';if(!(name in this.knapsack))
{this.knapsack[name]={name:name,knapsack:0,chest:0,total:0};}
if((-count)>this.knapsack[name][vn])count=this.knapsack[name][vn];if((-count)>this.knapsack[name].total)count=this.knapsack[name].total;this.knapsack[name][vn]+=count;this.knapsack[name].total+=count;if(this.knapsack[name].total<=0)
{delete this.knapsack[name];}
this.refreshKnapsackTab();}
this.findRecipePlace=function(name)
{var arp=this.data.RecipePlaces;for(var i=arp.length-1;i>=0;i--)
{var rp=arp[i];if(rp.Name==name)return rp;}
return null;}
this.getItemRecipeHtml=function(recipe)
{var html=[];var place=this.findRecipePlace(recipe.PlaceName);if(place==null)return'<font color="red">bad place</font>';var arp=recipe.RecipeParts;html.push('<fieldset><legend title="');html.push(place.Description.join('<br />'));html.push('">');html.push(place.Caption);html.push('</legend>');if(recipe.Price>0)
{html.push('<div>');html.push('Стоимость сборки: ');html.push(recipe.Price);html.push(' кр.</div>');}
for(var i=0;i<arp.length;i++)
{var rp=arp[i];var mater=this.findDiggerItem(rp.Item);html.push('<div>');html.push(mater.Caption);html.push(': ');html.push(rp.Count);html.push('шт.</div>');}
html.push('</fieldset>');return html.join('');}
this.getItemDescHtml=function(name)
{var item=this.findDiggerItem(name);var html=[];var img=[item.Category.ImageBasePath,item.Name,'.gif'].join('');html.push('<div><nobr><b>');html.push(item.Caption);if(item.Binding>0)
{html.push(' <img src="http://img.combats.ru/i/destiny.gif" width="16" height="18" border="0" alt=""');switch(item.Binding)
{case 1:html.push(' style="filter: invert();" title="Этот предмет будет связан общей судьбой с первым, кто получит его. Никто другой не сможет его использовать."');break;case 2:html.push(' style="filter: gray();" title="Этот предмет будет связан общей судьбой с первым, кто оденет его. Никто другой не сможет его использовать."');break;case 3:html.push(' title="Этот предмет связан общей судьбой."');break;}
html.push(' />');}
if(item.IsGift)
{html.push(' <img src="http://img.combats.ru/i/podarok.gif" width="16" height="18" border="0" alt="" title="Подарок" />');}
html.push('</b></nobr></div>');html.push('<div>');html.push('<img src="');html.push(img);html.push('" border="0" align="right" width="');html.push(item.W);html.push('" height="');html.push(item.H);html.push('" />');html.push(item.Description.join(item.NewLineToBR?'<br />':''));html.push('</div>');if(('Recipes'in item)&&(item.Recipes.length>0))
{html.push('<div class="hintview">');for(var ri in item.Recipes)
{html.push(this.getItemRecipeHtml(item.Recipes[ri]));}
html.push('</div>');}
if('usedIn'in item)
{html.push('<table class="proshka" width="100%">');for(var ui in item.usedIn)
{var uitem=this.findDiggerItem(ui);var place=this.findRecipePlace(item.usedIn[ui].recipe.PlaceName);html.push('<tr><td>Используется для создания: <b>');html.push(uitem.Caption);html.push('</b> в количестве ');html.push(item.usedIn[ui].count);html.push('шт., в ');if(place==null)html.push('<font color="red">bad place</font>');else html.push(place.Caption);html.push('.</td></tr>');}
html.push('</table>');}
return html.join('');}
this.showItemInView=function(name)
{var vid=this.itemViewIds[this.tabIndex];if(vid==null)return;var ve=document.getElementById(vid);if(ve==null)return;ve.innerHTML=this.getItemDescHtml(name);}
this.showItemPopup=function(name)
{var html=[];html.push('<div style="width: 400px;">');html.push(this.getItemDescHtml(name));html.push('</div>');showPopup(html.join(''));}
this.getKnapsackTableHtml=function()
{var materCount=0;var html=[];html.push('<table style="table-layout:fixed;width:100%;background-color: white;border:1px solid #ccc;padding:2px;margin:0;" id="');html.push(this.knapsackTableId);html.push('">');for(var materi in this.knapsack)
{var materd=this.knapsack[materi];var matern=materd.name;var mater=this.findDiggerItem(matern);html.push('<tr><td width="60" align="center" valign="center" rowspan="2"><img src="');html.push(mater.Category.ImageBasePath);html.push(mater.Name);html.push('.gif" width="');html.push(mater.W);html.push('" height="');html.push(mater.H);html.push('" border="0" alt="" onmouseover="ui_showDiggerItemPopup(\'');html.push(mater.Name);html.push('\')" onmouseout="hidePopup2()" /></td><td colspan="2"><b onmouseover="ui_showDiggerItemPopup(\'');html.push(mater.Name);html.push('\')" onmouseout="hidePopup2()">');html.push(mater.Caption);html.push('</b></td><td rowspan="2" align="right">Всего: <b>');html.push(materd.total);html.push('</b>шт.</td></tr><tr><td align="right">В рюкзаке: ');html.push(materd.knapsack);html.push('шт.');html.push('&nbsp;<a title="Увеличить количество" href="javascript:;" onclick="ui_addIngr(false, \'');html.push(mater.Name);html.push('\', 1)"><b>+</b></a>');if(materd.knapsack>0)
{html.push('&nbsp;<a title="Уменьшить количество" href="javascript:;" onclick="ui_addIngr(false, \'');html.push(mater.Name);html.push('\', -1)"><b>-</b></a>');}
html.push('</td><td align="right">В сундуке: ');html.push(materd.chest);html.push('шт.');html.push('&nbsp;<a title="Увеличить количество" href="javascript:;" onclick="ui_addIngr(true, \'');html.push(mater.Name);html.push('\', 1)"><b>+</b></a>');if(materd.chest>0)
{html.push('&nbsp;<a title="Уменьшить количество" href="javascript:;" onclick="ui_addIngr(true, \'');html.push(mater.Name);html.push('\', -1)"><b>-</b></a>');}
html.push('</td></tr>');materCount++;}
if(materCount==0)
{html.push('<tr><td align="center" style="color: #a0a0a0;">Инвентарь не содержит ингредиентов и других компонентов.</td></tr>');}
html.push('</table>');return html.join('');}
this.getComponentOptions=function()
{var html=[];for(var cati=0;cati<this.data.Categories.length;cati++)
{var cat=this.data.Categories[cati];var chtml=[];for(var i=0;i<cat.Items.length;i++)
{var itm=cat.Items[i];if(!('usedIn'in itm))continue;chtml.push('<option value="');chtml.push(itm.Name);chtml.push('">');chtml.push(itm.Caption);chtml.push('</option>');}
if(chtml.length>0)
{html.push('<optgroup label="');html.push(cat.Caption);html.push('">');html.push(chtml.join(''));html.push('</optgroup>');}}
return html.join('');}
this.getKnapsackTabHtml=function()
{var html=[];html.push('<div style="position:relative;" id="');html.push(this.knapsackId);html.push('">');html.push('<div style="visibility:hidden;position:absolute;left:5%;width: 90%;top:10px;height: 380px;border: 1px solid #333;filter: alpha(opacity = 95, style = 4), progid:DXImageTransform.Microsoft.Shadow(color=\'#666666\', Direction=135, Strength=2);opacity:0.95;-moz-opacity:0.95;KhtmlOpacity:0.95;background-color:whitesmoke;padding:4px;" id="');html.push(this.knapsackImportDialogId);html.push('"><div style="background-color: caption; color: captiontext;">Загрузка данных</div>');html.push('Скопируйте содержимое <span id="');html.push(this.knapsackImportDialogLabelId);html.push('">рюкзака</span> сюда:<br /><textarea style="left:4%;width:92%;top: 4px;height:320px;" mode="soft" id="');html.push(this.knapsackImportDialogTextId);html.push('"></textarea><br />');html.push(' <input type="button" class="inpButton" value="Загрузить" onclick="ui_loadKnapsackOK()" />');html.push(' <input type="button" class="inpButton" value="Отмена" onclick="ui_loadKnapsackCancel()" />');html.push('</div>');html.push('<div style="visibility:hidden;position:absolute;left:5%;width: 90%;top:10px;border: 1px solid #333;filter: alpha(opacity = 95, style = 4), progid:DXImageTransform.Microsoft.Shadow(color=\'#666666\', Direction=135, Strength=2);opacity:0.95;-moz-opacity:0.95;KhtmlOpacity:0.95;background-color:whitesmoke;padding:4px;" id="');html.push(this.knapsackAddIngrDialogId);html.push('"><div style="background-color: caption; color: captiontext;">Добавление ингредиента</div>');html.push('Добавить ингредиент в <span id="');html.push(this.knapsackAddIngrDialogLabelId);html.push('">рюкзак</span>: <select id="');html.push(this.knapsackAddIngrDialogIngrId);html.push('">');html.push(this.getComponentOptions());html.push('</select><br />Количество: <input type="text" value="1" id="');html.push(this.knapsackAddIngrDialogCountId);html.push('" /><br />');html.push(' <input type="button" class="inpButton" value="Добавить" onclick="ui_addKnapsackOK()" />');html.push(' <input type="button" class="inpButton" value="Отмена" onclick="ui_addKnapsackCancel()" />');html.push('</div>');html.push('<div style="width: 100%;height: 440px;overflow: auto;">');html.push('<div id="');html.push(this.knapsackViewId);html.push('">');html.push(this.getKnapsackTableHtml());html.push('</div></div>');html.push(' <input type="button" class="inpButton" value="Загрузить рюкзак" onclick="ui_loadKnapsack(false)" />');html.push(' <input type="button" class="inpButton" value="Загрузить сундук" onclick="ui_loadKnapsack(true)" />');html.push(' <input type="button" class="inpButton" value="Добавить ингредиент в рюкзак" onclick="ui_addToKnapsack(false)" />');html.push(' <input type="button" class="inpButton" value="Добавить ингредиент в сундук" onclick="ui_addToKnapsack(true)" />');html.push(' <input type="button" class="inpButton" value="Сохранить на сервере" onclick="ui_saveKnapsack()" disabled="yes" />');html.push(' <input type="button" class="inpButton" value="Очистить" onclick="ui_clearKnapsack()" />');html.push(' <input type="button" class="inpButton" value="Подобрать рецепты &gt;&gt;&gt;" onclick="ui_changeDiggerTab(1)" />');html.push('</div>');return html.join('');}
this.refreshRecipesTab=function()
{document.getElementById(this.availIngrId).innerHTML=this.getIngrTable(this.availi,'Доступные компоненты');document.getElementById(this.availRecipesId).innerHTML=this.getRecipeTable(this.availr,true,'Доступные рецепты');document.getElementById(this.chosenIngrId).innerHTML=this.getIngrTable(this.choseni,'Использованные компоненты');document.getElementById(this.chosenRecipesId).innerHTML=this.getRecipeTable(this.chosenr,false,'Выбранные рецепты');}
this.getRecipesTabHtml=function()
{var html=[];html.push('<div style="display: none;" id="');html.push(this.recipesId);html.push('">');html.push('<table class="proshka" style="width: 100%" style="table-layout: fixed;"><tr><td width="50%"><div style="overflow: auto;height:260px;background-color: white;" id="');html.push(this.availIngrId);html.push('">');html.push(this.getIngrTable({},'Доступные компоненты'));html.push('</div></td><td><div style="overflow: auto;height:260px;background-color: white;" id="');html.push(this.availRecipesId);html.push('">');html.push(this.getRecipeTable([],true,'Доступные рецепты'));html.push('</div></td></tr><tr><td width="50%"><div style="overflow: auto;height:260px;background-color: white;" id="');html.push(this.chosenIngrId);html.push('">');html.push(this.getIngrTable({},'Использованные компоненты'));html.push('</div></td><td><div style="overflow: auto;height:260px;background-color: white;" id="');html.push(this.chosenRecipesId);html.push('">');html.push(this.getRecipeTable([],false,'Выбранные рецепты'));html.push('</div></td></tr></table>');html.push(' <input type="button" class="inpButton" value="&lt;&lt;&lt; Изменить содержание рюкзака" onclick="ui_changeDiggerTab(0)" />');html.push('</div>');return html.join('');}
this.getIngrTable=function(va,title)
{var html=[];var count=0;html.push('<table class="proshka">');if(title!=null)
{html.push('<tr><th colspan="2">');html.push(title);html.push('</th></tr>');}
for(var n in va)
{html.push('<tr><td');var mater=this.findDiggerItem(n);html.push(' onmouseover="ui_showDiggerItemPopup(\'');html.push(mater.Name);html.push('\')" onmouseout="hidePopup2()">');html.push(mater.Caption);html.push('</td><td');if(va[n]<0)html.push(' style="color: red;"');html.push('>');html.push(va[n]);html.push('шт.</td></tr>');count++;}
if(count==0)
{html.push('<tr><td colspan="2" align="center">Нет ингредиентов или других компонентов.</td></tr>');}
html.push('</table>');return html.join('');}
this.getRecipeTable=function(va,additive,title)
{var html=[];var count=0;html.push('<table class="proshka">');if(title!=null)
{html.push('<tr><th');if(additive!=null)
{html.push(' colspan="');html.push((additive==false||additive==true||additive=='4basket')?3:2);html.push('"');}
html.push('>');html.push(title);html.push('</th></tr>');}
for(var n in va)
{html.push('<tr><td');var rd=va[n];var mater=rd.recipe.Item;var place=this.findRecipePlace(rd.recipe.PlaceName);html.push(' onclick="ui_showItemInView(\'');html.push(mater.Name);html.push('\')" onmouseover="ui_showDiggerItemPopup(\'');html.push(mater.Name);html.push('\')" onmouseout="hidePopup2()">');if(place==null)html.push('<font color="red">bad place</font>');else html.push(place.Caption);html.push(': ');html.push(mater.Caption);html.push('</td>');if(additive!=null)
{if(additive==false||additive==true||additive=='4basket')
{html.push('<td');if(va[n]<0)html.push(' style="color: red;"');html.push('>');html.push((rd.count<65535)?rd.count:'?');html.push('шт.</td>');}
html.push('<td>');if(additive=='2basket')
{html.push('<small><a title="Добавить в корзину" href="javascript:;" onclick="ui_addRecipeToBasket(\'');html.push(rd.recipe.PlaceName);html.push('\', \'');html.push(rd.recipe.Item.Name);html.push('\')">Добавить</a></small>');}
else if(additive=='4basket')
{html.push('<small><a title="Убрать из корзины" href="javascript:;" onclick="ui_removeRecipeFromBasket(\'');html.push(rd.recipe.PlaceName);html.push('\', \'');html.push(rd.recipe.Item.Name);html.push('\')">Убрать</a></small>');}
else if(additive==true)
{html.push('<small><a href="javascript:;" onclick="ui_chooseRecipe(');html.push(n);html.push(', true)">Добавить</a></small>');}
else
{html.push('<small><a href="javascript:;" onclick="ui_chooseRecipe(');html.push(n);html.push(', false)">Убрать</a></small>');}
html.push('</td>');}
html.push('</tr>');count++;}
if(count==0)
{html.push('<tr><td align="center">Нет рецептов.</td></tr>');}
html.push('</table>');return html.join('');}
this.getSpliceArray=function(a,index)
{var r=[];for(var i=0;i<a.length;i++)
{if(i!=index)
{r.push(a[i]);}}
return r;}
this.chooseRecipe=function(index,additive)
{var from=additive?this.availr:this.chosenr;var to=additive?this.chosenr:this.availr;from[index].count--;var recipe=from[index].recipe;var it=-1;for(var i=0;i<to.length;i++)
{if(to[i].recipe==recipe)
{it=i;break;}}
if(it>=0)
{to[i].count++;}
else
{to.push({recipe:recipe,count:1});}
if(from[index].count==0)
{from=this.getSpliceArray(from,index);if(additive)this.availr=from;else this.chosenr=from;}
from=additive?this.availi:this.choseni;to=additive?this.choseni:this.availi;for(var i=0;i<recipe.RecipeParts.length;i++)
{var rp=recipe.RecipeParts[i];if(rp.Item in to)
{to[rp.Item]+=rp.Count;}
else
{to[rp.Item]=rp.Count;}
if(rp.Item in from)
{from[rp.Item]-=rp.Count;}
else
{from[rp.Item]=-rp.Count;}
if(from[rp.Item]==0)
{delete from[rp.Item];}}
this.refreshRecipesTab();}
this.getAllRecipes1TabHtml=function()
{var html=[];html.push('<div style="display: none; width: 100%;" id="');html.push(this.allRecipes1Id);html.push('"><table class="proshka" style="width: 100%"><tr><td width="220" valign="top">');var arp=this.data.RecipePlaces;for(var i=0;i<arp.length;i++)
{var rp=arp[i];html.push('<div style="font-size: 11px;"><a href="javascript:;" onclick="ui_openRecipesOfPlace(');html.push(i);html.push(')">');html.push(rp.Caption);html.push('</a></div>');}
html.push('</td><td>');html.push('<div style="height: 440px;overflow: auto;" id="')
html.push(this.allRecipesView1Id);html.push('"><font color="#a0a0a0">Выберите место сбора для просмотра.</font></div>');html.push('</td><td width="260">');html.push('<div style="width: 100%;" id="')
html.push(this.allRecipesItem1Id);html.push('"><font color="#a0a0a0">Выберите предмет для просмотра.</font></div>');html.push('</td></tr></table></div>');return html.join('');}
this.getItemTable=function(va,title,options)
{var html=[];var count=0;html.push('<table class="proshka">');if(title!=null)
{html.push('<tr><th');if(options&&options.action=='2basket')
{html.push(' colspan="2"');}
html.push(title);html.push('</th></tr>');}
for(var n in va)
{html.push('<tr><td');var mater=va[n];html.push(' onclick="ui_showItemInView(\'');html.push(mater.Name);html.push('\')" onmouseover="ui_showDiggerItemPopup(\'');html.push(mater.Name);html.push('\')" onmouseout="hidePopup2()">');html.push(mater.Caption);html.push('</td>');if(options&&options.action=='2basket')
{html.push('<td><small><a title="Добавить в корзину" href="javascript:;" onclick="ui_addItemToBasket(\'');html.push(mater.Name);html.push('\')">Добавить</a></small></td>');}
html.push('</tr>');count++;}
if(count==0)
{html.push('<tr><td align="center">Нет предметов.</td></tr>');}
html.push('</table>');return html.join('');}
this.openRecipesOfPlace=function(index)
{var va=[];var place=this.data.RecipePlaces[index];var placeName=place.Name;for(var i in this.recipes)
{var recipe=this.recipes[i];if(recipe.PlaceName==placeName)
{va.push({recipe:recipe,count:0});}}
var html=[];var title=[place.Caption];if(place.Description.length>0)
{title.push('<div style="font-size: 80%; font-weight: normal; border-top: 1px dashed #999; padding: 2px; margin: 0;">');title.push(place.Description.join('<br />'));title.push('</div>');}
html.push(this.getRecipeTable(va,'2basket',title.join('')));document.getElementById(this.allRecipesView1Id).innerHTML=html.join('');}
this.getAllRecipes2TabHtml=function()
{var html=[];html.push('<div style="display: none; width: 100%;" id="');html.push(this.allRecipes2Id);html.push('"><table class="proshka" style="width: 100%"><tr><td width="220">');var arp=this.data.Categories;for(var i=0;i<arp.length;i++)
{var rp=arp[i];if(!rp.hasRecipes)continue;html.push('<div style="font-size: 11px;"><a href="javascript:;" onclick="ui_openRecipesOfCategory(');html.push(i);html.push(')">');html.push(rp.Caption);html.push('</a></div>');}
html.push('</td><td>');html.push('<div style="height: 440px;overflow: auto;" id="')
html.push(this.allRecipesView2Id);html.push('"><font color="#a0a0a0">Выберите категорию для просмотра.</font></div>');html.push('</td><td width="260">');html.push('<div style="width: 100%;" id="')
html.push(this.allRecipesItem2Id);html.push('"><font color="#a0a0a0">Выберите предмет для просмотра.</font></div>');html.push('</td></tr></table></div>');return html.join('');}
this.openRecipesOfCategory=function(index)
{var va=[];var cat=this.data.Categories[index];for(var i in this.items)
{var itm=this.items[i];if(itm.Category==cat&&itm.Recipes.length>0)
{va.push(itm);}}
var html=[];var title=[cat.Caption];if(cat.Description.length>0)
{title.push('<div style="font-size: 80%; font-weight: notmal; border-top: 1px dashed #999; padding: 2px; margin: 0;">');title.push(cat.Description.join(cat.NewLineToBR?'<br />':''));title.push('</div>');}
html.push(this.getItemTable(va,title.join(''),{id:'',action:'2basket'}));document.getElementById(this.allRecipesView2Id).innerHTML=html.join('');}
this.getAllItemsTabHtml=function()
{var html=[];html.push('<div style="display: none; width: 100%;" id="');html.push(this.allItemsId);html.push('"><table class="proshka" style="width: 100%"><tr><td width="220">');var arp=this.data.Categories;for(var i=0;i<arp.length;i++)
{var rp=arp[i];html.push('<div style="font-size: 11px;"><a href="javascript:;" onclick="ui_openItemsOfCategory(');html.push(i);html.push(')">');html.push(rp.Caption);html.push('</a></div>');}
html.push('</td><td>');html.push('<div style="height: 440px;overflow: auto;" id="')
html.push(this.allItemsViewId);html.push('"><font color="#a0a0a0">Выберите категорию для просмотра.</font></div>');html.push('</td><td width="260">');html.push('<div style="width: 100%;" id="')
html.push(this.allItemsItemId);html.push('"><font color="#a0a0a0">Выберите предмет для просмотра.</font></div>');html.push('</td></tr></table></div>');return html.join('');}
this.openItemsOfCategory=function(index)
{var cat=this.data.Categories[index];var html=[];var title=['<div style="width: 100%;">',cat.Caption,'</div>'];if(cat.Description.length>0)
{title.push('<div style="font-size: 80%; font-weight: normal; border-top: 1px dashed #999; padding: 2px; margin: 0; width: 100%;">');title.push(cat.Description.join(cat.NewLineToBR?'<br />':''));title.push('</div>');}
html.push(this.getItemTable(cat.Items,title.join(''),{id:'',action:'2basket'}));document.getElementById(this.allItemsViewId).innerHTML=html.join('');}
this.addRecipeToBasket_Core=function(recipe,count)
{var it=-1;var total=count;for(var i=0;i<this.basket.length;i++)
{if(this.basket[i].recipe==recipe)
{it=i;break;}}
if(it>=0)
{total+=this.basket[it].count;this.basket[it].count=total;}
else
{this.basket.push({recipe:recipe,count:count});it=this.basket.length-1;}
if(total==0)
{this.basket=this.getSpliceArray(this.basket,it);}}
this.getBasketSummary=function()
{var html=[];var ingr={};var price=0;for(var i in this.basket)
{var def=this.basket[i];var rps=def.recipe.RecipeParts;var count=def.count;if(def.recipe.Price>0)
{price+=def.recipe.Price*count;}
for(var j in rps)
{var rp=rps[j];var idef={name:rp.Item,item:this.items[rp.Item],count:count*rp.Count};if(idef.name in ingr)
{ingr[idef.name].count+=idef.count;}
else
{ingr[idef.name]=idef;}}}
html.push('<table class="proshka">');html.push('<tr><th>Компонент</th><th>Количество</th></tr>');var empty=true;if(price>0)
{html.push('<tr><td scope="row">Стоимость сборки');html.push('</td><td style="text-align: center;">');html.push(price);html.push(' кр.</td></tr>');empty=false;}
for(var i in ingr)
{var idef=ingr[i];html.push('<tr><td scope="row">');html.push(idef.item.Caption);html.push('</td><td style="text-align: center;">');html.push(idef.count);html.push(' шт.</td></tr>');empty=false;}
if(empty)
{html.push('<tr><td colspan="2" style="text-align: center; color: #a0a0a0;">');html.push('Ничего не требуется.');html.push('</td></tr>');empty=false;}
html.push('</table>');return html.join('');}
this.refreshBasketTab=function()
{document.getElementById(this.basketItemsViewId).innerHTML=this.getRecipeTable(this.basket,'4basket','Корзина');document.getElementById(this.basketSummaryId).innerHTML=this.getBasketSummary();}
this.addRecipeToBasket=function(placeName,itemName)
{var itm=this.items[itemName];if(!('Recipes'in itm))return;for(var rn in itm.Recipes)
{var recipe=itm.Recipes[rn];if(recipe.PlaceName==placeName)
{this.addRecipeToBasket_Core(recipe,1);break;}}
this.refreshBasketTab();}
this.removeRecipeFromBasket=function(placeName,itemName)
{var itm=this.items[itemName];if(!('Recipes'in itm))return;for(var rn in itm.Recipes)
{var recipe=itm.Recipes[rn];if(recipe.PlaceName==placeName)
{this.addRecipeToBasket_Core(recipe,-1);break;}}
this.refreshBasketTab();}
this.addItemToBasket=function(itemName)
{var itm=this.items[itemName];if(!('Recipes'in itm))return;for(var rn in itm.Recipes)
{var recipe=itm.Recipes[rn];this.addRecipeToBasket_Core(recipe,1);}
this.refreshBasketTab();}
this.getBasketTabHtml=function()
{var html=[];html.push('<div style="display: none; width: 100%;" id="');html.push(this.basketId);html.push('"><table class="proshka" style="width: 100%"><tr>');html.push('<td>');html.push('<div id="')
html.push(this.basketItemsViewId);html.push('">');html.push(this.getRecipeTable(this.basket,'4basket','Корзина'));html.push('</div>');html.push('</td></tr>');html.push('<tr>');html.push('<td>');html.push('<div id="')
html.push(this.basketSummaryId);html.push('">');html.push(this.getBasketSummary());html.push('</div>');html.push('</td></tr></table></div>');return html.join('');}
this.getChapterHtml=function()
{var html=[];html.push('<h5>Конструктор диггера</h5>');html.push('<div id="');html.push(this.tabsId);html.push('">');html.push(this.getDiggerTabsHtml());html.push('</div>');html.push('<div id="');html.push(this.divId);html.push('">');html.push(this.getKnapsackTabHtml());html.push(this.getRecipesTabHtml());html.push(this.getAllRecipes1TabHtml());html.push(this.getAllRecipes2TabHtml());html.push(this.getAllItemsTabHtml());html.push(this.getBasketTabHtml());html.push('</div>');return html.join('');}}
function Underground()
{this.offline=false;this.titleSuffix=' - '+document.title;this.knownChapters={};this.welcomeDungeon=new WelcomeDungeon();this.activeDungeon=this.welcomeDungeon;this.diggerConstructor=new DiggerConst();this.resources=[this.diggerConstructor];this.externals=[new ExternalResource('http://tarmans.kombats.ru/forum/index.php?showtopic=9159','Букмекерская контора Хранителей Азарта Армады',['Существует множество разнообразных видов прогноза - от прогноза погоды до систем расчета поведения групп людей в определенных ситуациях. Но, пожалуй, самой популярной темой для прогнозирования всегда был спорт и все, что с ним связано. Следует признать, что умением прогнозировать правильно обладают далеко не все, но те, кто в этом силен, легко могут существенно увеличить свой капитал. А помогут им в этом Хранители Азарта Армады - они знают о прогнозировании и о спорте все.','Букмекерская контора Армады предлагает Вам возможность сделать ставки на все самые важные и интересные события в мире спорта - хоккей, теннис, футбол и многое другое. Играйте и выигрывай!'],true),new ExternalResource('http://demonscity.combats.ru/forum.pl?id=1172503495&n=sales','Гильдии Тёмных Земель',['В чужой монастырь со своим уставом не суйся. Так как заработать на Территории Тьмы, заработать кредиты, а не очередную травму? Маги, наемники, лекари и торговцы, специально для ващего сообщества были созданы Гильдии Темных Земель.','Что же вам дает вступление кроме ограничений? Поддержку Армады, слаженность команды и помощь в поиске клиентов. Торгуйте, лечите, колдуйте, калечьте по высоким ценам, демпингу не место во Тьме.'],true)];this.loadedDungeons={};this.loadedScripts={};this.objects={};this.mobs={};this.imagesToBePreloaded=[];this.preloader=null;this.decoratedOpacityStyle='opacity: 0.3; -moz-opacity: 0.3; KhtmlOpacity: 0.3;filter:alpha(opacity = 30, style = 4);';this.activeChapterHeader=null;this.pngCls=(msie&&!msie7)?'objectImage':'';this.pngClsAttr=(msie&&!msie7)?' class="objectImage"':'';this.settings={cellWidth:40,cellHeight:40,cellLocationShift:0,cellSizeShift:msie?(msie7?4:0):4,monsterPositions:[4,21,12],jsBase:'/head/?r=',mobImagesUrl:'/i/cache/?r=ugmob/',objectImagesUrl:'/i/cache/?r=ugobj/',blankImageUrl:'/i/cache/?r=blank.gif',cellMarkImageUrl:'/i/cache/?r=ugetc/cellmark.gif',compassLocation:'/i/cache/?r=ugetc/compass/',loadingImageUrl:'/i/cache/?r=loading.gif',look:0,rotation:0,zoom:100,highlightObjectImage:'entrancepoint_green1.gif',mapsNavDisplay:'none',lookTexts:['','Север','Восток','Юг','Запад'],compass:['','compas_N','compas_O','compas_S','compas_W'],levelColors:['Red','Green','SpringGreen','Gold','Magenta','RoyalBlue','Yellow','GreenYellow','DeepPink','DeepSkyBlue','Orange','DarkTurquoise','LawnGreen','Violet','DodgerBlue'],chapterHeaderPlace:'/i/cache/?r=ugetc/',chapterHeaders:{welcome:{name:'welcome',tw:286,th:124,rw:289,rh:180},novice:{name:'novice',tw:261,th:72,rw:367,rh:180},capital:{name:'capital',tw:278,th:111,rw:299,rh:180},angel:{name:'angels',tw:165,th:79,rw:284,rh:180},sand:{name:'sand',tw:179,th:100,rw:277,rh:180},emerald:{name:'emeralds',tw:287,th:118,rw:312,rh:180},demons:{name:'demons',tw:217,th:68,rw:302,rh:180},demons:{name:'demons',tw:217,th:68,rw:302,rh:180},altar:{name:'bloodaltar',tw:153,th:128,rw:263,rh:180},bookshop:{name:'bookshop',tw:180,th:111,rw:302,rh:180},diggerconst:{name:'constructor',tw:247,th:130,rw:307,rh:180},sentinel:null},centinel:'this is last setting always!'};this.setHash=function()
{var hash='';hash+=this.activeDungeon.getHash();window.location.hash=hash;document.title=this.activeDungeon.Caption+this.titleSuffix;}
this.renderChaptersCore=function()
{this.imagesToBePreloaded=[];if(!(this.activeDungeon.Name in this.knownChapters))
{this.addChapter(this.activeDungeon.Name,this.getActiveChapterHtml());this.activeDungeon.afterConstruction();}
document.getElementById('nav').innerHTML=this.getUndergroundTabsHtml();for(var i in this.knownChapters)
{var v=(this.knownChapters[i]==this.activeDungeon.Name)?'':'none';document.getElementById('chap_'+this.knownChapters[i]).style.display=v;}
this.setHash();this.stopWait();this.changeChapterHeader();}
var headerFxBegin=function()
{if(msie)document.getElementById('header').filters[0].apply();}
var headerFxEnd=function()
{if(msie)document.getElementById('header').filters[0].play();}
var setImgProps=function(img,src,w,h,m)
{if(m==null)m='scale';img.width=w;img.height=h;if(msie&&!msie7)
{img.runtimeStyle.filter=['progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'',src,'\',sizingMethod=\'',m,'\')'].join('');}
else
{img.src=src;}}
this.applyChapterHeader=function()
{var d=this.activeChapterHeader;d.loaded=true;var htitle=document.getElementById('header_dungeontitle');var hrightpart=document.getElementById('header_rightpart');headerFxBegin();setImgProps(htitle,d.imagesToBePreloaded[0],d.tw,d.th,'image');setImgProps(hrightpart,d.imagesToBePreloaded[1],d.rw,d.rh,'image');headerFxEnd();}
this.changeChapterHeader=function()
{var d=(this.activeDungeon.Name in this.settings.chapterHeaders)?this.settings.chapterHeaders[this.activeDungeon.Name]:this.settings.chapterHeaders.welcome;if(d==this.activeChapterHeader)return;this.activeChapterHeader=d;if(d.loaded)
{this.applyChapterHeader();}
else
{d.imagesToBePreloaded=[[this.settings.chapterHeaderPlace,d.name,'/','header-dungeontitle.png'].join(''),[this.settings.chapterHeaderPlace,d.name,'/','header-rightpart.png'].join('')];d.preloader=new ImagePreloader(d.imagesToBePreloaded,function(){underground.applyChapterHeader();});}}
this.renderChapters=function()
{hideMenu();hidePopup2();this.startWait();this.activeDungeon.needData(function(){underground.renderChapters2();});}
this.renderChapters2=function()
{if(msie&&!msie7&&this.imagesToBePreloaded.length>0)
{this.preloader=new ImagePreloader(this.imagesToBePreloaded,function(){underground.renderChaptersCore();});}
else
{this.renderChaptersCore();}}
this.addChapter=function(name,html)
{this.knownChapters[name]=name;var r=[];r.push('<div id="chap_');r.push(name);r.push('">');r.push(html);r.push('</div>');html=r.join('');var dsurface=document.getElementById('dsurface');if(dsurface.insertAdjacentHTML)
{dsurface.insertAdjacentHTML('beforeEnd',html);}
else
{dsurface.innerHTML=dsurface.innerHTML+html;}}
this.getUndergroundTabsHtml=function()
{var html=[];html.push('<li><a href="javascript:;" onclick="underground.chooseDungeon()" title="Нажмите здесь, чтобы выбрать другое подземелье">');html.push('Выбрать подземелье');html.push('</a></li>');html.push('<li class="activeLink"><a href="javascript:;" onclick="underground.hideDungeonPages()">');html.push(this.activeDungeon.Caption);html.push('</a></li>');html.push(this.activeDungeon.getUndergroundTabsHtml());return html.join('');}
this.chooseDungeon=function()
{this.activeDungeon=this.welcomeDungeon;this.renderChapters();}
this.openDungeon=function(chapterIndex)
{this.activeDungeon=this.knownDungeons[chapterIndex];this.settings.rotation=this.activeDungeon.rotation;this.renderChapters();}
this.openResource=function(chapterIndex)
{this.activeDungeon=this.resources[chapterIndex];this.renderChapters();}
this.hideDungeonPages=function()
{hidePopup2();this.activeDungeon.hidePages();}
this.getActiveChapterHtml=function()
{return this.activeDungeon.getChapterHtml();}
this.renderingRequired=function()
{this.handleHash();this.initializeSurface();this.renderChapters();}
var initialized=false;this.domReadyCore=function()
{initialized=true;if('knownDungeons'in this)
{this.renderingRequired();}}
this.domReady=this.domReadyCore;this.knowAbout=function(collectedData)
{this.knownDungeons=collectedData.KnownDungeons;this.extendKnownDungeons();if(initialized)
{this.renderingRequired();}}
this.handleHash=function(hash)
{if(hash==null)hash=window.location.hash;var suffix='';if(hash.length>0&&hash.charAt(0)=='#')hash=hash.substr(1);var pti=hash.indexOf('.');if(pti>=0)
{suffix=hash.substr(pti+1);hash=hash.substr(0,pti);}
if(hash=='')return;for(var i in this.knownDungeons)
{var d=this.knownDungeons[i];if(d.Name==hash)
{this.activeDungeon=d;if(suffix!='')d.handleHash(suffix);return;}}
for(var i in this.resources)
{var d=this.resources[i];if(d.Name==hash)
{this.activeDungeon=d;if(suffix!='')d.handleHash(suffix);return;}}}
this.extendKnownDungeons=function()
{for(var i in this.knownDungeons)
{this.knownDungeons[i].index=i;KnownDungeonEntender(this.knownDungeons[i]);}}
this.initializeSurface=function()
{document.getElementById('dsurface').innerHTML='';}
this.addDungeon=function(dungeon)
{LoadedDungeonEntender(dungeon);this.loadedDungeons[dungeon.Name]=dungeon;}
this.changeFloor=function(floorIndex)
{this.activeDungeon.changeFloor(floorIndex);this.setHash();}
this.checkStatsMob=function(mobName,mobLevel)
{this.activeDungeon.checkStatsMob(mobName,mobLevel);}
this.getMobInfoHtml=function(mob,mi,count)
{var html='';var reward=mi.Reward;if(reward==0)
{for(var i=0;i<mob.Instances.length;i++)
{var ami=mob.Instances[i];if(ami.Level==mi.Level&&ami.Reward>0)
{reward=ami.Reward;break;}}}
html+='<div class="hintviewcaption"><nobr>';if(mi.Align!='0')
{html+='<img border="0" width="12" height="15" src="http://img.combats.ru/i/align'+mi.Align+'.gif" />';}
html+='<b>'+mob.Caption+'</b>';html+=' ['+mi.Level+']';if(count!=null)html+=', '+count+' шт.';if(reward>0)html+=', x'+reward+'</u> ед. награды';if(mi.HitPoints>0)html+=', <u>'+mi.HitPoints+'</u>HP';html+='</nobr></div>';html+='<div style="width: 100%;"><div style="float: left;">';if(mi.HitPoints>0)
{html+='<div style="position: relative; width: 60px; height: 9px; background: green url(http://img.combats.ru/i/misc/bk_life_green.gif) repeat; padding: 0; margin: 0; overflow: hidden;">';html+='<nobr style="position: absolute; left: 5px; top: 0; padding: 0; margin: 0; height: 9px; line-height: 1; font-family: Verdana,Arial,Helvetica,Tahoma,sans-serif; font-size: 9px; font-weight: bold; color: #fff;">'+mi.HitPoints+'</nobr>';html+='</div>';}
html+='<img'+underground.pngClsAttr+' border="0" width="60" height="110" src="'+mob.ImageUrl+'" lowsrc="'+underground.settings.loadingImageUrl+'" />';html+='</div>';html+='<div style="margin-left: 8px; padding: 4px;">';if(count!=null)
{html+='В количестве <b>'+count+'</b> шт.<br /><br />';}
if(mi.Strength!=0)html+='Сила: '+mi.Strength+'<br />';if(mi.Dexterity!=0)html+='Ловкость: '+mi.Dexterity+'<br />';if(mi.Intuition!=0)html+='Интуиция: '+mi.Intuition+'<br />';if(mi.Endurance!=0)html+='Выносливость: '+mi.Endurance+'<br />';if(mi.Intellect!=0)html+='Интеллект: '+mi.Intellect+'<br />';if(mi.Wisdom!=0)html+='Мудрость: '+mi.Wisdom+'<br />';html+='</div>';if(mi.Move)html+='<div style="color: red">Монстр перемещается, его расположение заранее неизвестно.</div>';html+='</div>';var desc=mi.Description;if(desc.length==0)desc=mob.Description;if(desc.length>0)
{html+='<div style="border-top: 1px dashed gray; padding: 2px;">'+desc.join('<br />')+'</div>';}
return html;}
this.getCellObjectInfoHtml=function(o,i)
{var caption=i.Caption;if(caption=='')caption=o.Caption;var desc=i.Description;if(desc.length==0)desc=o.Description;var html='';html+='<div class="hintviewcaption"><nobr>';html+=caption;html+='</nobr></div>';html+='<div style="width: 100%;"><div style="float: left;">';html+='<img'+underground.pngClsAttr+' border="0" width="'+o.BW+'" height="'+o.BH+'" src="'+o.ImageUrl+'" lowsrc="'+underground.settings.loadingImageUrl+'" />';html+='</div>';html+='<div style="margin-left: 8px; padding: 4px;">';if(desc.length>0)
{html+=desc.join('<br />');}
html+='</div></div>';return html;}
this.findObjectAndInstance=function(objectName,instanceName)
{var obj=this.objects[objectName];var instance=obj.Instances[0];for(var i in obj.Instances)
{if(obj.Instances[i].Name==instanceName)
{instance=obj.Instances[i];break;}}
return{obj:obj,instance:instance};}
this.findMobAndInstance=function(name,level,align,tag)
{var mob=this.mobs[name];var mi=mob.Instances[0];for(var i in mob.Instances)
{cmi=mob.Instances[i];if(cmi.Level==level&&cmi.Align==align&&cmi.Tag==tag)
{mi=cmi;break;}}
return{mob:mob,mi:mi};}
this.showMobRefPopup=function(name,level,align,tag,count)
{var midata=this.findMobAndInstance(name,level,align,tag);var html=[];var mobInfo=document.getElementById('mobInfo');html.push(this.getMobInfoHtml(midata.mob,midata.mi,count));html='<div style="width: 400px;">'+html+'</div>';cursorX+=40;cursorY+=16;showPopup(html);}
this.showObjRefPopup=function(objectName,instanceName)
{var oidata=this.findObjectAndInstance(objectName,instanceName);var html=this.getCellObjectInfoHtml(oidata.obj,oidata.instance);html='<div style="width: 400px;">'+html+'</div>';showPopup(html);}
this.getMobImage=function(mob)
{var r=(mob.SmImg!='')?mob.SmImg:mob.Name;return this.settings.mobImagesUrl+r+'.gif';}
this.getMobInstanceImage=function(mob,mobInstance)
{var r=(mobInstance.Image!='')?(this.settings.mobImagesUrl+mobInstance.Image+'.gif'):this.getMobImage(mob);return r;}
this.getCellObjectImage=function(obj)
{var r=(obj.SmImg!='')?obj.SmImg:obj.Name;return this.settings.objectImagesUrl+r+'.gif';}
this.getCellObjectInstanceImage=function(obj,objInstance)
{var r=this.getCellObjectImage(obj);return r;}
this.preloadMobImage=function(mob)
{this.imagesToBePreloaded.push(this.getMobImage(mob));for(var mobit in mob.Instances)
{this.imagesToBePreloaded.push(this.getMobInstanceImage(mob,mob.Instances[mobit]));}}
this.preloadCellObjectImage=function(obj)
{this.imagesToBePreloaded.push(this.getCellObjectImage(obj));for(var oin in obj.Instances)
{this.imagesToBePreloaded.push(this.getCellObjectInstanceImage(obj,obj.Instances[oin]));}}
this.installMobs=function(mobsData)
{for(var i in mobsData.Objects)
{var obj=mobsData.Objects[i];this.objects[obj.Name]=obj;if(msie&&!msie7)this.preloadCellObjectImage(obj);}
for(var i in mobsData.Mobs)
{var mob=mobsData.Mobs[i];this.mobs[mob.Name]=mob;if(msie&&!msie7)this.preloadMobImage(mob);}}
this.loadCostructorData=function(d)
{this.diggerConstructor.loadCostructorData(d);}
this.showWelcomePopup=function(d)
{if(d.Description.length==0)return;this.welcomeDungeon.showDescription(d.Description.join(d.NewLineToBR?'<br />':''));}
this.showDungeonPopup=function(dungeonIndex)
{this.showWelcomePopup(underground.knownDungeons[dungeonIndex]);}
this.showResourcePopup=function(dungeonIndex)
{this.showWelcomePopup(underground.resources[dungeonIndex]);}
this.showExternalPopup=function(dungeonIndex)
{this.showWelcomePopup(underground.externals[dungeonIndex]);}
this.getCellContentMenuHtml=function(cellDivId,x,y)
{return this.activeDungeon.getCellContentMenuHtml(cellDivId,x,y);}
this.showCellContextMenu=function(cellDivId,x,y)
{showMenu(this.getCellContentMenuHtml(cellDivId,x,y));}
this.hotSpot=function(cellDivId)
{this.activeDungeon.hotSpot(cellDivId);}
this.hideHotSpots=function()
{this.activeDungeon.hideHotSpots();}
this.zoomChanged=function()
{var proposedZoom=this.activeDungeon.getProposedZoom();if(proposedZoom==underground.settings.zoom)return;underground.settings.zoom=proposedZoom;for(var i in this.knownDungeons)
{this.knownDungeons[i].applyZoom(proposedZoom);}}
this.getIPoint=function(s)
{var x=s.charCodeAt(0)-'A'.charCodeAt(0);var y=parseInt(s.substr(1))-1;return{x:x,y:y};}
this.startWait=function()
{document.getElementById('header_progress').style.visibility='';}
this.stopWait=function()
{document.getElementById('header_progress').style.visibility='hidden';}}
function createAjaxRequest(f)
{var req=false;try
{if(typeof XMLHttpRequest!='undefined')
{req=new XMLHttpRequest();}
if(!req)
try
{req=new ActiveXObject("Msxml2.XMLHTTP");}
catch(e)
{try
{req=new ActiveXObject("Microsoft.XMLHTTP");}
catch(e)
{req=false;}}
if(!req)
{alert("Kill yourself or change the browser...");return'';}}
catch(e)
{alert("Request error:\n"+e.message);return'';}
if(f!=null)
{req.onreadystatechange=function()
{if(req.readyState==4)
{f(req.responseText);}}}
return req;}
function loadXMLDoc2(url,f)
{if(underground.offline)alert('bad call from '+loadXMLDoc2.caller);var req=createAjaxRequest(f);if(req)
{req.open("GET",url,(f!=null));req.send(null);return(f==null)?req.responseText:'';}
return false;}
function loadScript(url,f)
{if(underground.offline)alert('bad call from '+loadScript.caller);var f2=(f!=null)?(function(doc){eval(doc);f();}):null;var script=loadXMLDoc2(url,f2);if(f==null)
{if(script=='')
{alert('Ошибка загрузки скрипта '+url);return;}
eval(script);}}
function getMobPoint(index,totalCount)
{var pt;var MonsterPositions=underground.settings.monsterPositions;switch(totalCount)
{case 1:pt={x:underground.settings.cellWidth/2-8,y:underground.settings.cellHeight/2-13};break;case 2:pt={x:MonsterPositions[index],y:underground.settings.cellHeight/2-13};break;case 3:pt={x:MonsterPositions[index],y:(index<2)?0:underground.settings.cellHeight/2-8};break;default:pt={x:MonsterPositions[(index<2)?index:index-2],y:(index<2)?-3:underground.settings.cellHeight/2-((index==4)?10:4)};break;}
return pt;}
function getStatisticsCheckId(checkId,mobName,mobLevel)
{return[checkId,mobName,mobLevel].join('_');}
function getStatisticsHtml(legend,stats,checkId)
{var minlevel=21;var maxlevel=0;var mobCount=0;var checks=(checkId!='')
for(var mobn in stats)
{for(var l in stats[mobn])
{var level=parseInt(l);if(minlevel>level)minlevel=level;if(maxlevel<level)maxlevel=level;}
mobCount++;}
if(mobCount==0)
{return'';}
var html=['<center><fieldset><legend>',legend,'</legend>'];if(!checks)
{html.push('<br /><div class="htext" name="htext" width="100%;">');}
html.push('<table class="hintview"><tr>');if(checks)html.push('<th><!--checkboxes here--></th>');html.push('<th colspan="2" align="left">Название</th>');for(var level=minlevel;level<=maxlevel;level++)
{var color=underground.settings.levelColors[level];html=html.concat(['<th style="padding-left: 8px; padding-right: 8px; text-align: center; background-color: ',color,';">&nbsp;[',level,']&nbsp;</th>']);}
html.push('<th style="padding-left: 8px; padding-right: 8px; text-align: center;">Всего</th></tr>');for(var mobn in stats)
{var mob=underground.mobs[mobn];var count=0;var gid=getStatisticsCheckId(checkId,mob.Name,0);html.push('<tr>');if(checks)
{html=html.concat(['<td valign="center"><input type="checkbox" style="width:10px;height:10px;margin:0;padding:0;" id="',gid,'" onclick="ui_checkStatsMob(\'',mob.Name,'\', 0)" /></td>']);}
var img=underground.getMobImage(mob);html.push('<td>');if(checks)
{html=html.concat(['<label for="',gid,'">']);}
html.push('<img src="');html.push(img);html.push('" width="17" height="26" border="0" valign="absbottom" />');if(checks)
{html.push('</label>');}
html.push('</td><td>');if(checks)
{html=html.concat(['<label for="',gid,'">']);}
html.push(mob.Caption);if(checks)
{html.push('</label>');}
html.push('</td>');for(var level=minlevel;level<=maxlevel;level++)
{html.push('<td align="center" valign="center">');if(level in stats[mobn])
{var lid=getStatisticsCheckId(checkId,mob.Name,level);if(checks)
{html=html.concat(['<label for="',lid,'">']);}
html=html.concat(['<b>',stats[mobn][level],'</b>']);if(checks)
{html=html.concat(['</label><input type="checkbox" style="width:10px;height:10px;margin:0;padding:0;" id="',lid,'" onclick="ui_checkStatsMob(\'',mob.Name,'\', ',level,')" />']);}
count+=parseInt(stats[mobn][level]);}
else
{html.push('-');}
html.push('</td>');}
html=html.concat(['<td align="center" valign="center"><b>',count,'</b></td></tr>']);}
html.push('</table>');if(!checks)
{html.push('<br />&nbsp;</div>');}
html.push('</fieldset></center>');return html.join('');}
var underground=new Underground();function hidePopup2()
{hidePopup();underground.hideHotSpots();}
function ui_changeShopCategory(shopTDId,catDivId)
{var td=document.getElementById(shopTDId);for(var i=0;i<td.childNodes.length;i++)
{var div=td.childNodes[i];div.style.display=(div.id==catDivId)?'':'none';}}
function ui_changeJobsCategory(shopTDId,catDivId)
{ui_changeShopCategory(shopTDId,catDivId);}
function ui_changeDiggerTab(index)
{underground.diggerConstructor.changeDiggerTab(index);}
function ui_loadKnapsack(isChest)
{underground.diggerConstructor.loadKnapsack(isChest);}
function ui_clearKnapsack()
{if(!window.confirm('Вы действительно хотите очистить инвентарь?'))return;underground.diggerConstructor.cleanKnapsackFor(false,false);underground.diggerConstructor.cleanKnapsackFor(true,true);}
function ui_addToKnapsack(toChest)
{underground.diggerConstructor.addToKnapsack(toChest);}
function ui_loadKnapsackOK()
{underground.diggerConstructor.loadKnapsackOK();}
function ui_loadKnapsackCancel()
{underground.diggerConstructor.loadKnapsackCancel();}
function ui_addKnapsackOK()
{underground.diggerConstructor.addKnapsackOK();}
function ui_addKnapsackCancel()
{underground.diggerConstructor.addKnapsackCancel();}
function ui_showDiggerItemPopup(name)
{underground.diggerConstructor.showItemPopup(name);}
function ui_addIngr(toChest,name,count)
{underground.diggerConstructor.addIngr(toChest,name,count);}
function ui_openRecipesOfPlace(index)
{underground.diggerConstructor.openRecipesOfPlace(index);}
function ui_openRecipesOfCategory(index)
{underground.diggerConstructor.openRecipesOfCategory(index);}
function ui_openItemsOfCategory(index)
{underground.diggerConstructor.openItemsOfCategory(index);}
function ui_chooseRecipe(index,additive)
{underground.diggerConstructor.chooseRecipe(index,additive);}
function ui_checkStatsMob(mobName,mobLevel)
{underground.checkStatsMob(mobName,mobLevel);}
function ui_showItemInView(itemName)
{underground.diggerConstructor.showItemInView(itemName);}
function ui_recalculateUsualReward(counten,rewarden,maxcounten,bonus,maxFactor,maxrewarden)
{var counte=document.getElementById(counten);var rewarde=document.getElementById(rewarden);var maxcounte=document.getElementById(maxcounten);var maxrewarde=document.getElementById(maxrewarden);var count=parseInt(counte.value);if(isNaN(count)||count<0){count=0;}
var scount=count.toString();if(counte.value!=scount)counte.value=scount;rewarde.innerHTML=['<b>',count*bonus,'</b>'].join('');if(maxcounte!=null)
{var maxc=Math.floor(count*maxFactor);maxcounte.innerHTML=maxc;maxrewarde.innerHTML=maxc*bonus;}}
function ui_recalculateMobReward(rewarden)
{var rewarde=document.getElementById(rewarden);var total=0;for(var i=1;i<ui_recalculateMobReward.arguments.length;i+=2)
{var counten=ui_recalculateMobReward.arguments[i];var bonus=ui_recalculateMobReward.arguments[i+1];var counte=document.getElementById(counten);var count=parseInt(counte.value);if(isNaN(count)||count<0){count=0;}
var scount=count.toString();if(counte.value!=scount)counte.value=scount;total+=count*bonus;}
rewarde.innerHTML=['<b>',total,'</b>'].join('');}
function ui_addRecipeToBasket(placeName,itemName)
{underground.diggerConstructor.addRecipeToBasket(placeName,itemName);}
function ui_removeRecipeFromBasket(placeName,itemName)
{underground.diggerConstructor.removeRecipeFromBasket(placeName,itemName);}
function ui_addItemToBasket(itemName)
{underground.diggerConstructor.addItemToBasket(itemName);}
var zoomHandle=null;function ui_zoomChanged()
{if(zoomHandle!=null)clearTimeout(zoomHandle);zoomHandle=setTimeout('ui_zoomChanged2()',1200);}
function ui_zoomChanged2()
{if(zoomHandle!=null)
{clearTimeout(zoomHandle);zoomHandle=null;}
underground.zoomChanged();}
function ui_cellObjectAction(action,chapter,point)
{if(action==1&&chapter!='')
{underground.handleHash('#'+chapter);underground.renderChapters();underground.activeDungeon.changeFloor(underground.activeDungeon.activeFloorIndex);if(point!='')
{underground.activeDungeon.highlightPoint(point);}}}
function ui_toggleCellMarker(cellDivId,x,y)
{underground.activeDungeon.data.Floors[underground.activeDungeon.activeFloorIndex].toggleCellMarker(cellDivId,x,y);}
function ui_showCellMarkerPopup(markerIndex)
{underground.activeDungeon.data.Floors[underground.activeDungeon.activeFloorIndex].showCellMarkerPopup(markerIndex);}
function ui_toggleMapsNav()
{underground.settings.mapsNavDisplay=(underground.settings.mapsNavDisplay=='none')?'':'none';var c=getElementsByClass('mapsNav',document.getElementById('dsurface'),'div')
for(var i in c)
{c[i].style.display=underground.settings.mapsNavDisplay;}}
String.prototype.quote=function()
{var c,i,l=this.length,o='"';for(i=0;i<l;i+=1){c=this.charAt(i);if(c>=' '){if(c==='\\'||c==='"'){o+='\\';}
o+=c;}else{switch(c){case'\b':o+='\\b';break;case'\f':o+='\\f';break;case'\n':o+='\\n';break;case'\r':o+='\\r';break;case'\t':o+='\\t';break;default:c=c.charCodeAt();o+='\\u00'+Math.floor(c/16).toString(16)+
(c%16).toString(16);}}}
return o+'"';};var json=function()
{this.unserialize=function(s){return eval(s);};var addslashesRepl={'\r':'\\r','\n':'\\n','\t':'\\t','\0':'\\0','\\':'\\\\','\b':'\\b','\f':'\\f'};var addslashes=function(s)
{return s.toString().quote();}
var isArray=function(testObject)
{return testObject&&!(testObject.propertyIsEnumerable('length'))&&typeof testObject==='object'&&typeof testObject.length==='number';}
var serializeArray=function(o)
{var s=['['];var first=true;for(var i=0;i<o.length;i++)
{var v=o[i];if(first)first=false;else s.push(',');s.push(this.serialize(v));}
s.push(']');return s.join('');}
var serializeObject=function(o)
{var s=['{'];var first=true;for(var i in o)
{var v=o[i];if(first)first=false;else s.push(',');s.push('"');s.push(addslashes(i));s.push('":');s.push(this.serialize(v));}
s.push('}');return s.join('');}
this.serialize=function(o)
{switch(typeof(o))
{case'string':return['"',addslashes(o),'"'].join('');case'number':return o.toString();case'object':if(isArray(o))return serializeArray(o);return serializeObject(o);case'boolean':return o?'true':'false';}
return'jsonTypeError';}
return this;}();attachDomLoaded(function(){underground.domReady();});