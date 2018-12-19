define(['jquery', 'core/log', 'core/ajax'], function($, log) {
    "use strict";
    log.debug('alert js module loaded');
    return {
        init: function(return_url) {
            log.debug('alert js init');
            $('a.tool_richardnz_deletelink').click(function(e) {
                log.debug('alert link clicked');
                if(!confirm('Are you sure you want to delete?')) {
                    e.preventDefault();
                    log.debug('alert link cancelled');
                    $.ajax({
                        url:return_url,
                    });
                } else {
                    log.debug('alert link confirmed');
                }
            });
        }
    };
});