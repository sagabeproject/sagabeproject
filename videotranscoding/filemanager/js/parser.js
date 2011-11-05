/*********************************************************************************************************
 This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
*********************************************************************************************************/

var fmParser = {

	mediaCnt: 0,
	titleHeight: 25,

	parseMain: function(json, contObj) {
		if(typeof contObj != 'object') return false;
		if(typeof json != 'object') json = eval('(' + json + ')');
		if(json.type == 'editor') return this.parseEditor(json, contObj);

		var html, id, title;
		var titleWidth = json.width ? (json.width - 120) + 'px' : '100%';
		var height = contObj.offsetHeight - this.titleHeight;

		if(typeof json.title != 'undefined' && json.title != '') {
			title = json.title;
		}
		else if(typeof json.search != 'undefined' && json.search != '') {
			title = fmMsg[json.lang].searchResult + ': ' + json.search;
		}
		else if(typeof json.sysType != 'undefined' && json.sysType != '') {
			title = '[' + json.sysType + '] ' + json.path;
		}
		else title = json.path;

		var now = new Date();
		var d = [];
		d[0] = now.getFullYear();
		d[1] = now.getMonth() + 1;
		if(d[1] < 10) d[1] = '0' + d[1];
		d[2] = now.getDate();
		if(d[2] < 10) d[2] = '0' + d[2];

		html = '<table border="0" cellspacing="0" cellpadding="0" width="100%">\n';
		html += '<tr>\n';
		html += '<td class="fmTH1" align="left" style="height:' + this.titleHeight + 'px"' + (json.explorer ? ' colspan="2"' : '') + '>\n';
		html += '<table border="0" cellspacing="0" cellpadding="0" width="100%">\n';
		html += '<tr>\n';
		html += '<td><div class="fmTH1" style="width:' + titleWidth + '; padding:4px; white-space:nowrap; overflow:hidden" title="' + title + '">' + title + '</div></td>\n';

		if(!json.login) html += this.parseTitleIcons(json);

		html += '</tr></table>\n';
		html += '</td>\n';
		html += '</tr>\n';

		if(json.login) {
			html += this.parseLogin(json, height);
		}
		else if(json.entries) {
			html += '<tr valign="top">\n';
			html += '<td class="fmTD1">\n';
			html += '<table border="0" cellspacing="0" cellpadding="0" width="100%">\n';
			html += '<tr valign="top">\n';

			if(json.explorer) {
				id = json.cont + 'Exp';
				html += '<td><div id="' + id + '" class="fmTD2" style="width:' + json.explorer.width + 'px; height:' + height + 'px; overflow:auto"></div></td>\n';
			}

			if(json.entries) {
				id = json.cont + 'Entries';
				html += '<td><div id="' + id + '" class="fmTD1" style="width:' + json.entries.width + 'px; height:' + height + 'px; overflow:auto">\n';

				switch(fmContSettings[json.cont].listType) {
					case 'details':html += this.parseEntriesDetailView(json, d.join('-'));break;
					case 'icons':html += this.parseEntriesIconView(json, d.join('-'));break;
				}
				html += '</div></td>\n';
			}
			html += '</tr>\n</table>\n';
			html += '</td>\n';
			html += '</tr>\n';
		}
		html += '</table>\n';

		contObj.innerHTML = html;
		return true;
	},

	parseEntriesDetailView: function(json, date) {
		if(typeof json != 'object') return '';
		if(!json.entries) return '';

		var html, i, j, action, action2, cssRow, cssData, mode, style, cellWidth, content, tooltip, name, img, order;
		var url = fmWebPath + '/action.php?fmContainer=' + json.cont;
		var entries = json.entries;
		var userPerms = fmContSettings[json.cont].userPerms;

		cssRow = (json.search != '') ? 'fmSearchResult' : 'fmTD1';
		html = '<table border="0" cellspacing="1" cellpadding="2" width="100%" class="fmTH2">\n';
		html += '<tr align="center">\n';

		for(i in entries.captions) {
			if(entries.captions[i].name == fmContSettings[json.cont].sort.field) {
				if(fmContSettings[json.cont].sort.order == 'asc') {
					img = 'sort_asc.gif';
					order = 'desc';
					tooltip = fmMsg[json.lang].cmdSortDesc;
				}
				else {
					img = 'sort_desc.gif';
					order = 'asc';
					tooltip = fmMsg[json.lang].cmdSortAsc;
				}
			}
			else {
				img = 'blank.gif';
				order = 'asc';
				tooltip = fmMsg[json.lang].cmdSortAsc;
			}
			action = "fmLib.sortList('" + json.cont + "', '" + entries.captions[i].name + "', '" + order + "')";
			html += '<td class="fmTH3" title="' + tooltip + '" style="white-space:nowrap" onMouseOver="this.className=\'fmTH4\'" onMouseOut="this.className=\'fmTH3\'" onMouseDown="this.className=\'fmTH5\'" onMouseUp="this.className=\'fmTH4\'" onClick="' + action + '">\n';
			if(entries.captions[i].caption != '') html += '&nbsp;' + entries.captions[i].caption + '&nbsp;';
			html += '<img src="' + fmWebPath + '/icn/' + img + '" width="8" height="7"/>';
			html += '</td>\n';
		}

		if(userPerms.remove || !userPerms.hideDisabledIcons) {
			action = this.parseAction(entries.lastCol, json.cont, '');
			html += '<td width="20" class="fmContent">';
			html += '<img src="' + fmWebPath + '/icn/' + entries.lastCol.icon + '" width="10" height="10" style="' + entries.lastCol.style + '" title="' + entries.lastCol.tooltip + '" onClick="' + action + '"/>';
			html += '</td>\n';
		}
		html += '</tr>\n';

		if(entries.captions.length > 2) {
			cellWidth = Math.round(entries.width / (entries.captions.length - 1)) + 25;
		}
		else cellWidth = entries.width - 70;

		for(i in entries.items) {
			fmEntries[json.cont][entries.items[i].id] = entries.items[i];

			if(entries.items[i].icon.substr(0, 4) == 'cdup') {
				mode = (json.search != '') ? 'search' : 'parent';
				action = "fmLib.call('" + url + '&fmMode=' + mode + "')";
			}
			else action = "fmLib.viewMenu('" + entries.items[i].id + "', '" + json.lang + "', '" + json.cont + "')";

			if(entries.items[i].deleted) {
				cssData = 'fmContentDisabled';
			}
			else if(fmContSettings[json.cont].markNew && entries.items[i].changed.substr(0, 10) == date) {
				cssData = 'fmContentNew';
			}
			else cssData = 'fmContent';

			html += '<tr class="' + cssRow + '" style="height:25px" onMouseOver="this.className=\'fmTD2\'" onMouseOut="this.className=\'' + cssRow + '\'">\n';

			for(j in entries.captions) {
				switch(entries.captions[j].name) {
					case 'isDir':
						content = '<img src="' + fmWebPath + '/icn/small/' + entries.items[i].icon + '" width="12" height="10"/>';
						style = 'cursor:pointer; width:15px; text-align:center';
						break;
					case 'name':
						if(userPerms.mediaPlayer && entries.items[i].name.match(/\.(mp3|m4a|aac|wav|og[ga])$/i)) {
							action2 = "fmLib.noMenu = true; fmLib.playSound(this, '" + json.cont + "', '" + entries.items[i].id + "')";
							img = (fmLib.soundObj && fmLib.soundObj.name == json.cont + '_snd_' + entries.items[i].id && fmLib.soundObj.bObj) ? fmLib.soundObj.bObj.src : fmWebPath + '/icn/mediaPlay.gif';
							name = '<img id="' + json.cont + '_btn_' + entries.items[i].id + '" src="' + img + '" align="absmiddle" hspace="4" onClick="' + action2 + '"/>' + entries.items[i].name;
							style = 'cursor:pointer; vertical-align:middle; width:' + cellWidth + 'px';
						}
						else if(userPerms.mediaPlayer && entries.items[i].name.match(/\.(swf|flv|mp4)$/i)) {
							action2 = "fmLib.noMenu = true; fmLib.openDialog('" + url + "&fmMode=loadFile&fmObject=" + entries.items[i].id + "', 'fmMediaPlayer', '" + entries.items[i].name.replace(/\'/g, "\\'") + "')";
							name = '<img src="' + fmWebPath + '/icn/mediaPlay.gif" align="absmiddle" hspace="4" onClick="' + action2 + '"/>' + entries.items[i].name;
							style = 'cursor:pointer; vertical-align:middle; width:' + cellWidth + 'px';
						}
						else {
							name = entries.items[i].name;
							style = 'cursor:pointer; width:' + cellWidth + 'px';
						}
						content = '<div class="' + cssData + '" style="overflow:hidden; white-space:nowrap; text-align:left; width:' + cellWidth + 'px" title="' + entries.items[i].name + '">' + name + '</div>';
						break;
					case 'size':
						content = entries.items[i].size;
						style = 'cursor:pointer; text-align:right; white-space:nowrap';
						break;
					default:
						content = entries.items[i][entries.captions[j].name];
						style = 'cursor:pointer; text-align:center; white-space:nowrap';
				}
				html += '<td class="' + cssData + '" style="' + style + '" onClick="' + action + '">' + content + '</td>\n';
			}

			if(userPerms.remove || !userPerms.hideDisabledIcons) {
				html += '<td class="' + cssData + '" align="center">';
				html += '<input type="checkbox" style="margin:0" value="' + entries.items[i].id + '"' + (entries.items[i].checkbox ? '' : ' disabled="disabled"') + '/>';
				html += '</td>\n';
			}
			html += '</tr>\n';
		}
		html += '</table>\n';
		return html;
	},

	parseEntriesIconView: function(json, date) {
		if(typeof json != 'object') return '';
		if(!json.entries) return '';

		var html, i, action, action2, cssRow, cssData, cssIcon, mode, thumbWidth, thumbHeight, perc, cellWidth, name, img;
		var url = fmWebPath + '/action.php?fmContainer=' + json.cont;
		var entries = json.entries;
		var cellCnt = 0;
		var userPerms = fmContSettings[json.cont].userPerms;

		cssRow = (json.search != '') ? 'fmSearchResult' : 'fmTD1';
		cellWidth = Math.round(entries.width * entries.cellWidth / 100) - 20;

		html = '<table border="0" cellspacing="2" cellpadding="5" width="100%" class="fmTH2">\n';
		html += '<colgroup>\n';

		for(i = 0; i < entries.cellsPerRow; i++) {
			html += '<col width="' + entries.cellWidth + '%"/>\n';
		}
		html += '</colgroup>\n';
		html += '<tr align="center" valign="top">\n';

		for(i in entries.items) {
			fmEntries[json.cont][entries.items[i].id] = entries.items[i];

			if(entries.items[i].icon.substr(0, 4) == 'cdup') {
				mode = (json.search != '') ? 'search' : 'parent';
				action = "fmLib.call('" + url + '&fmMode=' + mode + "')";
			}
			else action = "fmLib.viewMenu('" + entries.items[i].id + "', '" + json.lang + "', '" + json.cont + "')";

			if(entries.items[i].deleted) {
				cssData = 'fmContentDisabled';
			}
			else if(fmContSettings[json.cont].markNew && entries.items[i].changed.substr(0, 10) == date) {
				cssData = 'fmContentNew';
			}
			else cssData = 'fmContent';

			if(cellCnt >= entries.cellsPerRow) {
				html += '</tr>\n<tr align="center" valign="top">\n';
				cellCnt = 0;
			}
			cssIcon = (entries.items[i].thumbnail || entries.items[i].id3.Picture) ? 'fmThumbnail' : '';
			html += '<td class="' + cssRow + '" style="cursor:pointer" onClick="' + action + '" onMouseOver="this.className=\'fmTD2\'" onMouseOut="this.className=\'' + cssRow + '\'">\n';
    		html += '<table border="0" cellspacing="0" cellpadding="0" style="width:100%">\n';
			html += '<tr>\n';
 			html += '<td align="center" class="' + cssIcon + '" style="height:54px">';

			if(entries.items[i].id3.Picture) {
				thumbWidth = cellWidth;
				thumbHeight = 50;
				name = entries.items[i].id3.Picture.split(':')[0];
				img = url + '&fmMode=getCachedImage&fmObject=' + name + '&width=' + thumbWidth + '&height=' + thumbHeight;
			}
 			else if(entries.items[i].thumbnail) {
 				thumbWidth = entries.items[i].width;
				thumbHeight = entries.items[i].height;

 				if(thumbWidth > cellWidth) {
					perc = cellWidth / thumbWidth;
					thumbWidth = cellWidth;
					thumbHeight = Math.round(thumbHeight * perc);
				}

				if(thumbHeight > 50) {
					perc = 50 / thumbHeight;
					thumbWidth = Math.round(thumbWidth * perc);
					thumbHeight = Math.round(thumbHeight * perc);
				}
				img = url + '&fmMode=getThumbnail&fmObject=' + entries.items[i].id + '&width=' + thumbWidth + '&height=' + thumbHeight + '&' + entries.items[i].thumbnail;
			}
 			else {
				img = fmWebPath + '/icn/big/' + entries.items[i].icon;
				thumbHeight = 50;
			}
			html += '<div style="height:' + thumbHeight + 'px; background:url(' + img + ') center no-repeat"></div>';
 			html += '</td>\n';
 			html += '</tr><tr>\n';
 			html += '<td align="center" style="height:20px">\n';

			if(userPerms.mediaPlayer && entries.items[i].name.match(/\.(mp3|m4a|aac|wav|og[ga])$/i)) {
				action2 = "fmLib.noMenu = true; fmLib.playSound(this, '" + json.cont + "', '" + entries.items[i].id + "')";
				img = (fmLib.soundObj && fmLib.soundObj.name == json.cont + '_snd_' + entries.items[i].id && fmLib.soundObj.bObj) ? fmLib.soundObj.bObj.src : fmWebPath + '/icn/mediaPlay.gif';
				name = '<img id="' + json.cont + '_btn_' + entries.items[i].id + '" src="' + img + '" align="absmiddle" hspace="4" onClick="' + action2 + '"/>' + entries.items[i].name;
			}
			else if(userPerms.mediaPlayer && entries.items[i].name.match(/\.(swf|flv|mp4)$/i)) {
				action2 = "fmLib.noMenu = true; fmLib.openDialog('" + url + "&fmMode=loadFile&fmObject=" + entries.items[i].id + "', 'fmMediaPlayer', '" + entries.items[i].name.replace(/\'/g, "\\'") + "')";
				name = '<img src="' + fmWebPath + '/icn/mediaPlay.gif" align="absmiddle" hspace="4" onClick="' + action2 + '"/>' + entries.items[i].name;
			}
			else name = entries.items[i].name;

 			html += '<div class="' + cssData + '" style="overflow:hidden; white-space:nowrap; vertical-align:middle; width:' + cellWidth + 'px" title="' + entries.items[i].name + '">' + name + '</div>\n';
 			html += '</td>\n';
			html += '</tr></table>\n';
 			html += '</td>\n';
			cellCnt++;
		}

		while(cellCnt < entries.cellsPerRow) {
			html += '<td class="' + cssRow + '">&nbsp;</td>\n';
			cellCnt++;
		}
		html += '</tr></table>\n';
		return html;
	},

	parseExplorer: function(json, link) {
		if(typeof json != 'object') return '';
		if(!json.explorer) return '';

		var url, action, icon, icon2, style, hash, i, j;
		var html = '';
		var explorer = json.explorer;
		fmContSettings[json.cont].expJson = json;

		if(typeof fmContSettings[json.cont].expanded != 'object') {
			fmContSettings[json.cont].expanded = {};

			for(i = 0; i < explorer.items.length; i++) {
				hash = explorer.items[i].hash;
				if(explorer.expandAll || explorer.items[i].level == 1) {
					fmContSettings[json.cont].expanded[hash] = true;
				}
			}
		}

		for(i = 0; i < explorer.items.length; i++) {
			if(link) url = link + '&fmName=' + explorer.items[i].id;
			else url = fmWebPath + '/action.php?fmContainer=' + json.cont + '&fmMode=expOpen&fmObject=' + explorer.items[i].id;

			if(explorer.items[i-1] && explorer.items[i-1].level < explorer.items[i].level) {
				hash = explorer.items[i-1].hash;

				if(!fmContSettings[json.cont].expanded[hash]) {
					html += '<div id="' + json.cont + '|' + hash + '" style="display:none">';
				}
				else html += '<div id="' + json.cont + '|' + hash + '">';
			}
			html += '<div class="fmExplorer" onMouseOver="this.className=\'fmExplorerHilight\'" onMouseOut="this.className=\'fmExplorer\'">';

			if(explorer.items[i].level > 1) for(j = 1; j < explorer.items[i].level; j++) {
				html += '<img src="' + fmWebPath + '/icn/blank.gif" width="8" height="1"/>';
			}

			if(explorer.items[i+1] && explorer.items[i+1].level > explorer.items[i].level) {
				hash = explorer.items[i].hash;
				if(fmContSettings[json.cont].expanded[hash]) {
					icon = 'treeClose.gif';
					icon2 = 'dir_open.gif';
				}
				else {
					icon = 'treeOpen.gif';
					icon2 = 'dir.gif';
				}
				action = 'fmLib.toggleTreeItem(this)';
				style = 'cursor:pointer';
			}
			else {
				icon = 'blank.gif';
				icon2 = 'dir.gif';
				action = style = '';
			}
			html += '<img src="' + fmWebPath + '/icn/' + icon + '" width="9" height="9" onClick="' + action + '" style="' + style + '"/>';
			html += '<img src="' + fmWebPath + '/icn/' + icon2 + '" hspace="4" onClick="fmLib.call(\'' + url + '\')" style="cursor:pointer"/>';
			html += '<span class="fmExplorerContent" onClick="fmLib.call(\'' + url + '\')" style="cursor:pointer" title="' + explorer.items[i].name + '">' + explorer.items[i].name + '</span>';
			html += '</div>';

			if(explorer.items[i+1] && explorer.items[i+1].level < explorer.items[i].level) {
				for(j = 0; j < explorer.items[i].level - explorer.items[i+1].level; j++) {
					html += '</div>';
				}
			}
		}
		return html;
	},

	parseEditor: function(json, contObj) {
		if(typeof json != 'object') return false;
		if(typeof contObj != 'object') return false;

		var html;
		var url = fmWebPath + '/action.php?fmContainer=' + json.cont;
		var width = contObj.offsetWidth;
		var height = contObj.offsetHeight - this.titleHeight;
		var content = fmTools.b64decode(json.text.content);

		if(fmContSettings[json.cont].encoding.match(/^UTF-8$/i)) {
			content = fmTools.utf8decode(content);
		}
		html = '<form name="frmEdit" class="fmForm" action="javascript:fmLib.call(\'' + url + '\', \'frmEdit\')" method="post">\n';
    	html += '<input type="hidden" name="fmMode" value="edit">\n';
    	html += '<input type="hidden" name="fmObject" value="' + json.id + '">\n';
		html += '<table border="0" cellspacing="0" cellpadding="0" width="100%">\n';
		html += '<tr>\n';
		html += '<td class="fmTH1" align="left" style="height:' + this.titleHeight + 'px">\n';
		html += '<table border="0" cellspacing="0" cellpadding="0" width="100%">\n';
		html += '<tr>\n';
		html += '<td class="fmTH1" style="padding:4px; overflow:hidden">' + fmMsg[json.lang]['cmdEdit'] + ': ' + fmEntries[json.cont][json.id].name + '</td>\n';
		html += this.parseTitleIcons(json);
		html += '</tr></table>\n';
		html += '</td>\n';
		html += '</tr><tr>\n';
		html += '<td class="fmTH2" align="center">\n';
		html += '<textarea name="fmText" style="width:' + width + 'px; height:' + height + 'px" ';
		html += 'class="codeedit ' + json.text.lang + ' lineNumbers focus" wrap="off">' + content + '</textarea>\n';
		html += '</td>\n';
		html += '</tr>\n</table>\n';
		html += '</form>\n';

		contObj.innerHTML = html;
		return true;
	},

	parseTextViewer: function(json, contObj) {
		if(typeof contObj != 'object') return false;
		if(typeof json != 'object') json = eval('(' + json + ')');

		var html;
		var width = fmContSettings[json.cont].docViewerWidth;
		var height = fmContSettings[json.cont].docViewerHeight;
		var content = fmTools.b64decode(json.text.content);

		if(fmContSettings[json.cont].encoding.match(/^UTF-8$/i)) {
			content = fmTools.utf8decode(content);
		}
		html = '<pre style="width:' + width + 'px; height:' + height + 'px; margin:0px; visibility:hidden" ';
		html += 'class="codeview ' + json.text.lang + ' lineNumbers">' + content + '</pre>\n';

		contObj.innerHTML = html;
		return true;
	},

	parseLogin: function(json, height) {
		if(typeof json != 'object') return '';
		if(!json.login) return '';

		var html;
		var action = this.parseAction(json.login, json.cont);

		html = '<tr>\n';
		html += '<td class="fmTH3" align="center" style="height:' + height + 'px; padding:4px">\n';
		html += '<form name="' + json.cont + 'Login" action="javascript:' + action + '" class="fmForm" method="post">\n';
		html += '<input type="hidden" name="fmMode" value="login"/>\n';
		html += '<input type="password" name="fmName" size="20" maxlength="60" class="fmField"/><br/>\n';
		html += '<input type="checkbox" name="fmRememberPwd" value="1"/>' + fmMsg[json.lang].rememberPwd + '<br/>\n';
		html += '<input type="submit" class="fmButton" value="' + fmMsg[json.lang].cmdLogin + '"/>\n';
		html += '</form>\n';
		html += '</td>\n';
		html += '</tr>\n';
		return html;
	},

	parseDebugInfo: function(json, contObj) {
		if(typeof contObj != 'object') return false;
		if(typeof json != 'object') json = eval('(' + json + ')');
		if(!json.debug) return false;

		var html;

		html = '<div class="fmTH1" style="padding:4px; text-align:left">DEBUG INFO</div>\n';
		html += '<div class="fmTD2" style="padding:2px; text-align:left">\n';
		html += '<table border="0" cellspacing="0" cellpadding="2">\n';
		html += '<tr valign="top">\n';
		html += '<td class="fmTD2">Cookie info:</td><td class="fmTD2">' + json.debug.cookie + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">PHP version:</td><td class="fmTD2">' + json.debug.phpVersion + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">Memory limit:</td><td class="fmTD2">' + json.debug.memoryLimit + '</td>\n';
		html += '</tr><tr valign="top">\n';

		if(json.debug.memoryUsage) {
			html += '<td class="fmTD2">Memory usage:</td><td class="fmTD2">' + json.debug.memoryUsage + '</td>\n';
			html += '</tr><tr valign="top">\n';
		}
		html += '<td class="fmTD2">FileManager::$language:</td><td class="fmTD2">' + json.debug.lang + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">FileManager::$encoding:</td><td class="fmTD2">' + json.debug.encoding + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">FileManager::$locale:</td><td class="fmTD2">' + json.debug.locale + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">FileManager::$uploadEngine:</td><td class="fmTD2">' + json.debug.uploadEngine + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">FileManager::$perlEnabled:</td><td class="fmTD2">' + json.debug.perlEnabled + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">FileManager::$fmWebPath:</td><td class="fmTD2">' + json.debug.webPath + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">FileManager::$startDir:</td><td class="fmTD2">' + json.debug.startDir + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">FileManager::$maxImageWidth:</td><td class="fmTD2">' + json.debug.maxImageWidth + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">FileManager::$maxImageHeight:</td><td class="fmTD2">' + json.debug.maxImageHeight + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">Listing::$curDir:</td><td class="fmTD2">' + json.debug.curDir + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">Listing::$searchString:</td><td class="fmTD2">' + json.debug.search + '</td>\n';
		html += '</tr><tr valign="top">\n';
		html += '<td class="fmTD2">Cache directory:</td><td class="fmTD2">' + json.debug.cache + ' files</td>\n';
		html += '</tr></table>\n';
		html += "</div>\n";

		contObj.innerHTML = html;
		return true;
	},

	parseLogMessages: function(json, contObj) {
		if(typeof contObj != 'object') return false;
		if(typeof json != 'object') json = eval('(' + json + ')');
		if(!json.messages) return false;

		var cssLog, i, msg;
		var html = '';

		for(i = 0; i < json.messages.length; i++) {
			msg = json.messages[i];
			cssLog = 'fmLog' + msg.type.substr(0, 1).toUpperCase() + msg.type.substr(1, msg.type.length);
			html += '<table border="0" cellspacing="0" cellpadding="0"><tr valign="top">';
			html += '<td class="' + cssLog + '" style="padding-right:10px; white-space:nowrap">' + msg.time + '</td>';
			html += '<td class="' + cssLog + '">' + msg.text + '</td>';
			html += '</tr></table>\n';
		}
		contObj.innerHTML += html;
		contObj.scrollTop = contObj.scrollHeight;
		return true;
	},

	parseTitleIcons: function(json) {
		if(typeof json != 'object') return '';
		if(!json.icons) return '';

		var html = action = tooltip = icon = '';

		if(json.type == 'list') {
			var url = fmWebPath + '/action.php?fmContainer=' + json.cont;

			action = "fmLib.call('" + url + "&fmMode=refresh')";
			tooltip = fmMsg[json.lang].cmdRefresh;
			html += '<td class="fmTH1" width="18" align="right">';
			html += '<img src="' + fmWebPath + '/icn/refresh.gif" width="11" height="14" style="cursor:pointer" title="' + tooltip + '" onClick="' + action + '"/>\n';
			html += '</td>\n';

			if(fmContSettings[json.cont].listType == 'details') {
				icon = 'list_icons.gif'
				tooltip = fmMsg[json.lang].cmdIcons;
			}
			else {
				icon = 'list_details.gif'
				tooltip = fmMsg[json.lang].cmdDetails;
			}
			action = "fmLib.toggleListView('" + json.cont + "')";
			html += '<td class="fmTH1" width="18" align="right">';
			html += '<img src="' + fmWebPath + '/icn/' + icon + '" width="11" height="14" style="cursor:pointer" title="' + tooltip + '" onClick="' + action + '"/>\n';
			html += '</td>\n';
		}

		for(i in json.icons) {
			action = this.parseAction(json.icons[i], json.cont, json.icons[i].id);
			tooltip = (typeof json.icons[i].caption == 'string') ? json.icons[i].caption : '';
			html += '<td class="fmTH1" width="' + (json.icons[i].width + 7) + '" align="right">';
			html += '<img src="' + fmWebPath + '/icn/' + json.icons[i].name + '" width="' + json.icons[i].width + '" height="' + json.icons[i].height + '" style="' + json.icons[i].style + '" title="' + tooltip + '" onClick="' + action + '"/>\n';
			html += '</td>\n';
		}
		html += '<td style="width:4px"></td>';
		return html;
	},

	parseAction: function(json, curCont, id) {
		if(typeof json != 'object') return '';

		var url = fmWebPath + '/action.php?fmContainer=' + curCont;
		var i, params;
		var action = name = '';

		if(json.call) {
			action = "fmLib.call('" + url + '&fmMode=' + json.call;
			if(id) action += '&fmObject=' + id;
			action += "')";
		}
		else if(json.submit) {
			action = "fmLib.call('" + url + "', '" + json.submit + "')";
		}
		else if(json.exec) {
			for(i = 1, params = []; i < json.exec.length; i++) {
				if(typeof json.exec[i] == 'object') {
					params.push(json.exec[i]);
				}
				else params.push("'" + json.exec[i].replace(/\'/g, "\\'") + "'");
			}
			action = json.exec[0] + '(' + params.join(',') + ')';
		}
		else if(json.dialog) {
			if(json.dialog == 'fmError') {
				for(i in json.caption) json.caption[i] = json.caption[i].replace(/\'/g, "\\'");
				action = "fmLib.openDialog('', 'fmError', ['" + json.caption[0] + "', '" + json.caption[1] + "'])";
			}
			else {
				caption = [json.caption + (id ? ': ' + fmEntries[curCont][id].name : '')];
				if(json.confirm) caption.push(json.confirm);
				if(json.text) for(i = 0; i < json.text.length; i++) {
					caption.push(json.text[i]);
				}
				if(typeof json.content != 'undefined') name = json.content;
				else if(id) name = fmEntries[curCont][id].name.replace(/\'/g, "\\'");
				for(i in caption) caption[i] = caption[i].replace(/\'/g, "\\'");
				action = "fmLib.openDialog('" + url + "', '" + json.dialog + "', ['" + caption.join("','") + "'], '";
				action += id + "', '" + name + "'";
				if(json.dialog == 'fmPerm') action += ", '" + fmEntries[curCont][id].permissions + "'";
				action += ')';
			}
		}
		return action;
	},

	parseMenu: function(items, curCont, id) {
		var i, css, width, icon, action;
		var html = '<table border="0" cellspacing="0" cellpadding="4" width="100%">';

		for(i = 0; i < items.length; i++) {
			if(items[i].caption == 'separator') {
				if(items[i+1]) {
					html += '<tr class="fmTD2">' +
							'<td colspan="2"><div style="height:0px; margin:0px; border:1px inset #FFFFFF"></div></td>' +
							'</tr>';
				}
			}
			else {
				css = (items[i].call || items[i].exec || items[i].dialog) ? 'fmContent' : 'fmContentDisabled';
				width = items[i].width ? items[i].width : 10;
				icon = fmWebPath + '/icn/' + items[i].icon;
				action = this.parseAction(items[i], curCont, id);

				html += '<tr class="fmTD2" style="cursor:pointer"' +
						' onMouseOver="this.className=\'fmTH2\'"' +
						' onMouseOut="this.className=\'fmTD2\'"' +
						' onClick="' + action + '">' +
						'<td class="' + css + '" width="10%">' +
						'<img src="' + icon + '" border="0" width="' + width + '" height="10"/></td>' +
						'<td class="' + css + '" width="90%" align="left">' + items[i].caption + '</td>' +
						'</tr>';
			}
		}
		html += '</table>';
		return html;
	}
}
