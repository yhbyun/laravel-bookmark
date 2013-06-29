var BookmarklyRouter = Backbone.Router.extend({

  routes: {
    "":                                     "index",
    "bookmarks":                            "bookmarks",
    "tag/:tag":                             "tag",
    "mytags":                               "tags",
    "search/*search":                       "search",
    "account":                              "account",
    "resetpassword":                        "resetpassword",
    "bookmarklet":                          "bookmarklet"
  },

  views: {},

  initialize: function() {
    _.bindAll(this, 'index', 'bookmarks', 'tag', 'tags', 'search', 'resetpassword', 'bookmarklet', 'setBody');

    //Create all the views, but don't render them on screen until needed
    this.views.app = new AppView({ el: $('body') });
    this.views.bookmarks = new BookmarksView();
    this.views.pub = new PublicView();
    this.views.tags = new TagsView();
    this.views.account = new AccountView();
    this.views.resetpassword = new ResetpasswordView();

    //The "app view" is the layout, containing the header and footer, for the app
    //The body area is rendered by other views
    this.view = this.views.app;
    this.view.render();
  },

  index: function() {
    //if the user is logged in, show their bookmarks, otherwise show the signup form
    if (typeof App.user != 'undefined') {
      this.navigate("bookmarks", true);
    } else {
      this.setBody(this.views.pub);
      this.view.body.fetch({reset:true});
    }
    this.views.app.select('home-menu');
  },

  bookmarks: function() {
    this.setBody(this.views.bookmarks, true);
    this.view.body.fetch({reset:true});
    this.views.app.select();
  },

  tag: function(tag) {
    this.setBody(this.views.bookmarks, true);
    this.view.body.fetch({ data: { tag: tag }, reset:true });
    this.views.app.select('mytags-menu');
  },

  search: function(search) {
    this.setBody(this.views.bookmarks, true);
    this.view.body.fetch({ data: { search: search }, reset:true });
    this.views.app.select();
  },

  tags: function() {
    this.setBody(this.views.tags, true);
    this.view.body.fetch({reset:true});
    this.views.app.select('mytags-menu');
  },

  account: function() {
    this.setBody(this.views.account, true);
    this.view.body.render();
    this.views.app.select('account-menu');
  },

  resetpassword: function(token) {
    this.setBody(this.views.resetpassword, false);
    this.view.body.render();
  },

  bookmarklet: function(params) {
    this.setBody(this.views.bookmarks, true);
    this.view.body.fetch({reset:true});

    var url = params && params.url;
    var title = params && params.title;
    var bookmark = { url: url, title: title };
    new EditView({ model: new Bookmark(bookmark) }).render();
  },

  setBody: function(view, auth) {
    if (auth == true && typeof App.user == 'undefined') {
      this.navigate("", true);
      return;
    }

    if (typeof this.view.body != 'undefined')
      this.view.body.unrender();

    this.view.body = view;
  }

});
