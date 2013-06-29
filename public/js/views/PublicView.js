var PublicView = Backbone.View.extend({

  initialize: function() {
    _.bindAll(this, 'fetch', 'render', 'unrender');

    this.collection = new BookmarksCollection();
    this.collection.bind('reset', this.render, this);
    this.collection.bind('add', this.render, this);
  },

  fetch: function(options) {
    this.collection.subFolder = '/public';
    this.collection.fetch(options);
  },

  render: function() {
    $(this.el).html(Templates.pub);
    $('#app').append(this.el);

    if (this.collection.models.length == 0) {
      $(this.el).css('width', '960px').css('margin', '0px auto');
      $(this.el).html(Templates.bookmarks);

    } else {

      $('#new-bookmarks').css('margin', '90px auto 15px auto').css('width', 'auto').css('background', 'transparent');
      $('#new-bookmarks').html('');
      _(this.collection.models).each(function(bookmark) {
        var bmv = new BookmarkPublicView({ model: bookmark });
        bmv.render();
        //$(self.el).append(bmv.el);
        $('#new-bookmarks').append(bmv.el);
      });

      $('#new-bookmarks').masonry({
        itemSelector: '.item',
        columnWidth: 255,
        isFitWidth: true
      });

    }
  },

  unrender: function() {
    $('#new-bookmarks').masonry('destroy').detach();
    $(this.el).detach();
  }

});
