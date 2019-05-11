(function( $ ) {
	'use strict';

	// Create a modal view.
	var modal = new wp.media.view.Modal({
		// A controller object is expected, but let's just pass
		// a fake one to illustrate this proof of concept without
		// getting console errors.
		controller: { trigger: function() {} }
	});

	function handlePostMessage(event){
		if ( ! _.isUndefined( event.data['resourceHandles'] ) && ! _.isUndefined( event.data['resourceType'] ) && ! _.isUndefined( event.data['shop'] ) ) {
			$("#sbb_resource_handle").val(event.data['resourceHandles'][0]);
			$("#sbb_resource_type").val(event.data['resourceType']);
			$("#sbb_shop_domain").val(event.data['shop']);
			$('.shopify-shortcode-generator').show();
			$('.shopify-picker-iframe').hide();
		}
	}

	// Create a modal content view.
	var ModalContentView = wp.Backbone.View.extend({
		
		shortcode: 'shopify_buy_button',

		template: wp.template( 'shopify-buybuttonwp-modal-content' ),

		activeEditor: null,
		
		events: {
			'click .js--select-shopify-product': 'showProductFrame',
			'click .js--shopify-generate-shortcode-btn': 'submitShortcode'
		},
		initialize: function(){
			window.addEventListener('message', handlePostMessage, false);
			//this.listenTo( modal, 'open', this.initJS, this );
		},
		showProductFrame: function( event ){
			event.preventDefault();
			$('.shopify-shortcode-generator').hide();
			$('.shopify-picker-iframe').show();
		},
		submitShortcode: function(event){
			event.preventDefault();
			var shortcode = this.generateShortcode();
			this.insertToEditor( shortcode );
			this.resetForm();
			modal.close();
		},
		generateShortcode: function() {
			var has_image = 'false',
				has_modal = 'false';
			if($("#sbb_has_image:checked", this.$el).length){
				has_image = 'true';
			}

			if($("#sbb_redirect_to", this.$el).val() == 'modal' || $("#sbb_resource_type", this.$el).val() == 'collection'){
				has_modal = 'true';
			}

			var attrs = {
				shop: $("#sbb_shop_domain", this.$el).val(),
				embed_type: $("#sbb_resource_type", this.$el).val(),
				handle: $("#sbb_resource_handle", this.$el).val(),
				has_image: has_image,
				has_modal: has_modal,
				redirect_to: $("#sbb_redirect_to", this.$el).val()
			};

			if ( document.getElementById('sbb_buy_button_text').value ) 
				attrs.buy_button_text = document.getElementById('sbb_buy_button_text').value;

			if ( document.getElementById('sbb_button_background_color').value ) 
				attrs.button_background_color = document.getElementById('sbb_button_background_color').value.replace('#', '');

			if ( document.getElementById('sbb_button_text_color').value ) 
				attrs.button_text_color = document.getElementById('sbb_button_text_color').value.replace('#', '');

			if ( document.getElementById('sbb_background_color').value ) 
				attrs.background_color = document.getElementById('sbb_background_color').value.replace('#', '');

			var shortcode = new wp.shortcode({
				tag: this.shortcode,
				attrs: attrs,
				type: 'single'
			});
			return shortcode.string();
		},
		initJS: function(){
			$('.themify_sbb_ColorSelectInput', this.$el).minicolors();
			//$('#sbb_button_background_color').minicolors('value', this.model.get('buttonColor') );
			//$('#sbb_button_text_color').minicolors('value', this.model.get('buttonText') );
			//$('#sbb_background_color').minicolors('value', this.model.get('background') );
		},
		insertToEditor: function( html ){
			var editor, wpActiveEditor,
				hasTinymce = ! _.isUndefined( window.tinymce ),
				hasQuicktags = ! _.isUndefined( window.QTags );

			if ( this.activeEditor ) {
				wpActiveEditor = window.wpActiveEditor = this.activeEditor;
			} else {
				wpActiveEditor = window.wpActiveEditor;
			}

			if ( ! wpActiveEditor ) {
				if ( hasTinymce && tinymce.activeEditor ) {
					editor = tinymce.activeEditor;
					wpActiveEditor = window.wpActiveEditor = editor.id;
				} else if ( ! hasQuicktags ) {
					return false;
				}
			} else if ( hasTinymce ) {
				editor = tinymce.get( wpActiveEditor );
			}

			if ( editor && ! editor.isHidden() ) {
				editor.execCommand( 'mceInsertContent', false, html );
			} else if ( hasQuicktags ) {
				QTags.insertContent( html );
			} else {
				document.getElementById( wpActiveEditor ).value += html;
			}
		},
		resetForm: function() {
			$('.shopify-shortcode-generator').hide();
			$('.shopify-picker-iframe').show();
		}
	});

	var modalContentView = new ModalContentView();
	modal.on('attach', function(){
		var tmpl = wp.template( 'shopify-buybuttonwp-modal-title');
		modal.$el.addClass('themify_sbb_modal_container')
		.find('.media-modal').prepend( tmpl() );
		modal.content( modalContentView );
		modalContentView.initJS();
	});

	$(function() {
		// When the user clicks a button, open a modal.
		$('body').on('click', '.js--open-shopify-buy-btn', function( event ) {
			event.preventDefault();
			modalContentView.activeEditor = $(this).data('editor');
			modal.open();
		});

		// Minicolors initialization on plugin option page.
		$('.shopify-buybuttonwp-minicolors').each( function(){
			$(this).minicolors({ 
				theme: 'shopify-buybuttonwp',
				change: function(value, opacity) {
					this.val( value.replace('#', '') );
				},
				hide: function( value, opacity ) {
					this.val( this.val().replace('#', '') );
				}
			}).val( $(this).val().replace('#', '') );
		});
	});

})( jQuery );