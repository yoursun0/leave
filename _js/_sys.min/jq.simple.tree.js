/*
* jQuery SimpleTree Drag&Drop plugin
* Update on 22th May 2008
* Version 0.3
*
* Licensed under BSD <http://en.wikipedia.org/wiki/BSD_License>
* Copyright (c) 2008, Peter Panov <panov@elcat.kg>, IKEEN Group http://www.ikeen.com
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*     * Redistributions of source code must retain the above copyright
*       notice, this list of conditions and the following disclaimer.
*     * Redistributions in binary form must reproduce the above copyright
*       notice, this list of conditions and the following disclaimer in the
*       documentation and/or other materials provided with the distribution.
*     * Neither the name of the Peter Panov, IKEEN Group nor the
*       names of its contributors may be used to endorse or promote products
*       derived from this software without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY Peter Panov, IKEEN Group ``AS IS'' AND ANY
* EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
* DISCLAIMED. IN NO EVENT SHALL Peter Panov, IKEEN Group BE LIABLE FOR ANY
* DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
* (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
* ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
* SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/


$.fn.simpleTree = function(opt){
	return this.each(function(){
		var TREE = this;
		var ROOT = $('.root',this);
		var mousePressed = false;
		var mouseMoved = false;
		var dragMoveType = false;
		var dragNode_destination = false;
		var dragNode_source = false;
		var dragDropTimer = true;
		TREE.option = {
			reload:		true,
			drag:		true,
			animate:	false,
			autoclose:	false,
			speed:		'fast',
			moveInSameFolder: false,
			confirmMove: true,
			afterAjax:	false,
			afterMove:	false,
			afterClick:	false,
			afterDblClick:	false,
			docToFolderConvert:false,
			folderRightClick:false,
			fileRightClick:false,
			afterCloseFolder:false
		};
		TREE.option = $.extend(TREE.option,opt);
		$.extend(this, {getSelected: function(){
			return $('span.active', this).parent();
		}});
		$.extend(this, {reset: function(){
			// return $('span.active', this).parent();
			TREE.init(ROOT);
		}});
		TREE.closeNearby = function(obj)
		{
			$(obj).siblings().filter('.folder-open, .folder-open-last').each(function(){
				var childUl = $('>ul',this);
				var className = this.className;
				this.className = className.replace('open','close');
				if(TREE.option.animate)
				{
					childUl.animate({height:"toggle"},TREE.option.speed);
				}else{
					childUl.hide();
				}
			});
		};
		TREE.nodeToggle = function(obj)
		{
			var childUl = $('>ul',obj);			
			if(childUl.is(':visible')){
				obj.className = obj.className.replace('open','close');

				if(TREE.option.animate)
				{
					childUl.animate({height:"toggle"},TREE.option.speed);
				}else{
					childUl.hide();
				}
				if (TREE.option.reload) {
					// var temp_id = childUl.parent().attr("id").substr(2);
					var temp_id = childUl.parent().attr("id");
					childUl.html("<li>" + temp_id + "</li>\n").addClass("ajax");

					TREE.setTreeNodes(childUl, true);
				}
				if(typeof TREE.option.afterCloseFolder == 'function')
				{
					TREE.option.afterCloseFolder($(obj).attr("id"));
				}
			}else{		
				obj.className = obj.className.replace('close','open');
				if(TREE.option.animate)
				{
					childUl.animate({height:"toggle"},TREE.option.speed, function(){
						if(TREE.option.autoclose)TREE.closeNearby(obj);
						if(childUl.is('.ajax'))TREE.setAjaxNodes(childUl);
					});
				}else{
					
					childUl.show();
					if(TREE.option.autoclose)TREE.closeNearby(obj);
					if(childUl.is('.ajax'))TREE.setAjaxNodes(childUl);
				}
			}
		};
		TREE.setAjaxNodes = function(node)
		{
			var url = parseInt($('>li', node).text());
						
			if(url != null)	{

				//url=$.trim(url.replace(/.*\{url:(.*)\}/i ,'$1'));
				$.ajax({
					type: "POST",
					url: "ajax.php?gMethod=loadTree" + "&fid=" + url,
					data: $('#appForm').serialize(),
					dataType: 'html',
					cache:false,
					success: function(responce){
						node.removeAttr('class');
						node.html(responce);
						TREE.setTreeNodes(node, true);
						if(typeof TREE.option.afterAjax == 'function')
						{
							TREE.option.afterAjax(node);
						}
					}
				});
			}
		};
		TREE.setTreeNodes = function(obj, useParent){
			obj = useParent? obj.parent():obj;
			$('li>span', obj).addClass('text')
			.bind('selectstart', function() {
				return false;
			}).click(function(){
				$('.active',TREE).attr('class','text');
				if(this.className=='text')
				{
					this.className='active';
				}
				if(typeof TREE.option.afterClick == 'function')
				{
					TREE.option.afterClick($(this).parent());
				}
				return false;
			}).dblclick(function(){
				mousePressed = false;
				TREE.nodeToggle($(this).parent().get(0));
				if(typeof TREE.option.afterDblClick == 'function')
				{
					TREE.option.afterDblClick($(this).parent());
				}
				return false;
			}).mousedown(function(event){
				var LI = $(this).parent();
				mousePressed = true;
				cloneNode = $(this).parent().clone();
				
				if (TREE.option.drag) {
					$('>ul', cloneNode).hide();
					$('body').append('<div id="drag_container"><ul></ul></div>');
					$('#drag_container').hide().css({
						opacity: '0.8'
					});
					$('#drag_container >ul').append(cloneNode);
					$("<img>").attr({
						id: "tree_plus",
						src: "css/simpleTree/plus.gif"
					}).css({
						width: "7px",
						display: "block",
						position: "absolute",
						left: "5px",
						top: "5px",
						display: 'none'
					}).appendTo("body");
					
					$(document).bind("mousemove", {
						LI: LI
					}, TREE.dragStart).bind("mouseup", TREE.dragEnd);
				}
				return false;
			}).mouseup(function(){
				if(mousePressed && mouseMoved && dragNode_source)
				{
					TREE.moveNodeToFolder($(this).parent());
				}
				TREE.eventDestroy();
			});
			$('li', obj).each(function(i){
				var className = this.className;
				var open = false;
				var cloneNode=false;
				var LI = this;
				var childNode = $('>ul',this);
				if(childNode.size()>0){
					var setClassName = 'folder-';
					if(className && className.indexOf('open')>=0){
						setClassName=setClassName+'open';
						open=true;
					}else{
						setClassName=setClassName+'close';
					}
					this.className = setClassName + ($(this).is(':last-child')? '-last':'');

					if(!open || className.indexOf('ajax')>=0)childNode.hide();

					TREE.setTrigger(this);
					if (typeof TREE.option.folderRightClick == 'function') {
						TREE.option.folderRightClick($(this));
					}
				}else{
					var setClassName = 'doc';
					this.className = setClassName + ($(this).is(':last-child')? '-last':'');
					if (typeof TREE.option.fileRightClick == 'function') {
						TREE.option.fileRightClick($(this));
					}
				}
			}).before('<li class="line">&nbsp;</li>')
			.filter(':last-child').after('<li class="line-last"></li>');
			TREE.setEventLine($('.line, .line-last', obj));
		};
		TREE.setTrigger = function(node){
			$('>span',node).before('<img class="trigger" src="css/simpleTree/spacer.gif" border=0>');
			var trigger = $('>.trigger', node);
			trigger.click(function(event){
				TREE.nodeToggle(node);
			});
			if(!$.browser.msie)
			{
				trigger.css('float','left');
			}
		};
		TREE.dragStart = function(event){
			var LI = $(event.data.LI);
			if(mousePressed)
			{
				mouseMoved = true;
				if(dragDropTimer) clearTimeout(dragDropTimer);
				if($('#drag_container:not(:visible)')){
					$('#drag_container').show();
					LI.prev('.line').hide();
					dragNode_source = LI;
				}
				$('#drag_container').css({position:'absolute', "left" : (event.pageX + 5), "top": (event.pageY + 15) });
				if(LI.is(':visible'))LI.hide();
				var temp_move = false;

				if(event.target.tagName.toLowerCase()=='span' && $.inArray(event.target.className, Array('text','active','trigger'))!= -1)
				{
					var parent = event.target.parentNode;
					var offs = $(parent).offset({scroll:false});
					var screenScroll = {x : (offs.left - 3),y : event.pageY - offs.top};
					var isrc = $("#tree_plus").attr('src');
					var ajaxChildSize = $('>ul.ajax',parent).size();
					screenScroll.x += 19;
					screenScroll.y = event.pageY - screenScroll.y + 5;

					if(parent.className.indexOf('folder-close')>=0 && ajaxChildSize==0)
					{
						if(isrc.indexOf('minus')!=-1)$("#tree_plus").attr('src','css/simpleTree/plus.gif');
						$("#tree_plus").css({"left": screenScroll.x, "top": screenScroll.y}).show();
						dragDropTimer = setTimeout(function(){
							parent.className = parent.className.replace('close','open');
							$('>ul',parent).show();
						}, 700);
					}else if(parent.className.indexOf('folder')>=0 && ajaxChildSize==0){
						if(isrc.indexOf('minus')!=-1)$("#tree_plus").attr('src','css/simpleTree/plus.gif');
						$("#tree_plus").css({"left": screenScroll.x, "top": screenScroll.y}).show();
					}else if(parent.className.indexOf('folder-close')>=0 && ajaxChildSize>0)
					{
						// mouseMoved = false;
						// $("#tree_plus").attr('src','css/simpleTree/minus.gif');
						$("#tree_plus").attr('src','css/simpleTree/plus.gif');
						$("#tree_plus").css({"left": screenScroll.x, "top": screenScroll.y}).show();
					}else{
						if(TREE.option.docToFolderConvert)
						{
							$("#tree_plus").css({"left": screenScroll.x, "top": screenScroll.y}).show();
						}else{
							$("#tree_plus").hide();
						}
					}
				}else{
					$("#tree_plus").hide();
				}
				return false;
			}
			return true;
		};
		TREE.dragEnd = function(){
			if(dragDropTimer) clearTimeout(dragDropTimer);
			TREE.eventDestroy();
		};
		TREE.setEventLine = function(obj){
			obj.mouseover(function(){
				if(this.className.indexOf('over')<0 && mousePressed && mouseMoved)
				{
					this.className = this.className.replace('line','line-over');
				}
			}).mouseout(function(){
				if(this.className.indexOf('over')>=0)
				{
					this.className = this.className.replace('-over','');
				}
			}).mouseup(function(){
				if(mousePressed && dragNode_source && mouseMoved)
				{
					dragNode_destination = $(this).parents('li:first');
					TREE.moveNodeToLine(this);
					TREE.eventDestroy();
				}
			});
		};
		TREE.checkNodeIsLast = function(node)
		{
			if(node.className.indexOf('last')>=0)
			{
				var prev_source = dragNode_source.prev().prev();
				if(prev_source.size()>0)
				{
					prev_source[0].className+='-last';
				}
				node.className = node.className.replace('-last','');
			}
		};
		TREE.checkLineIsLast = function(line)
		{
			if(line.className.indexOf('last')>=0)
			{
				var prev = $(line).prev();
				if(prev.size()>0)
				{
					prev[0].className = prev[0].className.replace('-last','');
				}
				dragNode_source[0].className+='-last';
			}
		};
		TREE.eventDestroy = function()
		{
			$(document).unbind('mousemove').unbind('mouseup').unbind('mousedown');
			$('#drag_container, #tree_plus').remove();
			if(dragNode_source)
			{
				$(dragNode_source).prev('.line').show();
				$(dragNode_source).show();
			}
			dragNode_destination = dragNode_source = mousePressed = mouseMoved = false;
		};
		TREE.convertToFolder = function(node){
			node[0].className = node[0].className.replace('doc','folder-open');
			node.append('<ul><li class="line-last"></li></ul>');
			TREE.setTrigger(node[0]);
			TREE.setEventLine($('.line, .line-last', node));
		};
		TREE.convertToDoc = function(node){
			$('>ul', node).remove();
			$('img', node).remove();
			node[0].className = node[0].className.replace(/folder-(open|close)/gi , 'doc');
		};
		TREE.moveNodeToFolder = function(node)
		{
			if(!TREE.option.docToFolderConvert && node[0].className.indexOf('doc')!=-1)
			{
				return true;
			}else if(TREE.option.docToFolderConvert && node[0].className.indexOf('doc')!=-1){
				TREE.convertToFolder(node);
			}
			TREE.checkNodeIsLast(dragNode_source[0]);
			var lastLine = $('>ul >.line-last', node);
			if(lastLine.size()>0)
			{
				TREE.moveNodeToLine(lastLine[0]);
			}
		};
		TREE.moveNodeToLine = function(node){
			if (!TREE.option.moveInSameFolder) {
				var node_id = $(node).parent().parent().attr("id");
				var dragNode_id = $(dragNode_source).parent().parent().attr("id");
				if(node_id == dragNode_id) return false;
			}
			if(TREE.option.confirmMove){
				if(!confirm("Confirm Move?")) return false;
			}
			TREE.checkNodeIsLast(dragNode_source[0]);
			TREE.checkLineIsLast(node);
			var parent = $(dragNode_source).parents('li:first');
			var line = $(dragNode_source).prev('.line');
			$(node).before(dragNode_source);
			$(dragNode_source).before(line);
			node.className = node.className.replace('-over','');
			var nodeSize = $('>ul >li', parent).not('.line, .line-last').filter(':visible').size();
			if(TREE.option.docToFolderConvert && nodeSize==0)
			{
				TREE.convertToDoc(parent);
			}else if(nodeSize==0)
			{
				parent[0].className=parent[0].className.replace('open','close');
				$('>ul',parent).hide();
			}
			if(typeof(TREE.option.afterMove) == 'function')
			{
				var pos = $(dragNode_source).prevAll(':not(.line)').size();
				TREE.option.afterMove($(node).parents('li:first'), $(dragNode_source), pos);
			}
		};
		TREE.init = function(obj)
		{
			TREE.setTreeNodes(obj, false);
		};
		TREE.init(ROOT);
	});
}