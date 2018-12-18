define(['jquery', 'core/log'], function($, log) {
    "use strict";
    log.debug('alert js module loaded');
    return {
        init: function() {
            log.debug('alert js init');
            $('a.tool_richardnz_deletelink').click( function() {
                alert('alert js deleting now');
            });
        }
    };
});