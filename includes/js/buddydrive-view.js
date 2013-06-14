function buddydriveStripLast() {
	if( jQuery('#buddydrive-dir tbody').find('.buddydrive-more').length )
		jQuery('#buddydrive-dir tbody').find('.buddydrive-more').prev().remove();
}

function openFolder( srcstring ) {
	var folder_id = srcstring.replace('?folder-', '');
	var buddyscope = false;

	if( jQuery('.buddydrive-type-tabs li.current a').length )
		buddyscope = jQuery('.buddydrive-type-tabs li.current a').attr('id');

	folder_id = Number(folder_id) + 0;

	if( !isNaN( folder_id ) ) {
		var data = {
      		action:'buddydrive_openfolder',
	  		folder: folder_id,
			foldername:1,
			scope:buddyscope
    	};

    	jQuery('#buddydrive-dir tbody').html('<tr><td colspan="5"><p class="buddydrive-opening-dir"><a class="loading">'+buddydrive_view.loading+'</a></p></td></tr>');

		jQuery.post(ajaxurl, data, function(response) {
			
			jQuery('#buddy-new-folder').hide();

			jQuery('.buddytree').each(function(){
				jQuery(this).removeClass('current');
			});
			
			if( response.length > 1)
				jQuery('.buddydrive-crumbs').append( ' / <span id="folder-'+folder_id+'" class="buddytree current"><input type="hidden" id="buddydrive-open-folder" value="'+folder_id+'">'+response[1]+'</span>' );
			
			jQuery('#buddydrive-dir tbody').html('');
	        jQuery("#buddydrive-dir tbody").prepend(response[0]);
			
	    }, 'json' );
	}
}

jQuery(document).ready(function($){
	$.cookie( 'buddydrive-oldestpage', 1, {path: '/'} );

	if ( '-1' != window.location.search.indexOf('folder-') )
		openFolder( window.location.search );
	
	$('#buddydrive-dir').on('click', '.buddydrive-load-more a', function(){
		var currentfolder = group_id = 0;
		
		$('.buddytree').each(function(){
			if( $(this).hasClass('current') )
				currentfolder = $(this).attr('id').replace('folder-', '');
		});
		
		var buddyscope = 'groups';

		if( $('.buddydrive-type-tabs li.current a').length )
			buddyscope = $('.buddydrive-type-tabs li.current a').attr('id');

		if( buddyscope == 'groups' && $('#buddydrive-home').attr('data-group') )
			group_id = $('#buddydrive-home').attr('data-group');
		
		var loadmore_tr = $(this).parent().parent();
		
		$(this).addClass('loading');
		
		if ( null == $.cookie('buddydrive-oldestpage') )
	        $.cookie('buddydrive-oldestpage', 1, {path: '/'} );

	    var oldest_page = ( $.cookie('buddydrive-oldestpage') * 1 ) + 1;
		
		var data = {
	      action:'buddydrive_loadmore',
	      page: oldest_page,
		  folder:currentfolder,
		  scope:buddyscope,
		  group:group_id
	    };

	    $.post(ajaxurl, data, function(response) {
	        $.cookie( 'buddydrive-oldestpage', oldest_page, {path: '/'} );
	        $("#buddydrive-dir tbody").append(response);
			loadmore_tr.hide();
	    });
		
		return false;
	});
	
	$('#buddydrive-dir').on('click', '.buddyfolder', function(){
		var buddyscope = false;
		
		$.cookie( 'buddydrive-oldestpage', 1, {path: '/'} );

		if( $('.buddydrive-type-tabs li.current a').length )
			buddyscope = $('.buddydrive-type-tabs li.current a').attr('id');
		
		parent_id = $(this).attr('data-folder');
		$('#buddy-new-folder').hide();

		$('.buddytree').each(function(){
			$(this).removeClass('current');
		});
		
		$('.buddydrive-crumbs').append( ' / <span id="folder-'+parent_id+'" class="buddytree current"><input type="hidden" id="buddydrive-open-folder" value="'+parent_id+'">'+$(this).html()+'</span>' );
		
		var data = {
	      action:'buddydrive_openfolder',
		  folder: parent_id,
		  scope:buddyscope
	    };

	    $('#buddydrive-dir tbody').html('<tr><td colspan="5"><p class="buddydrive-opening-dir"><a class="loading">'+buddydrive_view.loading+'</a></p></td></tr>');
	
		$.post(ajaxurl, data, function(response) {
			$('#buddydrive-dir tbody').html('');
	        $("#buddydrive-dir tbody").prepend(response[0]);
	    }, 'json' );
		
		return false;
		
	});

	$('#buddydrive-dir').on('click', '.buddydrive-row-actions a', function(){
		if( $(this).hasClass('buddydrive-private-message') )
			return true;

		if( $(this).hasClass('buddydrive-group-activity') ) {

			if( $(this).hasClass('loading') )
				return false;

			target = $(this).parent().parent().parent().find('a').first().attr('data-file');
			
			if( !target )
				target = $(this).parent().parent().parent().find('a').first().attr('data-folder');
			
			link = $(this).parent().parent().parent().find('a').first().attr('href');
			var shared = $(this);
			$(this).addClass('loading');
			
			var data = {
		      action:'buddydrive_groupupdate',
			  itemid: target,
			  url:link,
			  '_wpnonce_buddydrive_actions': $("input#_wpnonce_buddydrive_actions").val()
		    };
		
			$.post(ajaxurl, data, function(response) {
				if( response == 1 ) {
					shared.html( buddydrive_view.shared );
					shared.css('color', 'green');
				}
				shared.removeClass('loading');
		    });
		}

		if( $(this).hasClass('buddydrive-remove-group') ) {
			if( $(this).hasClass('loading') )
				return false;
			
			target = $(this).parent().parent().parent().find('a').first().attr('data-file');
			
			if( !target )
				target = $(this).parent().parent().parent().find('a').first().attr('data-folder');

			group = $(this).attr('data-group');

			$(this).addClass('loading');
			
			var data = {
		      action:'buddydrive_removefromgroup',
			  itemid: target,
			  groupid: group,
			  '_wpnonce_buddydrive_actions': $("input#_wpnonce_buddydrive_actions").val()
		    };
		
			$.post(ajaxurl, data, function(response) {
				if( response == 1 ) {
					$('tr#item-'+target).remove();
				} else {
					alert( buddydrive_view.group_remove_error );
				}
		    });


			return false;
		}
		
		var show = $(this).attr('class').replace('buddydrive-show-', ''); 
		var desc = $(this).parent().parent().parent().find('.buddydrive-ra-'+show);

		$(this).parent().parent().parent().parent().parent().find('.ba').each(function(){
			if( $(this).get(0) != desc.get(0) )
				$(this).addClass('hide');
		});

		if( desc.hasClass('hide') )
			desc.removeClass('hide');
		else
			desc.addClass('hide');

		if( show == 'link' )
			desc.find('input').focus();

		return false;
	});

	$.fn.selectRange = function(start, end) {
	    return this.each(function() {
	        if(this.setSelectionRange) {
	            this.focus();
	            this.setSelectionRange(start, end);
	        } else if(this.createTextRange) {
	            var range = this.createTextRange();
	            range.collapse(true);
	            range.moveEnd('character', end);
	            range.moveStart('character', start);
	            range.select();
	        }
	 });
	};

	$('#buddydrive-dir').on('focus', '.buddydrive-file-input', function() {
		$(this).selectRange( 0, $(this).val().length );
		return false;
	});
	
});