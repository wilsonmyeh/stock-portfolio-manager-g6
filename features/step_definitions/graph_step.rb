Given(/^waits for (\d+) seconds$/) do |seconds|
	sleep_time_seconds = seconds.to_i;
	sleep(sleep_time_seconds);
end

When(/^user waits for (\d+) seconds$/) do |seconds|
	sleep_time_seconds = seconds.to_i;
	sleep(sleep_time_seconds);
end

When(/^user clicks the graph tab for 3 months$/) do
	page.has_content?("3 month tab");
	click_button('3 month tab');
end

When(/^user clicks the tab for the "([^"]*)"$/) do |tab|
	page.has_content?(tab);
	click_button(tab);
end

When(/^user scrolls up while hovering over graph$/) do
	find('#graph_here').hover;
	page.execute_script "window.scrollBy(0, 10)";
end

Then(/^graph should be loaded on the dashboard$/) do
	page.has_content?("Stock Graph");
end

Then(/^graph axis should change to span 3 months$/) do
	page.has_content?("3 month tab");
end

Then(/^graph should change to "([^"]*)" graph$/) do |tab|
	page.has_content?(tab);
end

Then(/^graph should zoom$/) do
	page.has_content?("graph_here");
end