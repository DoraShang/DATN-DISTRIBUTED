<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>CKFinder</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<script type="text/javascript" src="ckfinder.js"></script>
	<style type="text/css">
		body, html, iframe, #ckfinder {
			margin: 0;
			padding: 0;
			border: 0;
			width: 100%;
			height: 100%;
			overflow: hidden;
		}
	</style>
</head>
<body class="CKFinderFrameWindow">
	<div id="ckfinder"></div>
	<script type="text/javascript">



//<![CDATA[
(function()
{
		var config = {};
		var get = CKFinder.tools.getUrlParam;
		var getBool = function( v )
		{
			var t = get( v );

			if ( t === null )
				return null;

			return t == '0' ? false : true;
		};

		var tmp;
		if ( tmp = get( 'configId' ) )
		{
			var win = window.opener || window;
			try
			{
				while ( ( !win.CKFinder || !win.CKFinder._.instanceConfig[ tmp ] ) && win != window.top )
					win = win.parent;

				if ( win.CKFinder._.instanceConfig[ tmp ] )
					config = CKFINDER.tools.extend( {}, win.CKFinder._.instanceConfig[ tmp ] );
			}
			catch(e) {}
		}

		if ( tmp = get( 'basePath' ) )
			CKFINDER.basePath = tmp;

		if ( tmp = get( 'startupPath' ) || get( 'start' ) )
			config.startupPath = decodeURIComponent( tmp );

		config.id = get( 'id' ) || '';

		if ( ( tmp = getBool( 'rlf' ) ) !== null )
			config.rememberLastFolder = tmp;

		if ( ( tmp = getBool( 'dts' ) ) !== null )
			config.disableThumbnailSelection = tmp;

		if ( tmp = get( 'data' ) )
			config.selectActionData = tmp;

		if ( tmp = get( 'tdata' ) )
			config.selectThumbnailActionData = tmp;

		if ( tmp = get( 'type' ) )
			config.resourceType = tmp;

		if ( tmp = get( 'skin' ) )
			config.skin = tmp;

		if ( tmp = get( 'langCode' ) )
			config.language = tmp;

			config.selectMultiple = true

		if ( typeof( config.selectActionFunction ) == 'undefined' )
		{
			// Try to get desired "File Select" action from the URL.
			var action;
			if ( tmp = get( 'CKEditor' ) )
			{
				if ( tmp.length )
					action = 'ckeditor';
			}
			if ( !action )
				action = get( 'action' );

			var parentWindow = ( window.parent == window ) ? window.opener : window.parent;
			switch ( action )
			{
				case 'js':
					var actionFunction = get( 'func' );
					if ( actionFunction && actionFunction.length > 0 )
						config.selectActionFunction = parentWindow[ actionFunction ];

					actionFunction = get( 'thumbFunc' );
					if ( actionFunction && actionFunction.length > 0 )
						config.selectThumbnailActionFunction = parentWindow[ actionFunction ];
					break ;

				case 'ckeditor':
					var funcNum = get( 'CKEditorFuncNum' );
					if ( parentWindow['CKEDITOR'] )
					{
						config.selectActionFunction = function( fileUrl, data, allFiles )
						{
							// parentWindow.alert_abc();

							// parentWindow['alert_abc'];

							// msg = 'abc';
							if ( allFiles.length > 1 )
							{
								var files = new Array();
								// msg = '';
								// msg += '<br /><br /><b>Selected files:</b><br /><br />';
								// msg += '<ul style="padding-left:20px">';
								for ( var i = 0 ; i < allFiles.length ; i++ )
								{
									// See also allFiles[i].url
									// msg += '<li>' + allFiles[i].data['fileUrl'] + ' (' + allFiles[i].data['fileSize'] + 'KB)</li>';

									files.push(allFiles[i].data['fileUrl']);
								}

								var filestr = files.join(',');
								
								parentWindow.insert_images(filestr);

								parentWindow['CKEDITOR'].tools.callFunction( funcNum, fileUrl, data );
							}
							else
							{
								parentWindow['CKEDITOR'].tools.callFunction( funcNum, fileUrl, data );
							}
						};

						config.selectThumbnailActionFunction = config.selectActionFunction;
					}
					break;

				default:
					if ( parentWindow && parentWindow['FCK'] && parentWindow['SetUrl'] )
					{
						action = 'fckeditor' ;

						config.selectActionFunction = parentWindow['SetUrl'];

						if ( !config.disableThumbnailSelection )
							config.selectThumbnailActionFunction = parentWindow['SetUrl'];
					}
					else
						action = null ;
			}
			config.action = action;
		}


		function showFileInfo( fileUrl, data, allFiles )

		{
			var msg = 'The last selected file is: <a href="' + fileUrl + '">' + fileUrl + '</a><br /><br />';
				// Display additional information available in the "data" object.
				// For example, the size of a file (in KB) is available in the data["fileSize"] variable.
				if ( fileUrl != data['fileUrl'] )
					msg += '<b>File url:</b> ' + data['fileUrl'] + '<br />';
				msg += '<b>File size:</b> ' + data['fileSize'] + 'KB<br />';
				msg += '<b>Last modified:</b> ' + data['fileDate'];

				if ( allFiles.length > 1 )
				{
					msg += '<br /><br /><b>Selected files:</b><br /><br />';
					msg += '<ul style="padding-left:20px">';
					for ( var i = 0 ; i < allFiles.length ; i++ )
					{
						// See also allFiles[i].url
						msg += '<li>' + allFiles[i].data['fileUrl'] + ' (' + allFiles[i].data['fileSize'] + 'KB)</li>';
					}
					msg += '</ul>';
				}
				// this = CKFinderAPI object
				this.openMsgDialog( "Selected file", msg );
		}

		// Always use 100% width and height when nested using this middle page.
		config.width = config.height = '100%';
		// config.selectActionFunction = 'showFileInfo';

		var ckfinder = new CKFinder( config );
		ckfinder.replace( 'ckfinder', config );

        // ckfinder.on( 'files:choose', function( evt ) {
        //     var files = evt.data.files;

        //     var chosenFiles = '';

        //     files.forEach( function( file, i ) {
        //         chosenFiles += ( i + 1 ) + '. ' + file.get( 'name' ) + '\n';
        //     } );

        //     alert( chosenFiles );
        // } );

        // ckfinder.on( 'file:choose:resizedImage', function( evt ) {
        //     alert( evt.data.resizedUrl );
        //     // Call the code below if you would like to close the "Choose Resized" dialog window.
        //     ckfinder.request( 'dialog:destroy' );
        // } );

})();
//]]>

	</script>
</body>
</html>
