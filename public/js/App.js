var App = {

  initialize: function() {
    //If Backbone sync gets an unauthorized header, it means the user's
    //session has expired, so send them back to the homepage
    var sync = Backbone.sync;
    Backbone.sync = function(method, model, options) {
      options.error = function(xhr, ajaxOptions, thrownError) {
        if (xhr.status == 401) {
          alert('401 error');
          window.location = '/bookmark';
        }
      }
      sync(method, model, options);
    };

    this.router = new BookmarklyRouter();
    Backbone.history.start({pushState: true, hashChange: false, root: '/bookmark/'});
  }

};


