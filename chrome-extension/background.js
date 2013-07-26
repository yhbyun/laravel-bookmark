// Called when the user clicks on the browser action.
chrome.browserAction.onClicked.addListener(function(tab) {
	var action_url = 'https://rivario.com/bookmark/bookmarklet?url='+encodeURIComponent(tab.url)+'&title='+encodeURIComponent(tab.title);
	chrome.tabs.create({ url: action_url });
});
