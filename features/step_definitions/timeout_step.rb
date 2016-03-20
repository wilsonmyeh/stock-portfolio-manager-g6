Given(/^user navigates to http:\/\/localhost\/Frontend\/login\.html$/) do
	visit 'http://localhost/Frontend/login.html'
end

Given(/^fills Username as "([^"]*)"$/) do |username|
  fill_in('user', :with => username)
end

Given(/^fills Password as "([^"]*)"$/) do |password|
  fill_in('password', :with => password)
end

Given(/^logs in$/) do
	click_button('Login')
	text = page.driver.browser.switch_to.alert.text # Should be Successful log in! popup
	expect(text).to eq 'Successful log in!' # RSpec to check string
	page.driver.browser.switch_to.alert.accept # Click through the popup
	page.should have_content('Your Dashboard')
end

When(/^user is inactive for (\d+) minutes$/) do |minutes|
	sleep_time_seconds = minutes.to_i * 60 + 10; # Convert to seconds and add a 10 second buffer
	sleep(sleep_time_seconds) # Sleep until automatic timeout
end

Then(/^user should be automatically logged out$/) do
	text = page.driver.browser.switch_to.alert.text # Should be Successful log out! popup
	expect(text).to eq 'Successful log out!'
end