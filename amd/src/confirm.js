define(['jquery', 'core/modal_factory'], function($, ModalFactory) {
  var trigger = $('a.tool_richardnz_deletelink');
  ModalFactory.create({
    title: 'Confirm delete',
    body: '<p>Do you really want to delete this task?</p>',
    type: ModalFactory.types.SAVE_CANCEL,
  }, trigger)
  .done(function(modal) {
    // Do what you want with your new modal.

  });
});