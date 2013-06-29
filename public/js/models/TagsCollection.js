var TagsCollection = Backbone.Collection.extend({
  model: Tag,
  url: '/bookmark/api/v1/tag'
});