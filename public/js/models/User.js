var User = Backbone.Model.extend({
  urlRoot: '/bookmark/api/v1/user',

  validation: {
    username: [{
      required: true,
      msg: 'Please enter username'
    }],
    password: [{
      required: true,
      msg: 'Please enter password'
    }],
    email: [{
      required: true,
      msg: 'Please enter email address'
    }, {
      pattern: 'email',
      msg: 'Please enter a valid email address'
    }]
  }
});


