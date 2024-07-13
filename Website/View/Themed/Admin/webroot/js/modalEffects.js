/**
 * modalEffects.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */

var ModalEffects = (function() {

    function init() {


        var overlay = document.querySelector('.md-overlay');
       

       $.each(document.querySelectorAll('.md-trigger'), function(i, el) {
           var modal = document.querySelector('#' + el.getAttribute('data-modal')),
                close = modal.querySelector('.md-close');

            function removeModal(hasPerspective) {
                classie.remove(modal, 'md-show');

                if (hasPerspective) {
                    classie.remove(document.documentElement, 'md-perspective');
                }
            }

            function removeModalHandler() {
                removeModal(classie.has(el, 'md-setperspective'));
            }

            if (document.addEventListener) {
                el.addEventListener('click', function(ev) {
                    classie.add(modal, 'md-show');
                    overlay.removeEventListener('click', removeModalHandler);
                    overlay.addEventListener('click', removeModalHandler);

                    if (classie.has(el, 'md-setperspective')) {
                        setTimeout(function() {
                            classie.add(document.documentElement, 'md-perspective');
                        }, 25);
                    }
                });

                close.addEventListener('click', function(ev) {
                    ev.stopPropagation();
                    removeModalHandler();
                });
            } else {
                el.attachEvent('onclick', function (ev) {
                    classie.add(modal, 'md-show');
                    overlay.detachEvent('onclick', removeModalHandler);
                    overlay.attachEvent('onclick', removeModalHandler);

                    if (classie.has(el, 'md-setperspective')) {
                        setTimeout(function () {
                            classie.add(document.documentElement, 'md-perspective');
                        }, 25);
                    }
                });

                close.attachEvent('onclick', function (ev) {
                    //ev.stopPropagation();
                    removeModalHandler();
                });
            }


        });
    };


	init();

})();