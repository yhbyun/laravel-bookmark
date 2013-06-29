var SignupView = Backbone.View.extend({

  events: {
    "click .save":            "save",
    "submit form":            "save"
  },

  initialize: function() {
    _.bindAll(this, 'render', 'unrender', 'save');

    Backbone.Validation.bind(this);
  },

  render: function() {
    var source = Templates.signup;
    var template = Handlebars.compile(source);
    var html = template(this.model.attributes);
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

  save: function(e) {
    e.preventDefault();

    var username = this.$('input[name=username]').val();
    var password = this.$('input[name=password]').val();
    var email = this.$('input[name=email]').val();

    var self = this;
    this.$('.alert').hide();
    this.$('button[type=submit]').attr('disabled', 'disabled');

    if (this.model.set({ username: username, password: password, email: email }, {validate:true})) {
      $.ajax({
        type: 'POST',
        url: './api/v1/user',
        dataType: 'json',
        data: { username: username, password: password, email: email },
        success: function(data) {
          self.$('button[type=submit]').removeAttr('disabled');
          if (typeof data.error !== 'undefined') {
            self.$('.alert-error').text(data.error).show();
          } else {
            self.unrender();

            $('#header .public').hide();
            $('#header .logged-in').show();

            App.user = data;
            App.router.navigate("bookmarks", true);
          }
        },
        error: function() {
          self.$('button[type=submit]').removeAttr('disabled');
          self.$('.alert-error').text(data.error).show();
        }
      });
    } else {
      this.$('.alert-error').fadeIn();
    }
  }

});

