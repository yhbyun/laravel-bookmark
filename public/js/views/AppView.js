var AppView = Backbone.View.extend({

  events: {
    "click  #btn-home":            "home",
    "click  #btn-mytags":          "mytags",
    "click  #btn-addbookmark":     "addbookmark",
    "click  #btn-account":         "account",
    "click  #btn-logout":          "logout",
    "click  #btn-login":           "signin",
    "click  #btn-signup":          "signup",
    "click  #btn-started":         "signup",
    "submit #frm-search":          "search"
  },

  initialize: function() {
    _.bindAll(this, 'render', 'search', 'home', 'mytags', 'addbookmark', 'account', 'logout', 'signin', 'signup', 'select');
  },

  render: function() {
    $('#header').html(Templates.header);

    if (typeof App.user != 'undefined') {
      $('#header .public').hide();
      $('#header .logged-in').show();
    }
  },

  home: function(e) {
    e.preventDefault();
    App.router.navigate("", true);
  },

  search: function(e) {
    e.preventDefault();
    var term = $(e.target).find('input').val();
    $(e.target).find('input').val('').blur();
    App.router.navigate("search/" + term, true);
  },

  mytags: function(e) {
    e.preventDefault();
    App.router.navigate("mytags", true);
  },

  addbookmark: function(e) {
    e.preventDefault();
    App.router.navigate("bookmarks", true);
    new EditView({ model: new Bookmark() }).render();
  },

  account: function(e) {
    e.preventDefault();
    App.router.navigate("account", true);
  },

  logout: function(e) {
    e.preventDefault();
    $.ajax({
      type: 'GET',
      url: './api/v1/logout',
      dataType: 'json',
      success: function(data) {
        window.location = '/bookmark';
      }
    });
  },

  signin: function(e) {
    e.preventDefault();
    new SigninView().render();
  },

  signup: function(e) {
    e.preventDefault();
    new SignupView({ model: new User() }).render();
  },

  select: function(menuItem) {
    $('.nav li').removeClass('active');
    if (menuItem) $('.' + menuItem).addClass('active');
  }

});
