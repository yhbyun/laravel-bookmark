var EditView = Backbone.View.extend({

  events: {
    "click .save":            "save",
    "click .cancel":          "cancel",
    "submit form":            "save"
  },

  initialize: function() {
    _.bindAll(this, 'render', 'unrender', 'save', 'cancel');

    Backbone.Validation.bind(this);
  },

  render: function() {
    var source = Templates.edit;

    this.model.get('public') === '1' ? is_public = 'checked' : is_public = '';
    var model = _.extend(this.model.toJSON(), {is_public: is_public});

    var template = Handlebars.compile(source);
    var html = template(model);
    $(this.el).html(html);

    $('body').append(this.el);
    this.$('input[name=tags]').attr('id', 'tags' + this.model.id);
    /*
    this.$('input[name=tags]').tagsInput({
      autocomplete: {
        source: './api/v1/autocomplete'
      },
      defaultText: '',
      width: '367px'
    });
    */
    this.$('input[name=tags]').tagsInput({
      autocomplete: {
        source: './api/v1/autocomplete'
      },
      width: '367px'
    });

    this.$('#public').wrap('<div class="switch" id="switch_wrapper"/>').parent().bootstrapSwitch();
    this.$('#switch_wrapper').on('switch-change', function (e, data) {
      $(data.el).attr('checked', data.value);
    });

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

  save: function(e) {
    e.preventDefault();

    var url = this.$('input[name=url]').val();
    var title = this.$('input[name=title]').val();
    var description = this.$('input[name=description]').val();
    var share = this.$('input[name=public]').attr('checked') ? '1' : '0';
    var taglist = this.$('input[name=tags]').val();
    var tags = Array();

    if (taglist.indexOf(',') !== -1) {
      tags = taglist.split(',');
    } else if (taglist.length > 0) {
      tags.push(taglist);
    }

    this.$('.alert').hide();

    if (this.model.set({ url: url, title: title, description: description, public: share, tags: tags, timestamp: Math.round(new Date().getTime() / 1000) }, {validate:true})) {
      this.model.save();
      this.unrender();

      if (this.model.isNew()) {
        App.router.view.body.collection.add(this.model, { at: 0 });
      } else {
        $(App.router.view.body.el).masonry('reload');
      }
    } else {
      this.$('.alert-error').fadeIn();
    }
  },

  cancel: function(e) {
    e.preventDefault();
    this.unrender();
  }
});
