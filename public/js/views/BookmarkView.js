var BookmarkView = Backbone.View.extend({

  events: {
    "dblclick":               "edit",
    "click .edit":            "edit",
    "click .delete":          "del",
    "click .tag":             "filter",
    "click .pushpin":         "pinify"
  },

  initialize: function() {
    _.bindAll(this, 'render', 'edit', 'del', 'pinify');

    var d = new Date(this.model.get('timestamp') * 1000);
    this.model.set({date: d});
    this.model.set({thumburl: encodeURIComponent(this.model.get('url')) });
    this.model.bind('change', this.render);

    $(this.el).addClass('item');
  },

  render: function() {
    var source = Templates.bookmark;
    var template = Handlebars.compile(source);
    var html = template(this.model.attributes);
    $(this.el).html(html);
  },

  edit: function(e) {
    e.preventDefault();
    new EditView({ model: this.model }).render();
  },

  del: function(e) {
    e.preventDefault();
    var del = confirm('Are you sure you want to delete this bookmark?');
    if (del) {
      App.router.view.body.collection.remove(this.model);
      this.model.destroy();
      $(this.el).remove();
      $(App.router.view.body.el).masonry('reload');
    }
  },

  filter: function(e) {
    e.preventDefault();
    var tag = $(e.target).text().trim();
    App.router.navigate('tag/' + encodeURIComponent(tag), true);
  },

  pinify: function(e) {
    e.preventDefault();

    var pinify = this.model.get('pin');
    pinify = (pinify === '0') ? '1' : '0';
    this.model.set({pin: pinify});
    this.model.save();
  }

});
