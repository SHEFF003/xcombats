
var isAHTMLEditorMode;var msie=false;var opera=false;var msie7=false;var _domLoaded=[];function attachDomLoaded(f)
{_domLoaded.push(f);}
function detectClient()
{var userAgent=navigator.userAgent;msie=(userAgent.indexOf('MSIE')>0);opera=(navigator.appName=='Opera')||(userAgent.indexOf('Opera')>0);msie7=(userAgent.indexOf('MSIE 7.')>0);if(typeof(isAHTMLEditorMode)=='undefined')
{isAHTMLEditorMode=false;}}
detectClient();if(typeof HTMLElement!='undefined'&&!HTMLElement.prototype.insertAdjacentElement&&typeof window.Range!='undefined'&&typeof Range.prototype.createContextualFragment=='function')
{HTMLElement.prototype.insertAdjacentElement=function(where,parsedNode)
{switch(where)
{case'beforeBegin':this.parentNode.insertBefore(parsedNode,this)
break;case'afterBegin':this.insertBefore(parsedNode,this.firstChild);break;case'beforeEnd':this.appendChild(parsedNode);break;case'afterEnd':if(this.nextSibling)
this.parentNode.insertBefore(parsedNode,this.nextSibling);else
this.parentNode.appendChild(parsedNode);break;}}
HTMLElement.prototype.insertAdjacentHTML=function(where,htmlStr)
{var r=this.ownerDocument.createRange();r.setStartBefore(this);var parsedHTML=r.createContextualFragment(htmlStr);this.insertAdjacentElement(where,parsedHTML)}
HTMLElement.prototype.insertAdjacentText=function(where,txtStr)
{var parsedText=document.createTextNode(txtStr)
this.insertAdjacentElement(where,parsedText)}}
function init(){if(arguments.callee.done)return;arguments.callee.done=true;if(_timer)clearInterval(_timer);for(var i=0;i<_domLoaded.length;i++)_domLoaded[i]();};if(document.addEventListener){document.addEventListener("DOMContentLoaded",init,false);}
if(/WebKit/i.test(navigator.userAgent)){var _timer=setInterval(function(){if(/loaded|complete/.test(document.readyState)){init();}},10);}
window.onload=init;var hiddenTextIndex=0;var hiddenTexts=[];var tabsDivIndex=0;var tabsDivPrefix='$tabs$div$_';if(typeof(addEvent)=='undefined')
{function addEvent(elm,evType,fn,useCapture)
{if(elm.addEventListener)
{elm.addEventListener(evType,fn,useCapture);return true;}
else if(elm.attachEvent)
{var r=elm.attachEvent('on'+evType,fn);return r;}
else
{elm['on'+evType]=fn;}}}
function getHTextHtml(hti)
{var html=['<div><a class="blike" href="javascript:;" onclick="toggleHText(',hti,')">'];var ht=hiddenTexts[hti];if(ht.hidden)
{html.push('Показать</a></div>');}
else
{html.push('Скрыть</a></div>');html.push('<div style="padding: 2px;">');html.push(ht.data);html.push('</div>');}
return html.join('');}
function toggleHText(hti)
{var ht=hiddenTexts[hti];ht.hidden=!ht.hidden;ht.elt.innerHTML=getHTextHtml(hti);hideHTextsOf(ht.elt);rebuildTabsIn(ht.elt);}
function hideHText(e)
{hiddenTextIndex++;hiddenTexts[hiddenTextIndex]={hidden:true,elt:e,data:e.innerHTML};e.innerHTML=getHTextHtml(hiddenTextIndex);e.className='htext2';}
function getElementsByClass(searchClass,node,tag)
{var classElements=[];if(node==null)
node=document;if(tag==null)
tag='*';var els=node.getElementsByTagName(tag);var elsLen=els.length;var pattern=new RegExp("(^|\\s)"+searchClass+"(\\s|$)");for(var i=0;i<elsLen;i++){if(pattern.test(els[i].className))
{classElements.push(els[i]);}}
return classElements;}
function getChildrenByTag(node,tag)
{var r=[];if(node==null)
node=document;if(tag==null)
tag='*';else
tag=tag.toLowerCase();var els=node.childNodes;var elsLen=els.length;for(var i=0;i<elsLen;i++){var e=els[i];if(e.nodeType==1&&(tag=='*'||e.tagName.toLowerCase()==tag))
{r.push(e);}}
return r;}
function getChildrenByClass(searchClass,node,tag)
{var classElements=[];if(node==null)
node=document;if(tag==null)
tag='*';else
tag=tag.toLowerCase();var els=node.childNodes;var elsLen=els.length;var pattern=new RegExp("(^|\\s)"+searchClass+"(\\s|$)");for(var i=0;i<elsLen;i++){var e=els[i];if(e.nodeType==1&&(tag=='*'||e.tagName.toLowerCase()==tag)&&pattern.test(e.className))
{classElements.push(e);}}
return classElements;}
function hideHTextsOf(b)
{var coll=getElementsByClass('htext',b,'div');for(var i=0;i<coll.length;i++)
{hideHText(coll[i]);}}
function hideHTexts()
{hideHTextsOf(document.body);}
function rebuildTabsOf(tabs)
{tabsDivIndex++;tabs.id=tabsDivPrefix+tabsDivIndex;var enames=getChildrenByClass('tabn',tabs,'div');var ec=getChildrenByClass('tabc',tabs,'div');var thtml=[];thtml.push('<div style="width:100%;overflow:hidden;"><div class="dtab" style="right: 0; width:100%;"><ul class="dtab">');for(var i=0;i<enames.length;i++)
{if(i>=ec.length)break;var ecw=ec[i];if(msie&&!msie7)
{ecw=document.createElement('div');ecw.className='tabc_wrapper';ecw.style.width='100%';ecw.style.display='none';ec[i].style.display='';ecw.innerHTML=ec[i].outerHTML;if(tabs.replaceChild)
{tabs.replaceChild(ecw,ec[i]);}
else
{tabs.insertBefore(ecw,ec[i]);tabs.removeChild(ec[i]);}}
thtml.push('<li');if(i==0)
{thtml.push(' class="activeLink"');ecw.style.display='';}
else
{ecw.style.display='none';}
thtml.push('><a href="javascript:;" onclick="ui_switchAHTMLETab(\'');thtml.push(tabs.id);thtml.push('\', ');thtml.push(i);thtml.push(')"');thtml.push('>');thtml.push(enames[i].innerHTML);thtml.push('</a></li>');}
thtml.push('</ul></div></div>');thtml=thtml.join('');for(var i=enames.length-1;i>=0;i--)
{tabs.removeChild(enames[i]);}
for(var i=tabs.childNodes.length-1;i>=0;i--)
{if(tabs.childNodes[i].nodeType==3)
{tabs.removeChild(tabs.childNodes[i]);}}
if(tabs.insertAdjacentHTML)
{tabs.insertAdjacentHTML('afterBegin',thtml);}
else
{tabs.innerHTML=thtml+tabs.innerHTML;}
tabs.className='tabs2';}
function ui_switchAHTMLETab(parentn,index)
{var parent=document.getElementById(parentn);var ec=getChildrenByClass((msie&&!msie7)?'tabc_wrapper':'tabc',parent,'div');var ul=getElementsByClass('dtab',parent,'ul')[0];var fs=getChildrenByTag(ul,'font');if(fs.length>0)ul=fs[0];var lis=getChildrenByTag(ul,'li');for(var i=ec.length-1;i>=0;i--)
{if(i>=lis.length)continue;var active=(i==index);ec[i].style.display=active?'':'none';lis[i].className=active?'activeLink':'';}}
function rebuildTabsIn(b)
{var coll=getElementsByClass('tabs',b,'div');for(var i=0;i<coll.length;i++)
{rebuildTabsOf(coll[i]);}}
function rebuildTabs()
{rebuildTabsIn(document.body);}
function aHtmlView_Init()
{hideHTexts();rebuildTabs();}
if(!isAHTMLEditorMode)
{attachDomLoaded(aHtmlView_Init);}