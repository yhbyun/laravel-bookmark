var Bookmark = Backbone.Model.extend({
  urlRoot: '/bookmark/api/v1/bookmark',
  validation: {
    url: [{
      required: true,
      msg: 'Please enter a url'
    }, {
      pattern: 'url',
      msg: 'Please enter a valid url'
    }]
  }
});

