var BookmarksCollection = Backbone.Collection.extend({
  model: Bookmark,
  subFolder : '',
  url: function () {
    return '/bookmark/api/v1/bookmark' + this.subFolder;
  }
});