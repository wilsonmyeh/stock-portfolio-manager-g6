When(/^user types "([^"]*)" into the searchbar$/) do |arg1|
  fill_in('searchbar', :with => arg1)
end

Then(/^user should see a dropdown of search results$/) do
	result = true;
end
