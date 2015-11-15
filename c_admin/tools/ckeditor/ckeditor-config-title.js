/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

/**
 * This file was added automatically by CKEditor builder.
 * You may re-use it at any time to build CKEditor again.
 *
 * If you would like to build CKEditor online again
 * (for example to upgrade), visit one the following links:
 *
 * (1) http://ckeditor.com/builder
 *     Visit online builder to build CKEditor from scratch.
 *
 * (2) http://ckeditor.com/builder/3f26deee77986f874ea2cf6faaafc16d
 *     Visit online builder to build CKEditor, starting with the same setup as before.
 *
 * (3) http://ckeditor.com/builder/download/3f26deee77986f874ea2cf6faaafc16d
 *     Straight download link to the latest version of CKEditor (Optimized) with the same setup as before.
 *
 * NOTE:
 *    This file is not used by CKEditor, you may remove it.
 *    Changing this file will not change your CKEditor configuration.
 */

/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {

	//config.height = '60px';
	//config.autoGrow_onStartup = false,
	//config.resize_enabled = false;
	//config.resize_maxHeight = 60;

	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'paragraph',   groups: [ 'align', 'bidi' ] }
	];
};
