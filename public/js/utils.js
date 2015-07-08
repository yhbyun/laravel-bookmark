
jQuery(function ($) {

  Handlebars.registerHelper('relativeTime', function(time_value) {

    var parsed_date = Date.parse(time_value);
    var relative_to = new Date();
    var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);

    var r = '';
    if (delta < 60) {
      r = 'a minute ago';
    } else if(delta < 120) {
      r = 'couple of minutes ago';
    } else if(delta < (45*60)) {
      r = (parseInt(delta / 60)).toString() + ' minutes ago';
    } else if(delta < (90*60)) {
      r = 'an hour ago';
    } else if(delta < (24*60*60)) {
      r = '' + (parseInt(delta / 3600)).toString() + ' hours ago';
    } else if(delta < (48*60*60)) {
      r = '1 day ago';
    } else {
      r = (parseInt(delta / 86400)).toString() + ' days ago';
    }
    return r;
  });

  Handlebars.registerHelper('newBadge', function(time_value) {
    var parsed_date = Date.parse(time_value);
    var relative_to = new Date();
    var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);

    if (delta < (24*60*60)) {
      return '<div class="popular"><span>new</span></div>';
    } else {
      return '';
    }
  });

  Handlebars.registerHelper('pinify', function(pin) {
    if (pin === '1') {
      return 'on';
    } else {
      return '';
    }
  });

  Handlebars.registerHelper('isPinned', function(block) {
    if(this.pin === '1') {
      return block.fn(this);
    } else {
      return block.inverse(this);
    }
  });

  Handlebars.registerHelper('isPublic', function(block) {
    if(this.public === '1') {
      return block.fn(this);
    } else {
      return block.inverse(this);
    }
  });
});


