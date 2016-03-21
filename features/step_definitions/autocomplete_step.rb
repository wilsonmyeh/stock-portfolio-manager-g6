When(/^user types "([^"]*)" into the searchbar$/) do |arg1|
  fill_in('searchbar', :with => username)
end

Then(/^user should see a dropdown of search results$/) do
	
end
