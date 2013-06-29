var ResetpasswordView = Backbone.View.extend({

  events: {
    "submit #frm-password":      "reset"
  },

  initialize: function() {
    _.bindAll(this, 'render', 'reset', 'unrender');
  },

  render: function() {
    var source = Templates.resetpassword;
    var template = Handlebars.compile(source);
    var html = template(App);
    $(this.el).html(html);

    $(this.el).css('margin', '100px auto 15px auto').css('width', '470px');
    $('#app').append(this.el);

  },

  unrender: function() {
  },

  reset: function(e) {

    e.preventDefault();

    var token = this.$('input[name=token]').val();
    var email = this.$('input[name=email]').val();
    var password = this.$('input[name=password]').val();
    var password_confirmation = this.$('input[name=password_confirmation]').val();

    this.$('input[type=submit]').attr('disabled', 'disabled');

    var self = this;
    $.ajax({
      type: 'POST',
      url: './api/v1/password/reset',
      dataType: 'json',
      data: { token: token, password: password, password_confirmation: password_confirmation, email: email },
      success: function(data) {
        if (data.status && data.status === 'OK') {
          self.$('input[type=submit]').removeAttr('disabled');
          self.$('.account_info').html('Changes saved');
          setTimeout(function() {
            self.$('.account_info').html('');
          }, 3000);
        } else {
          self.$('input[type=submit]').removeAttr('disabled');
          self.$('.account_info').html(data.error);
          setTimeout(function() {
            self.$('.account_info').html('');
          }, 3000);
        }
      },
      error: function() {
        window.location = '/bookmark';
      }
    });

  }

});
