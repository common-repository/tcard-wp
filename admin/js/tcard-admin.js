/* 
 
 Author: Cloanta Alexandru
 Name: Tcard Wordpress
 Version: 1.8.0
 
 */

(function( $ ) {
	'use strict';

	$(document).on("ready",function(){

		var stopClick = false;

		$(document).on("click",".tcard-row-bar",function(e){

		  	if (e.target != this)
					return;
			var tcardArrow = $(this).find("input.tcard_check");
			$(this).next(".tcard-skin").slideToggle(); 

			(tcardArrow.prop("checked")) ? tcardArrow.prop("checked",false) : tcardArrow.prop("checked",true);
		});

		$(".tcard-container-skins").sortable({
			handle: ".tcard-row-bar",
			opacity: 0.8,
			cursor: "move",
			delay:100,
			placeholder: "tcard-highlight",
			start: function(event, ui) {

				for(var i = 0; i < $(".tcard-row").length;i++){
					$(".tcard-row").eq(i).attr("data-index",i)
				}

				$(".tcard-highlight").height(ui.item.height())

				
		    },
			update: function(event, ui) {

			 	Tcard.reorder(".tcard-row");

			 	$(".tcard-textarea").each(function(){
			 		wp.editor.remove($(this).attr('id'));
					Tcard.add_wp_editor($(this));
			 	});
			}
		});

		var number = 0;
		$(".tc-add-new-skin").on("click",function(e){
			if(!$(".select-tcard-group select").val()){

				alert("Please create a group");
				$(this).find("input").val('')
				return;
			}
			else if(!$("#select-skin").val()){

				alert("Please select type of skin");
				$(this).find("input").val('')
				return;
			}
			else{

				if(stopClick) return;
				if($(this).find("input").val() > 0){
					number = $(this).find("input").val();
				}

				number++;

				$(".tcard-count-skin").find("span").text(number)
				$(this).find("input").val(number);

				TcardAjax.add_skin('new-skin',$("#select-skin").val(),number,'');
			}
			
		});

		$(document).on("click",".tcard-delete-skin",function(){

			var skin_index = $(this).closest(".tcard-row").index();

			$(".tcard-row").each(function(){
				$(this).attr("data-index",$(this).index())
			});

			$(this).closest(".tcard-row").remove();

			$(".tcard-count-skin").find("span").text($(".tcard-row").length)
			$(".tc-add-new-skin").find("input").val($(".tcard-row").length);

			for (var i = 0; i < $(".tcard-row").length; i++) {
				$(".tcard-skin-order").eq(i).text(i + 1)
			}

			TcardAjax.delete_skin(skin_index,$(".tcard-row").length);
			Tcard.reorder(".tcard-row");
			
		});

		$("#select-skin").on('change',function(){

			if($(".select-tcard-group select").val()){

				TcardAjax.select_skin($(this).val());
			}
			else{

				alert("Please create a group.");
				$(this).val('') 
				return;
			}
		});	

		$(".delete-tcard-group").on("click",function(){
			var group_title;
			
			if($(".tcard-group-title").val()){
				group_title = $(".tcard-group-title").val();
			}else{
				group_title = $(".select-tcard-group select option[selected]").text();
			}

			return confirm('Are you sure you want to remove '+ group_title +' group ?');
		});

		$(document).on("click",".tcard-settings",function(){
			$(this).next(".tcard-modal").fadeIn().addClass("is-open");
			$(".tcard-container-skins").sortable("disable");
		});

		$(document).on("click",".tcard-element-bar",function(){
			Tcard.modal($(this).next(".tcard-modal"));
		});

		$(document).on("click",".tcard-modal,.tcard-close-modal",function(e){

			if (e.target != this)
					return;
			$(this).closest(".tcard-modal").fadeOut().removeClass("is-open");

			setTimeout(function(){
				$(".tcard-container-skins").sortable("enable"); 	
			},200);
		});

		$('#tcard-save').on("click",function(e){
			window.onbeforeunload = null;
			$(".spinner").css("visibility","visible");

		});
		
        $('.copy-shortcode').on('click', function () {
            Tcard.copy_shortcode(document.getElementById('tcard-code'))
        });
 
        $('.tcard-shortcode').on('click', function () {
           Tcard.copy_shortcode(this)
        });

		$(".elements-menu h4").on("click",function(){

			if(!$(this).hasClass("tc-current-side")){
				if(stopClick) return;

				var thisMenuAttr = $(this).attr("data-tcard-menu");
				$(".elements-menu h4").removeClass("tc-current-side");
				$(this).addClass("tc-current-side");

				Tcard.fade_content(thisMenuAttr,".tcard-item-inner","data-tcard-box",".tcard-sidebar-item.elements",10);
			}
		});

		$(document).on("click",".tcard-add-item",function(){
			Tcard.add_item_list($(this),$(this).text());
		});

		$(".tcard-input").on("input",function(e){
			window.onbeforeunload = function() { return true };
		});

		$(document).on("click",".tcard-remove-item",function(){
			$(this).closest(".tcard-modal-item").remove();
		})

		$(document).on("click",".settings-btn",function(){

			if(!$(this).hasClass("tc-current-side")){
				if(stopClick) return;

				var thisMenuAttr = $(this).attr("data-menu-container");
				$(".tcard-modal.is-open .settings-btn").removeClass("tc-current-side");
				$(this).addClass("tc-current-side");
				var modalContainer = $(this).closest(".tcard-modal").find(".tcard-modal-container"),
					modalContent = $(this).closest(".tcard-modal").find(".tcard-modal-content");
				Tcard.fade_content(thisMenuAttr,modalContainer,"data-modal-container",modalContent,20);
			}
		});
		var stop = false;
		var Tcard = {
			fade_content: function(thisMenuAttr,item,attr,parent,padding){
				stopClick = true;

				$(item).each(function(){
					var thisBox = $(this).attr(attr);
					var thisItem = $(this);
					if(thisBox.indexOf(thisMenuAttr) !== -1){
						$(this).fadeIn(500)
						$(this).parent(parent).animate({
							height: thisItem.height() + padding
						},200);
						var btnWpcolor = $(this).find(".button.wp-color-result"),
						oldHeight = thisItem.height() + padding;
						if(btnWpcolor.length){
							btnWpcolor.on("click",function(){
								thisItem.parent(parent).animate({
									height: thisItem.height() + padding
								},100);
							});
							thisItem.on("click",function(){
								if(btnWpcolor.hasClass("wp-picker-open"))
								thisItem.parent(parent).animate({
									height: oldHeight
								},100);
							});
						}
					}
					else{
						$(this).fadeOut(0);
					}

				});
				
				setTimeout(function(){
					stopClick = false;
				},400);		
			},
			setWidth: function(action,item){
				var classes = ['tc-1', 'tc-2', 'tc-3', 'tc-4'];
				var step = -1;

				classes.forEach(function(val, key) {
					if (item.closest(".tcard-element").hasClass(classes[key])) {
						step = key;
					}
				});

				item.closest(".tcard-element").each(function(){

					if (action == "increase") {
						if (step < classes.length - 1) {
							step++;
							$(this).removeClass(classes[step - 1]).addClass(classes[step]);
						}
					} 
					else if (action == "decrease") {
						if (step > 0) {
							step--;
							$(this).removeClass(classes[step + 1]).addClass(classes[step]);

						}
					}

					$(this).one("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function() {
						var calcWidth = Math.round(($(this).width() + 6) / $(this).parent().width() * 100) + "%";
						$(this).find(".elem-width").val(calcWidth);
					});
				});
				
				window.onbeforeunload = function() { return true };
			},
			modal: function(modal){
				modal.fadeIn().addClass("is-open");

				modal.find(".tc-input-title").on("input",function(){
					var title;
					if($(this).val().length > 12){
						title = strip($(this).val()).substr(0, 12) + '...'
					}else{
						title = strip($(this).val());
					}

					$(this).closest(".tcard-element").find(".tcard-element-bar span").text(title);

					function strip(html){
						var tmp = document.createElement("DIV");
						tmp.innerHTML = html;
						return tmp.textContent || tmp.innerText;
					}
				});

				modal.find(".clear_after_login").on("click",function(){
					$(this).next("select").each(function(){
						$(this).find("option").removeAttr('selected');
					});
				});

				modal.find(".select-social").on("change",function(){
					$(this).closest(".tcard-element").find(".tcard-element-bar span").text($(this).val());
				});

				if(modal.find(".tc-show-input").length){
					modal.find("select.tc-show-input").on("change",function(){

						if($(this).closest(".tc_button").length){
							if($(this).val() == "text" || $(this).val() == "link"){
								$(this).closest(".tc_button").find(".tchp_text_btn").css("display","block");
							}else{
								$(this).closest(".tc_button").find(".tchp_text_btn").css("display","none").val('');
							}
						}else{
							if($(this).val() == "text" || $(this).val() == "link"){
								$(this).next(".tchp_text_btn").css("display","block");
							}else{
								$(this).next(".tchp_text_btn").css("display","none").val('');
							}
						}
					});
				}
			},
			reorder: function(item){

				var setOrder = [];
				$(".tcard_skin_id").each(function(){
					setOrder.push($(this).val());
				});

				setOrder.sort(function(a, b){
				  return a - b;
				});

				for (var i = 0; i < $(".tcard_skin_id").length; i++) {
					$(".tcard_skin_id").eq(i).val(setOrder[i]);
				}
	
				$(item).each(function(){

					
					var oldOrder = parseInt($(this).attr("data-index"));
					var newOrder = $(this).index();
	
					$(this).find(".tcard-skin-order").text(newOrder + 1);
					$(this).find(".tcard_check").attr("name","tcard_check_order"+newOrder+"");
					
					$(this).find(".tcard-input").each(function(){
						var setOrder = $(this).attr("name").replace(oldOrder,newOrder);
	        			$(this).attr("name",setOrder);
					});

					$(this).find(".assigns-tcard-gallery select,.tcg-box input").each(function(){
						var setOrder = $(this).attr("name").replace(oldOrder,newOrder);
	        			$(this).attr("name",setOrder);
					});

					window.onbeforeunload = function() { return true };	
				});
			},
			add_wp_editor: function(textarea){
				$(textarea).each(function(){
					var thisID = $(this).attr("id");
 					wp.editor.initialize(
					  thisID,
					  	{ 
						    mediaButtons: true,
						    tinymce: { 
						      	wpautop:true,
						      	toolbar1: 'formatselect bold italic | bullist numlist | blockquote | alignleft aligncenter alignright | wp_more',
						     	min_height: "150",
						     	plugins: "colorpicker lists compat3x directionality link image charmap hr image fullscreen media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wpview wptextpattern",
                                wpautop: false, 
						     	init_instance_callback: function (editor) {
								    editor.on('keyup', function (e) {
								    	$("#" + editor.id).each(function(){
								    		if($(this).closest(".tcard-modal-item").hasClass('tc-textarea-title')){
									    		var title,
									    		val = tinyMCE.activeEditor.getContent({ format: 'text' });
												if(val.length > 12){
													title = val.substr(0, 12) + '...'
												}else{
													title = val;
												}
												$(this).closest(".tcard-element").find(".tcard-element-bar span").text(title);
												window.onbeforeunload = function() { return true }
								    		}
										});
								    });
							  	}
						    }, 
						    quicktags: {
						        buttons: "strong,em,link,img,block,ul,ol,li,code,ins,more"
						    }
					  	}
					);	
		 		});
			},
			add_item_list: function (thisBtn,name){

				var skin = thisBtn.closest(".tcard-row").index(),
				tcardside = thisBtn.closest(".tcard-main-elem"), side;
					if(tcardside.hasClass("front-side")) side = "front";
						else side = "back";

				var lastIten = thisBtn.closest(".tcard-modal-body").find(".tcard-modal-item"),
					modalContent = thisBtn.closest(".tcard-modal-body").find(".tcard-modal-content"),thisElement;

					if(thisBtn.attr("data-itemnum")){
						thisElement = thisBtn.attr("data-itemnum");
					}else{
						thisElement = "";
					}

				var listItem = 
				'<div class="tcard-modal-item">' +
					'<div class="tcard-modal-item-inner">' +
						'<h4 class="tcard-with-em">Item Title: <br><span class="tcard-em">Require</span></h4>' + 
						'<input class="tcard-input" type="text" name="footer'+skin+'_'+side+'[info_list_title'+thisElement+'][]" value="">' +
						'<h4 class="tcard-remove-item"></h4>' +
					'</div>' +
					'<div class="tcard-modal-item-inner">' +
						'<h4>Item Text: </h4>' + 
						'<input class="tcard-input" type="text" name="footer'+skin+'_'+side+'[info_list_text'+thisElement+'][]" value="">' +
					'</div>' +
					'<div class="tcard-animation">' +
						'<h4>Delay:</h4>' +
						'<input class="tcard-input" type="number" name="footer'+skin+'_'+side+'[delay][]" value="">' +
					'</div>' +
				'</div>';

				var title,order,item,parent,icon,placeholder;
				if(modalContent.hasClass('contact')){
					title = '<h4 class="tcard-with-em">Label name: <br> <em class="tcard-em">Default : '+name+' </em></h4>';
					item = 'contact_item';
					parent = 'content';
					order = 'contact';
					placeholder = name;
				}else if(modalContent.hasClass('register')){
					title = '<h4 class="tcard-with-em">Label name: <br> <em class="tcard-em">Default : '+name+' </em></h4>';
					item = 'register_item';
					parent = 'content';
					order = 'register';
					placeholder = name;
				}else{
					if(name == "google+"){
						icon = "google-plus-square";
					}else if(name == "instagram" || name == "linkedin" || name == "flickr"){
						icon = name;
					}else{
						icon = name + "-square";
					}
					title = '<h4><i class="fab fa-'+icon+'"></i> </h4>';
					order = 'social_list_order'+ thisElement;
					item = 'social_list' + thisElement;
					parent = 'footer';
					placeholder = name + " username";
				}

				var socialItem =
				'<div class="tcard-modal-item">' +
					'<div class="tcard-modal-item-inner">' +
						'<input class="tcard-input" type="hidden" name="'+parent+''+skin+'_'+side+'['+order+'][]" value="'+name+'">' +
						title +
						'<input class="tcard-input" placeholder="'+placeholder+'" type="text" name="'+parent+''+skin+'_'+side+'['+item+'][]" value="">' +
						'<h4 class="tcard-remove-item"></h4>' +
					'</div>' +
					'<div class="tcard-animation">' +
						'<h4>Delay:</h4>' +
						'<input class="tcard-input" type="number" name="'+parent+''+skin+'_'+side+'[delay][]" value="">' +
					'</div>' +
				'</div>';

				var skillItem = 
				'<div class="tcard-modal-item tc-skill-item">'+
					'<div class="tc-skill-name">'+
						'<h4>Skill name: </h4>'+ 
						'<input class="tcard-input" type="text" name="content'+skin+'_'+side+'[skills_skill'+thisElement+'][]" value="">'+
						'<h4 class="tcard-remove-item"></h4>'+
					'</div>'+
					'<div class="tc-skill-percent">'+
						'<h4>Skill percent: </h4>'+
						'<input class="tcard-input" type="number" name="content'+skin+'_'+side+'[skills_percent'+thisElement+'][]" value="">'+
					'</div>'+
					'<div class="tcard-animation">'+
						'<h4>Delay:</h4>'+
					    '<input class="tcard-input" type="number" name="content'+skin+'_'+side+'[delay][]" value="">' +
					'</div>'+
				'</div>';

				var list = 
				'<div class="tcard-modal-item">' +
					'<div class="tcard-modal-item-inner">' +
						'<h4>Text: </h4>' +
						'<input class="tcard-input" type="text" name="content'+skin+'_'+side+'[list'+thisElement+'][]" value="">' +
						'<h4 class="tcard-remove-item"></h4>' +
					'</div>' +
					'<div class="tcard-animation">'+
						'<h4>Delay:</h4>'+
					    '<input class="tcard-input" type="number" name="content'+skin+'_'+side+'[delay][]" value="">' +
					'</div>'+
				'</div>';

				var socialButton =
				'<div class="tcard-modal-item">' +
					'<div class="tcard-modal-item-inner">' +
						'<input class="tcard-input" type="hidden" name="header'+skin+'_'+side+'[social_button_order'+thisElement+'][]" value="'+name+'">' +
						title+
						'<input class="tcard-input" type="text" placeholder="'+name+' username" name="header'+skin+'_'+side+'[social_button'+thisElement+'][]" value="">' +
						'<h4 class="tcard-remove-item"></h4>' +
					'</div>' +
				'</div>';

				var social_parent = thisBtn.closest(".tcard-item").attr("data-item");
				social_parent = social_parent.replace("tcard-","");

				var social_profile = 
				'<div class="tcard-modal-item">' +
					'<div class="tcard-modal-item-inner">' +
						'<input class="tcard-input" type="hidden" name="'+social_parent+''+skin+'_'+side+'[instagram_profile_order'+thisElement+'][]" value="'+name+'">' +
						'<h4 class="social_profile"><span>'+name+'</span> animation: </h4>' +
						'<div class="tcard-animation">' +
							'<h4>Animation In:</h4>' +
							'<select class="tcard-input" name="'+social_parent+''+skin+'_'+side+'[animation_in][]">' +
								animations_in()+
							'</select>' +
						'</div>' +
						'<h4 class="tcard-remove-item"></h4>' +
						'<div class="tcard-animation">' +
							'<h4>Animation Out:</h4>' +
							'<select class="tcard-input" name="'+social_parent+''+skin+'_'+side+'[animation_out][]">' +
								animations_out()+
							'</select>' +
						'</div>' +
						'<div class="tcard-animation">'+
							'<h4>Delay:</h4>'+
						    '<input class="tcard-input" type="number" name="'+social_parent+''+skin+'_'+side+'[delay][]" value="">' +
						'</div>'+
					'</div>' +
				'</div>';

				function animations_in(){
					var html, animations_in = ['shake','headShake','swing','tada','wobble','jello','bounceIn','bounceInDown','bounceInLeft','bounceInRight',
						'bounceInUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig',
						'flipInX','flipInY','lightSpeedIn','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight','hinge',
						'jackInTheBox','rollIn','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','slideInDown','slideInLeft','slideInRight','slideInUp'
					];

					html = '<option></option>';
					for (var i = 0; i < animations_in.length; i++) {
						html += '<option value='+animations_in[i]+'>'+animations_in[i]+'</option>';

					}

					return html;
				}

				function animations_out(){
					var html, animations_out = ['shake','headShake','swing','tada','wobble','jello','bounceOut','bounceOutDown','bounceOutLeft','bounceOutRight',
						'bounceOutUp','fadeOut','fadeOutDown','fadeOutDownBig','fadeOutLeft','fadeOutLeftBig','fadeOutRight','fadeOutRightBig','fadeOutUp','fadeOutUpBig',
						'flipOutX','flipOutY','lightSpeedOut','rotateOut','rotateOutDownLeft','rotateOutDownRight','rotateOutUpLeft','rotateOutUpRight','hinge',
						'jackInTheBox','rollOut','zoomOut','zoomOutDown','zoomOutLeft','zoomOutRight','zoomOutUp','slideOutDown','slideOutLeft','slideOutRight','slideOutUp'
					];

					html = '<option></option>';
					for (var i = 0; i < animations_out.length; i++) {
						html += '<option value='+animations_out[i]+'>'+animations_out[i]+'</option>';

					}
					return html;
				}

				if(modalContent.hasClass('info_list')){
					lastIten.last().before($(listItem).appendTo(modalContent));
				}
				else if(modalContent.hasClass('social_list') || modalContent.hasClass('contact') || modalContent.hasClass('register')){
					if(modalContent.hasClass('social_list')){
						lastIten.last().before($(socialItem).appendTo(modalContent));
					}else{
						lastIten.last().prev().before($(socialItem).appendTo(modalContent));
					}
				}
				else if(modalContent.hasClass('skills')){
					lastIten.last().before($(skillItem).appendTo(modalContent));
				}
				else if(modalContent.hasClass('list')){
					lastIten.last().before($(list).appendTo(modalContent));
				}
				else if(modalContent.hasClass('social_button')){
					if(lastIten.filter(".last").length){
						lastIten.filter(".last").before($(socialButton).appendTo(modalContent));
					}else{
						lastIten.last().before($(socialButton).appendTo(modalContent));
					}
				}else if(modalContent.hasClass('instagram_profile')){
					$(social_profile).appendTo(modalContent);
				}

				window.onbeforeunload = function() { return true };
				
			},
			copy_shortcode: function(elm) {
				var range;
	            var selection;

	            if (window.getSelection) {
	                selection = window.getSelection();
	                range = document.createRange();
	                range.selectNodeContents(elm);
	                selection.removeAllRanges();
	                selection.addRange(range);
	            } else if (document.body.createTextRange) {
	                range = document.body.createTextRange();
	                range.moveToElementText(elm);
	                range.select();
	            }

				document.execCommand("Copy");
			},
			upload_image:function(){
	    		var frame = wp.media({
					title : 'Tcard Profile Images Upload',
					multiple : false,
					library : { type : 'image' },
					button : { text : 'Insert into profile' },
				});

	    		var thisBtn;
	    		$(document).on("click",".tcard-up-image",function(e){

				    frame.open();
				    thisBtn = $(this);
					return false;
				});

				frame.on( 'select', function() {
					var attachment = frame.state().get('selection').toJSON();
					if(attachment.length){
						thisBtn.prev(".tcard-image-input").val(attachment[0].url);
						thisBtn.closest(".tcard-modal-content").find(".tcard-profile-image").css("display","block")
						thisBtn.closest(".tcard-modal-content").find(".tcard-profile-image img").attr("src",attachment[0].url)
					}
					return false;
				});	

				$(".tcard-image-input").on("change",function(){
					$(this).closest(".tcard-modal-content").find(".tcard-profile-image").css("display","block")
					$(this).closest(".tcard-modal-content").find(".tcard-profile-image img").attr("src",$(this).val());
	    		});
			},
			gallery: function(){

				var frame = wp.media({
					title : 'Tcard Multiple Images Upload',
					multiple : true,
					library : { type : 'image' },
					button : { text : 'Insert into gallery' },
				});

				var thisBtn;
				$(document).on("click",".tc-multiple-images",function(){

					frame.open();
					thisBtn = $(this);
					return false;
				});

				frame.on( 'select', function() {
					var attachment = frame.state().get('selection').toJSON();
					if(attachment.length){
						for (var i = 0; i < attachment.length; i++) {
							var boxImg = 
							'<div class="tcg-box" style="background-image: url('+attachment[i].url+')">'+
								'<input type="hidden" name="tcg_gallery'+ thisBtn.closest(".tcard-row").index() +'[image][]" value='+attachment[i].url+'>'+
								'<div class="remove-tcg-img"></div>' +
							'</div>';
							thisBtn.closest(".tcard-gallery").find(".gallery").append(boxImg);
						}
					}
					return false;
				});

				var oldUser;
				$(document).on("focusin",".assigns-tcard-gallery select",function(){
					oldUser = this.value
				});

				$(document).on("change",".assigns-tcard-gallery select",function(){
					if($(this).val() !== oldUser){
						$(this).closest(".tcard-gallery").find(".gallery").remove();
						if($(this).val().length || !$(this).val().length){
							$(this).closest(".tcard-gallery").append('<div class="gallery"></div>');
							sort_gallery();							
						} 
					}

					if($(this).val()){
						$(this).closest(".tcard-gallery").find(".tc-multiple-images").remove();
					}else{
						var gallery_btn = '<span class="tc-multiple-images"><i class="fas fa-cloud-upload-alt"></i></i></span>';
						$(this).closest(".tcard-gallery").find(".tcard-gallery-bar").append(gallery_btn)
					}
					window.onbeforeunload = function() { return true };
				});

				$(document).on("click",".remove-tcg-img",function(){
					$(this).closest(".tcg-box").remove();
				});

				$(document).on("change",".thumbnail-name select",function(){
					var skin = $(this).closest(".tcard-row").index(),
					thumb_name = '<input class="thumbnail_title" type="text" name="tcg_gallery'+skin+'[thumbnail_title]" value="">';

					if($(this).val() == "thumbnail_title"){
						$(this).parent().append(thumb_name);
					}else{
						$(this).parent().find(".thumbnail_title").remove();
					}
					window.onbeforeunload = function() { return true };
				});

				function sort_gallery(){
					$(".tcard-gallery .gallery").sortable({
						opacity: 0.8,
						cursor: "move",
						delay:100,
						placeholder: "tcg-highlight",
						start: function(event, ui) {
							$(".tcg-highlight").height(ui.item.outerHeight())
					    }
					});
				}
				sort_gallery();
			}
		}
 		Tcard.add_wp_editor(".tcard-textarea");
 		Tcard.upload_image();
 		Tcard.gallery();

		var TcardAjax = {
			add_skin: function(type_action,nameSkin,numberSkin,skinCloned){
				stopClick = true;

				var data = {
			      action: 'tcard_add_skin',
			      security: tcard.add_skin,
			      group_id: tcard.group_id,
			      type_action: type_action,
			      nameSkin: nameSkin,
			      startCount: numberSkin - 1,
			      stopCount: numberSkin,
			      skinCloned: skinCloned
			    };

				$.ajax({
		            url: ajaxurl,
		            type: 'POST',
		            data: data,
		            success:function(data){
			            $(".spinner").css("visibility","visible");
			            if(type_action == "clone-skin"){
			            	$(data).appendTo(".tcard-container-skins").find(".skin-cloned-after")
			            	.html('<i class="fas fa-clone"></i> ' + nameSkin + "." + (skinCloned + 1));
			            }else{
			            	$(data).appendTo(".tcard-container-skins");
			            }
			            
			            $.when(data).promise().done(function(){
			            	$(".spinner").css("visibility","hidden");
		            		Tcard.mainElements();
		            		Tcard.add_wp_editor($(data).find(".tcard-textarea"));
	
		            		if($(".tcard-container-skins").hasClass("customSkin")){
		            			Tcard.sortable();
			            		$(".modal-post-content").sortable({
									opacity: 0.8,
									cursor: "move",
									delay:100,
								});	
		            		}

		            		Tcard.colorPicker('.tcard-color-picker');
							stopClick = false;
						});
		            },
		            error: function(error){
		            	alert("Skin could not load");

		            	if(!$(".tcard-row").eq(number).length){
							number = number - 1;
						}else{
							number = numberSkin - 1;
						}

		            	$(".tcard-count-skin").find("span").text(number)
						$(".tc-add-new-skin").find("input").val(number);
						TcardAjax.delete_skin(number,$(".tcard-row").length);
		            }
		        });
			},
			delete_skin: function(thisSkin,skins_number){

				var data = {
			      action: 'tcard_delete_skin',
			      security: tcard.delete_skin,
			      group_id: tcard.group_id,
			      skin_key: thisSkin,
			      skins_number: skins_number
			    };

				$.ajax({
		            url: ajaxurl,
		            type: 'POST',
		            data: data,
		            success:function(success){
						window.onbeforeunload = function() { return true };
		            },
		            error: function(){
		            	alert("No skin-"+ thisSkin +" found")
		            }
		        });
			},
			select_skin: function(skin_type){
				var data = {
			      action: 'tcard_select_skin',
			      security: tcard.select_skin,
			      group_id: tcard.group_id,
			      group_name: $('.tcard-group-title').val(),
			      skin_type: skin_type
			    };

			    $.ajax({
		            url: ajaxurl,
		            type: 'POST',
		            data: data,
		            success:function(){
		            	window.onbeforeunload = null;
			            location.reload();
		            },
		            error: function(){
		     			 alert("File missing!")
		            }
		        });
			}
		}
	});
})( jQuery );