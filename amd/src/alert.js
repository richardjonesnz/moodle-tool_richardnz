define(['jquery', 'core/log'], function($, log) {
    "use strict";
    log.debug('alert js module loaded');
    return {
        init: function() {
              $('.tool_richardnz_deletelink').click(function() {
                  log.debug('deleting now');
              });
        }
   }
});