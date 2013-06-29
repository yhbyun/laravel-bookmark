var SigninView = Backbone.View.extend({

  events: {
    "click .signin":          "signin",
    "click .btn-forgot":      "reset"
  },

  initialize: function() {
    _.bindAll(this, 'render', 'unrender', 'signin');
  },

  render: function() {
    var source = Templates.signin;
    var template = Handlebars.compile(source);
    var html = template();
    $(this.el).html(html);

    $('body').append(this.el);

    $(this.el).modal({
      backdrop: true,
      keyboard: false,
      show: true
    });
  },

  unrender: function() {
    $(this.el).modal('hide');
    $(this.el).remove();
  },

  signin: function(e) {
    e.preventDefault();

    var username = this.$('input[name=username]').val();
    var password = this.$('input[name=password]').val();

    var self = this;
    this.$('.alert').hide();
    this.$('button[type=submit]').attr('disabled', 'disabled');

    $.ajax({
      type: 'POST',
      url: './api/v1/login',
      dataType: 'json',
      data: { username: username, password: password },
      success: function(data) {
        self.unrender();

        $('#header .public').hide();
        $('#header .logged-in').show();
        App.user = data;
        App.router.navigate("bookmarks", true);
      },
      error: function() {
        self.$('button[type=submit]').removeAttr('disabled');
        self.$('.alert-error').text('That username & password was not found').show();
      }
    });

  },

  reset: function(e) {
    e.preventDefault();
    this.unrender();

    new ResetView().render();
  }

});


