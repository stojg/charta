;(function(h,$) {

	function ink(element, evt) {
		var parent, x, y, d;

		parent = element.parent();
		if(parent.find(".ink").length == 0) {
			parent.prepend("<span class='ink'></span>");
		}

		ink = parent.find(".ink");
		//in case of quick double clicks stop the previous animation
		ink.removeClass("animate");

		//set size of .ink
		if(!ink.height() && !ink.width())
		{
			//use parent's width or height whichever is larger for the diameter to make a circle which can cover the entire element.
			d = Math.max(parent.outerWidth(), parent.outerHeight());
			ink.css({height: d, width: d});
		}

		x = evt.pageX - parent.offset().left - ink.width() / 2;
		y = evt.pageY - parent.offset().top - ink.height() / 2;
		//set the position and add class .animate
		ink.css({top: y+'px', left: x+'px'}).addClass("animate");
	}

	var floppy = {
		set: function(state) {
			$('.glyphicon-floppy-disk').removeClass(this.state);
			this.state = state;
			$('.glyphicon-floppy-disk').addClass(this.state);
		},
		inState: function(state) {
			return (this.state === state);
		}
	};

	$('document').ready(function(){

		hljs.initHighlightingOnLoad();

		$("ul li a").click(function(evt){
			ink($(this), evt);
		});

		floppy.set('saved');

		Mousetrap.bind('e', function() {
			var url = $(".actions .edit").attr('href');
			if(url) {
				window.location.href = url;
			}
		});

		if($("textarea.editor").length == 1) {

			var cm = CodeMirror.fromTextArea($('textarea').get(0), {
				mode: 'markdown',
				theme: 'paper',
				tabSize: '4',
				indentWithTabs: true,
				lineNumbers: false,
				autofocus: true,
				lineWrapping: true,
				viewportMargin: Infinity
			});

			cm.on("change", function(cm, change) {
				floppy.set('dirty');
			});

			if($('form.edit').length != 0) {
				setInterval(function(){
					if(floppy.inState('saving') || floppy.inState('saved')) {
						return;
					}

					floppy.set('saving');

					$.ajax({
						url: $("form").attr('action'),
						dataType: 'json',
						method: 'POST',
						data: {
							content: cm.getValue(),
							title: $('form .title').val(),
							_token: $('form .token').val()
						},
						cache: false,
						success: function() {
							floppy.set('saved');
						},
						error: function(xhr, status, err) {
							console.error(status, err);
						}
					});
				}, 500);
			}
		}

	});
})(document, jQuery);