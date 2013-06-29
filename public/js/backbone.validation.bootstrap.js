_.extend(Backbone.Validation.callbacks, {
  valid: function(view, attr, selector) {
    var control, group;
    control = view.$('[' + selector + '=' + attr + ']');
    group = control.parents(".control-group");
    group.removeClass("error");
    if (control.data("error-style") === "tooltip") {
      if (control.data("tooltip")) {
        return control.tooltip("hide");
      }
    } else if (control.data("error-style") === "inline") {
      return group.find(".help-inline.error-message").remove();
    } else {
      return group.find(".help-block.error-message").remove();
    }
  },
  invalid: function(view, attr, error, selector) {
    var control, group, position, target;
    control = view.$('[' + selector + '=' + attr + ']');
    group = control.parents(".control-group");
    group.addClass("error");
    if (control.data("error-style") === "tooltip") {
      position = control.data("tooltip-position") || "right";
      control.tooltip({
        placement: position,
        trigger: "manual",
        title: error
      });
      return control.tooltip("show");
    } else if (control.data("error-style") === "inline") {
      if (group.find(".help-inline").length === 0) {
        group.find(".controls").append("<span class=\"help-inline error-message\"></span>");
      }
      target = group.find(".help-inline");
      return target.text(error);
    } else {
      if (group.find(".help-block").length === 0) {
        group.find(".controls").append("<p class=\"help-block error-message\"></p>");
      }
      target = group.find(".help-block");
      return target.text(error);
    }
  }
});