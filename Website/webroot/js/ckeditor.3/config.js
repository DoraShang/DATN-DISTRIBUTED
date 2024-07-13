/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.skin = 'moono_blue';
	config.extraPlugins = 'youtube,lineheight,richcombo';


	 CKEDITOR.config.selectMultiple = true;
	 CKEDITOR.config.entities = false;
// CKEDITOR.config.basicEntities = false;

// CKEDITOR.config.entities_greek = false;
CKEDITOR.config.entities_latin = false;

// CKEDITOR.config.entities_additional = '';

// CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;

CKEDITOR.config.htmlEncodeOutput = false; 
config.protectedSource.push( /<tex[\s\S]*?\/tex>/g );
};
