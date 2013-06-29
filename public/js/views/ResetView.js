var ResetView = Backbone.View.extend({

  events: {
    "click .reset":          "reset"
  },

  initialize: function() {
    _.bindAll(this, 'render', 'unrender', 'reset');
  },

  render: function() {
    var source = Templates.reset;
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

  reset: function(e) {
    e.preventDefault();

    var email = this.$('input[name=email]').val();

    var self = this;
    this.$('.alert').hide();
    this.$('button[type=submit]').attr('disabled', 'disabled');

    $.ajax({
      type: 'POST',
      url: './api/v1/password/remind',
      dataType: 'json',
      data: { email: email },
      success: function(data) {
        self.$('button[type=submit]').removeAttr('disabled');

        if (data.status && data.status === 'OK') {
          self.$('.alert-success').text('An e-mail with the password reset has been sent.').show();
          self.$('input[name=email]').hide();
          self.$('button[type=submit]').hide();
        } else {
          self.$('.alert-error').text(data.error).show();
        }
      },
      error: function() {
        self.$('button[type=submit]').removeAttr('disabled');
        self.$('.alert-error').text('System Error').show();
      }
    });

  }

});


