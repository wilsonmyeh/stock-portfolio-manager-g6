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