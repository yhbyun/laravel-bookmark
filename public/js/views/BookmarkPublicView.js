var BookmarkPublicView = Backbone.View.extend({

  initialize: function() {
    _.bindAll(this, 'render');

    var d = new Date(this.model.get('created_at'));
    this.model.set({date: d});
    this.model.bind('change', this.render);

    $(this.el).addClass('item');
  },

  render: function() {
    var source = Templates.bookmarkpublic;
    var template = Handlebars.compile(source);
    var html = template(this.model.attributes);
    $(this.el).html(html);
  }

});

