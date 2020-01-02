// JScript source code

// common JavaScript code 
// Author: Akzhan Abdulin

// This code is common to all my scripts.

var dcACommonScriptVersion = 210;


var popupDivId = "popupDiv";
var defaultTipOpacity = 85;
var popupXOffset = 30;
var popupYOffset = 10;
var popupWidth = 200;
var popupTimer = "";
var popupActive = false;

var menuDivId = "menuDiv";
var defaultMenuOpacity = 92;
var menuXOffset = -64;
var menuYOffset = 5;
var clearMenuOnceWhenClosed = false;
var dc_menuItemCount = 0;

var cursorX = 0;
var cursorY = 0;
var moz = (document.getElementById && !document.all);

// string manipulation

function replacestr(source, what, replaceWith)
{
  var retValue = [];
  var i;
  while (true)
  {
    i = source.indexOf(what);
    if (i >= 0)
    {
      retValue.push(source.substr(0, i));
      retValue.push(replaceWith);
      source = source.substr(i + what.length);
    }
    else
    {
      break;
    }
  }
  retValue.push(source);
  return retValue.join('');
}

var htmlEntities = new Array(
	{ what: '<', replaceWith: '&lt;' },
	{ what: '>', replaceWith: '&gt;' },
	{ what: "'", replaceWith: '&apos;' },
	{ what: '"', replaceWith: '&quot;' }
	);

function htmlstring(s)
{
	for (var i = 0; i < htmlEntities.length; i++)
	{
		s = replacestr(s, htmlEntities[i].what, htmlEntities[i].replaceWith);
	}
	return s;
}

function format(fmt)
{
  var retValue = fmt;
  for (var i = 1; i < format.arguments.length; i++)
  {
    var sp = '{' + (i - 1) + '}';
    retValue = replacestr(retValue, sp, '' + format.arguments[i]);
  }
  return retValue;
}

function trim(s)
{
	var li = 0;
	var ri = s.length - 1;
	for (; li < ri; li++)
	{
		if (s.charAt(li) != ' ')
		{
			break;
		}
	}
	for (; ri >= li; ri--)
	{
		if (s.charAt(ri) != ' ')
		{
			break;
		}
	}
	return s.substring(li, ri);
}

// browser compatibility

if(document.all && !document.getElementById)
{
    document.getElementById = function(id)
    {
         return document.all[id];
    }
}

// images preloading

function dc_preimg()
{
  for (var i = 0; i < dc_preimg.arguments.length; i++)
  {
    var img = new Image();
    img.src = dc_preimg.arguments[i];
  }
}

// popups

function showPopup(message) 
{
	var obj_id = document.getElementById(popupDivId);
	obj_id.innerHTML = message;
	objWidth = obj_id.offsetWidth;
	popupActive = true;
	if (uiOptions.useTransitionEffects)
	{
		obj_id.filters['blendtrans'].apply();
	}
	obj_id.style.visibility = "visible";
	if (uiOptions.useTransitionEffects)
	{
		obj_id.filters['blendtrans'].play();
	}
	followMouse();
}

function followMouse()
{
	if (!popupActive)
	{
		return;
	}
	var obj_id = document.getElementById(popupDivId);
	var x = 0;
	var y = 0;
	if(cursorX > document.body.clientWidth / 2 && cursorX < document.body.clientWidth)
	{
		x = cursorX - objWidth;
		y = cursorY + popupYOffset;
	}
	else
	{
		x = cursorX + popupXOffset;
		y = cursorY + popupYOffset;
	}
	obj_id.style.left = x + "px";
	obj_id.style.top  = y + "px";
	popupTimer = setTimeout("followMouse()", 50);
}

function hidePopup()
{
	var obj_id = document.getElementById(popupDivId);
	obj_id.style.visibility = "hidden";
	clearTimeout(popupTimer);
	popupActive = false;
}

// menus

function prepareMenuCore(menu)
{
	if (msie)
	{
		menu.style.backgroundColor = 'window';
		menu.style.color = 'windowtext';
		menu.style.borderColor = 'windowtext';
	}
}

function prepareMenu()
{
	var menu = document.getElementById(menuDivId);
	prepareMenuCore(menu);
}

function showMenuCore(menu, capture)
{
	var x = cursorX;
	var y = cursorY;
	menu.style.left = x + "px";
	menu.style.top  = y + "px";
	hidePopup();
	if (menu.style.visibility != "visible") 
	{
		if (uiOptions.useTransitionEffects)
		{
			menu.filters['blendtrans'].apply();
		}
		menu.style.visibility = "visible";
		if (uiOptions.useTransitionEffects)
		{
			menu.filters['blendtrans'].play();
		}
		if (msie)
		{
			var trange = document.body.createTextRange();
			trange.moveToElementText(menu);
			trange.scrollIntoView();
		}
	}
	if ((capture == null || capture) && menu.setCapture && uiOptions.captureMouse)
	{
		menu.setCapture(false);
	}
}

function showMenu(content, capture)
{
	if (document.releaseCapture)
	{
		document.releaseCapture();
	}
	var menu = document.getElementById(menuDivId);
	menu.innerHTML = content;
	showMenuCore(menu, capture);
}

function reshowMenu(capture)
{
	var menu = document.getElementById(menuDivId);
	hidePopup();
	menu.style.visibility = "visible";
	if ((capture == null || capture) && menu.setCapture && uiOptions.captureMouse)
	{
		menu.setCapture(false);
	}
}

function hideMenu()
{
	if (document.releaseCapture)
	{
		document.releaseCapture();
	}
	hideMenuCore();
}

function hideMenuCore()
{
	var menu = document.getElementById(menuDivId);
	if (clearMenuOnceWhenClosed)
	{
		menu.innerHTML = '';
		clearMenuOnceWhenClosed = false;
	}
	menu.style.visibility = 'hidden';
	hidePopup();
}

function onMenuClick()
{
	var menu = document.getElementById(menuDivId);
	if (!msie)
	{
		return;
	}
	var o = window.event.srcElement;
	if (menu != o && !menu.contains(o))
	{
		hideMenu();
	}
}


function getMenuItemHtml(html, action)
{
	return format('<a class="ABLink" href="#" onclick="hideMenu(); {1}; return false">{0}</a>', html, action);
}

function onCellOver(id)
{
	var elt = document.getElementById(id);
	if (elt != null)
	{
		elt.className = "ABLinkH";
	}
}

function onCellOut(id)
{
	var elt = document.getElementById(id);
	if (elt == null)
	{
		return;
	}
	elt.className = "ABLink";
}

function onCellClick_Core(id)
{
	var oldv = clearMenuOnceWhenClosed;
	clearMenuOnceWhenClosed = false;
	var r = onCellClick(id);
	clearMenuOnceWhenClosed = oldv;
	return r;
}

function onCellClick(id)
{
	onCellOut(id);
	hideMenu();
	return true;
}

function getCellMenuItemHtml_Core(html, action, over, out)
{
	if (over == null)
	{
		over = '';
	}
	if (out == null)
	{
		out = '';
	}
	var newid = 'cmi_' + dc_menuItemCount;
	dc_menuItemCount++;
	var qnewid = "'" + newid + "'";
	return format('<td align="center"><div id="{4}" class="ABLink" onmouseover="onCellOver({5}); {2}" onmouseout="onCellOut({5}); {3}" onclick="onCellClick_Core({5}); {1}">{0}</div></td>', html, action, over, out, newid, qnewid);
}

function getCellMenuItemHtml(html, action, over, out)
{
	if (over == null)
	{
		over = '';
	}
	if (out == null)
	{
		out = '';
	}
	var newid = 'cmi_' + dc_menuItemCount;
	dc_menuItemCount++;
	var qnewid = "'" + newid + "'";
	return format('<td align="center"><div id="{4}" class="ABLink" onmouseover="onCellOver({5}); {2}" onmouseout="onCellOut({5}); {3}" onclick="onCellClick({5}); {1}">{0}</div></td>', html, action, over, out, newid, qnewid);
}

function getCellMenuSeparatorHtml()
{
	return '<td align="center">|</td>';
}

function getRowMenuItemHtml(html, action, over, out)
{
	return '<tr>' + getCellMenuItemHtml(html, action, over, out) + '</tr>';
}

function getRowMenuSeparatorHtml()
{
	return '<tr><td align="center"><hr class="dashed" /></td></tr>';
}

function onCellOver2(id)
{
	var elt = document.getElementById(id);
	if (elt != null)
	{
		elt.className = "ABLinkH2";
	}
}

function onCellOut2(id)
{
	var elt = document.getElementById(id);
	if (elt != null)
	{
		elt.className = "ABLink2";
	}
}

function onCellClick2(id)
{
	onCellOut2(id);
	return true;
}

function getCell2MenuItemHtml(html, action, over, out)
{
	if (over == null)
	{
		over = '';
	}
	if (out == null)
	{
		out = '';
	}
	var newid = 'cmi2_' + dc_menuItemCount;
	dc_menuItemCount++;
	var qnewid = "'" + newid + "'";
	return format('<td id="{4}" unselectable="on" valign="bottom" align="center" class="ABLink2" onmouseover="onCellOver2({5}); {2}" onmouseout="onCellOut2({5}); {3}" onclick="onCellClick2({5}); {1}">{0}</td>', html, action, over, out, newid, qnewid);
}

function getCell2MenuControlHtml(text)
{
	return format('<td valign="center" unselectable="on" align="center" class="ABLink2" style="cursor: default;">{0}</td>', text);
}

function getCell2MenuFillerHtml(text)
{
	return '<td valign="center" unselectable="on" align="center" class="ABLink2" width="100%" style="cursor: default;">&nbsp;</td>';
}

function getCell2MenuSeparatorHtml()
{
	return '<td valign="center" unselectable="on" align="center" class="ABLink2" style="cursor: default;">|</td>';
}

function getCell2MenuLabelHtml(html)
{
	if (html == null)
	{
		html = '';
	}
	return '<td valign="center" unselectable="on" align="center" class="ABLink2" style="cursor: default;">' + html + '</td>';
}

// utils

function CurPos(e)
{
	cursorX = !moz ? event.clientX : e.clientX;
	cursorY = !moz ? event.clientY : e.clientY;
	cursorX += document.body.scrollLeft;
	cursorY += document.body.scrollTop;
	if (!opera)
	{
		cursorX += document.documentElement.scrollLeft;
		cursorY += document.documentElement.scrollTop;
	}
}

function isDeveloperMode()
{
	return (window.location.search && window.location.search.indexOf("dev=") >= 0);
}

// init

if (typeof (uiOptions) == 'undefined')
{
	uiOptions = {
		useAlphaForMenuAndTip: true,
		useTransitionEffects: false,
		captureMouse: false
		};
}

document.onmousemove = CurPos;
