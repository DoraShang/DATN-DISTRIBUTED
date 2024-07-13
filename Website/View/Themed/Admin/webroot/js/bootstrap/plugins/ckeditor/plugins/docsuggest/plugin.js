(function () {
    CKEDITOR.plugins.add('docsuggest', {
        init: function (editor) {

            if (typeof ($.Autocomplete)=="undefined") {
                console.log("Chưa khai báo biến autocomplete");
                return;
            }

            editor.addCommand('mkDocsuggest', new CKEDITOR.dialogCommand('docsuggestDialogue', {
                allowedContent: 'iframe[!width,!height,!src,!frameborder,!allowfullscreen]; object param[*]'
            }));

           
            CKEDITOR.dialog.add('docsuggestDialogue', function (instance) {
                var video;

                return {
                    title: "Gắn link nội dung",
                    minWidth: 500,
                    minHeight: 200,
                    contents:
                    [
                        {
                            id: 'mkDocSuggestPlugin',
                            className: 'docSuggest',
                            expand: true,
                            elements:
                            [
                                {
                                    type: 'hbox',
                                    align: 'right',
                                    children: [
                                        {
                                            type: 'select',
                                            id: 'searchType',
                                            label: 'Tìm trong website',
                                            items: [['Bài viết', 'inarticle'], ['Danh mục', 'incategory']],
                                            'default': 'inarticle',
                                            onChange: function (x) {
                                                var value = this.getValue();

                                                var url = '';
                                                if (value == 'incategory') {
                                                    url = '/admin/category/SearchLink';
                                                }
                                                else {
                                                    url = '/admin/article/SearchLink';
                                                }

                                                $(".mktext").autocomplete({
                                                    serviceUrl: url,
                                                    noCache: true,
                                                    onSelect: function (suggestion) {
                                                        var me = CKEDITOR.dialog.getCurrent().getContentElement("mkDocSuggestPlugin", 'mkOkLink');
                                                        me.setValue(suggestion.data);
                                                    }
                                                });
                                            }
                                        },
                                        {
                                            type: 'select',
                                            id: 'sport',
                                            label: 'Kiểu mở liên kết',
                                            items: [['Trang hiện tại', '_seft'], ['Tab mới', '_blank'], ['Mở dạng popup', '_popup']],
                                            'default': '_blank'
                                        }
                                    ]
                                },
                                {
                                    type: 'html',
                                    autofocus: 'autofocus',
                                    title: "Tìm bài viết",
                                    html: '<div>Tìm bài viết</div><div class="cke_dialog_ui_input_text"><input class="mktext cke_dialog_ui_input_text" type="text" placeholder="Nhập tiêu đề tìm kiếm"/></div>'
                                },
                                {
                                    type: 'text',
                                    id: 'mkOkLink',
                                    label: 'Link hiển thị',
                                }
                            ]
                        }
                    ],
                    onShow: function (a) {
                        var editor = this.getParentEditor(),
                            selection = editor.getSelection(),
                            element = null;

                        // Fill in all the relevant fields if there's already one link selected.
                        if ((element = getSelectedLink(editor)) && element.hasAttribute('href')) {
                            // Don't change selection if some element is already selected.
                            // For example - don't destroy fake selection.
                            if (!selection.getSelectedElement())
                                selection.selectElement(element);
                        } else {
                            element = null;
                        }
                        
                        var selectedText = selection.getSelectedText();

                        // Record down the selected element in the dialog.
                        this._.selectedElement = element;

                        this.setupContent(element);
                    

                        $(".mktext").autocomplete({
                            serviceUrl: '/admin/article/SearchLink',
                            noCache: true,
                            onSelect: function (suggestion) {
                                var me = CKEDITOR.dialog.getCurrent().getContentElement("mkDocSuggestPlugin", 'mkOkLink');
                                me.setValue(suggestion.data);
                            }
                        });

                        $(".mktext").val(selectedText);
                        $(".mktext").trigger("blur");
                    },
                    onOk: function() {
                        var data = {};
                        data.sl = CKEDITOR.dialog.getCurrent().getContentElement("mkDocSuggestPlugin", 'sport').getValue();
                        data.okLink = CKEDITOR.dialog.getCurrent().getContentElement("mkDocSuggestPlugin", 'mkOkLink').getValue();
                        // Collect data from fields.
                        this.commitContent(data);

                        var selection = editor.getSelection(),
                            attributes = getLinkAttributes(editor, data);
                        
                        if (!this._.selectedElement) {
                            var range = selection.getRanges()[0];

                            // Use link URL as text with a collapsed cursor.
                            if (range.collapsed) {
                                // Short mailto link text view (#5736).
                                var text = new CKEDITOR.dom.text(data.type == 'email' ?
                                    data.email.address : attributes.set['data-cke-saved-href'], editor.document);
                                range.insertNode(text);
                                range.selectNodeContents(text);
                            }

                            // Apply style.
                            var style = new CKEDITOR.style({
                                element: 'a',
                                attributes: attributes.set
                            });

                            style.type = CKEDITOR.STYLE_INLINE; // need to override... dunno why.
                            style.applyToRange(range, editor);
                            range.select();
                        } else {
                            // We're only editing an existing link, so just overwrite the attributes.
                            var element = this._.selectedElement,
                                href = element.data('cke-saved-href'),
                                textView = element.getHtml();

                            element.setAttributes(attributes.set);
                            element.removeAttributes(attributes.removed);

                            // Update text view when user changes protocol (#4612).
                            if (href == textView || data.type == 'email' && textView.indexOf('@') != -1) {
                                // Short mailto link text view (#5736).
                                element.setHtml(data.type == 'email' ?
                                    data.email.address : attributes.set['data-cke-saved-href']);

                                // We changed the content, so need to select it again.
                                selection.selectElement(element);
                            }

                            delete this._.selectedElement;
                        }
                    }
                };

            });

            // Add buttons for link and unlink.
            if (editor.ui.addButton) {
                editor.ui.addButton('mkDocsuggestButton', {
                    label: 'Liên kết bài viết',
                    command: 'mkDocsuggest',
                    icon: this.path + 'link.png'
                });
            }
        },
        afterInit: function (editor) {
            if (typeof ($.Autocomplete) == "undefined") {
                return true;
            }

            editor.on('key', function (evt) {
                if (evt.data.keyCode == 1114181) {
                     editor.execCommand('mkDocsuggest');
                }
            });

            if (editor.contextMenu) {
                editor.addMenuGroup('linkGroup');
                editor.addMenuItem('linkItem', {
                    label: 'Tạo liên kết (Link)',
                    icon: this.path + 'icons.png',
                    command: 'mkDocsuggest',
                    group: 'linkGroup'
                });

                editor.contextMenu.addListener(function (element) {
                    return { linkItem: CKEDITOR.TRISTATE_OFF };
                });
            }
        }
    });

    /**
     * Get the surrounding link element of current selection.
     *
     * The following selection will all return the link element.
     *
     * @example
     *  <a href="#">li^nk</a>
     *  <a href="#">[link]</a>
     *  text[<a href="#">link]</a>
     *  <a href="#">li[nk</a>]
     *  [<b><a href="#">li]nk</a></b>]
     *  [<a href="#"><b>li]nk</b></a>
     *
     * @param {CKEDITOR.editor} editor
     *   The CKEditor editor object
     *
     * @return {?HTMLElement}
     *   The selected link element, or null.
     *
     */
    function getSelectedLink(editor) {
        var selection = editor.getSelection();
        var selectedElement = selection.getSelectedElement();

        if (selectedElement && selectedElement.is('a')) {
            return selectedElement;
        }
        var range = selection.getRanges(true)[0];

        if (range) {
            range.shrink(CKEDITOR.SHRINK_TEXT);
            return editor.elementPath(range.getCommonAncestor()).contains('a', 1);
        }

        return null;
    }

    function getLinkAttributes( editor, data ) {
        var emailProtection = editor.config.emailProtection || '', set = {};

        // Compose the URL.
        switch ( data.sl ) {
            case '_blank':
                set['data-cke-saved-href'] = data.okLink;
                set['data-cke-pa-target'] = data.sl;
                break;
            case '_self':
                set['data-cke-saved-href'] = data.okLink;
                break;
            case 'email':
                var email = data.email,
                    address = email.address,
                    linkHref;

                switch ( emailProtection ) {
                    case '':
                    case 'encode':
                        var subject = encodeURIComponent( email.subject || '' ),
                            body = encodeURIComponent( email.body || '' ),
                            argList = [];

                        // Build the e-mail parameters first.
                        subject && argList.push( 'subject=' + subject );
                        body && argList.push( 'body=' + body );
                        argList = argList.length ? '?' + argList.join( '&' ) : '';

                        if ( emailProtection == 'encode' ) {
                            linkHref = [
                                'javascript:void(location.href=\'mailto:\'+', // jshint ignore:line
                                protectEmailAddressAsEncodedString( address )
                            ];
                            // parameters are optional.
                            argList && linkHref.push( '+\'', escapeSingleQuote( argList ), '\'' );

                            linkHref.push( ')' );
                        } else {
                            linkHref = [ 'mailto:', address, argList ];
                        }

                        break;
                    default:
                        // Separating name and domain.
                        var nameAndDomain = address.split( '@', 2 );
                        email.name = nameAndDomain[ 0 ];
                        email.domain = nameAndDomain[ 1 ];

                        linkHref = [ 'javascript:', protectEmailLinkAsFunction( editor, email ) ]; // jshint ignore:line
                }

                set[ 'data-cke-saved-href' ] = linkHref.join( '' );
                break;
        }

        // Popups and target.
        if ( data.target ) {
            if ( data.target.type == 'popup' ) {
                var onclickList = [
                        'window.open(this.href, \'', data.target.name || '', '\', \''
                ],
                    featureList = [
                        'resizable', 'status', 'location', 'toolbar', 'menubar', 'fullscreen', 'scrollbars', 'dependent'
                    ],
                    featureLength = featureList.length,
                    addFeature = function( featureName ) {
                        if ( data.target[ featureName ] )
                            featureList.push( featureName + '=' + data.target[ featureName ] );
                    };

                for ( var i = 0; i < featureLength; i++ )
                    featureList[ i ] = featureList[ i ] + ( data.target[ featureList[ i ] ] ? '=yes' : '=no' );

                addFeature( 'width' );
                addFeature( 'left' );
                addFeature( 'height' );
                addFeature( 'top' );

                onclickList.push( featureList.join( ',' ), '\'); return false;' );
                set[ 'data-cke-pa-onclick' ] = onclickList.join( '' );
            }
            else if ( data.target.type != 'notSet' && data.target.name ) {
                set.target = data.target.name;
            }
        }

        // Advanced attributes.
        if ( data.advanced ) {
            for ( var a in advAttrNames ) {
                var val = data.advanced[ advAttrNames[ a ] ];

                if ( val )
                    set[ a ] = val;
            }

            if ( set.name )
                set[ 'data-cke-saved-name' ] = set.name;
        }

        // Browser need the "href" fro copy/paste link to work. (#6641)
        if ( set[ 'data-cke-saved-href' ] )
            set.href = set[ 'data-cke-saved-href' ];

        var removed = {
            target: 1,
            onclick: 1,
            'data-cke-pa-onclick': 1,
            'data-cke-saved-name': 1
        };

        if ( data.advanced )
            CKEDITOR.tools.extend( removed, advAttrNames );

        // Remove all attributes which are not currently set.
        for ( var s in set )
            delete removed[ s ];

        return {
            set: set,
            removed: CKEDITOR.tools.objectKeys( removed )
        };
    }
})();