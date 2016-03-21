Given(/^unsuccesful log in$/) do
	click_button('Login')
	text = page.driver.browser.switch_to.alert.text # Should be Successful log in! popup
	expect(text).to eq 'invalid login parameters' # RSpec to check string
	page.driver.browser.switch_to.alert.accept # Click through the popup
	page.should have_content('Welcome to Stock Portfolio Manager')
end
Given(/^logs out$/) do
	click_button('logoutbutton')
	text = page.driver.browser.switch_to.alert.text # Should be Successful log in! popup
	expect(text).to eq 'Successful log out!' # RSpec to check string
	page.driver.browser.switch_to.alert.accept # Click through the popup
	page.should have_content('Welcome to Stock Portfolio Manager')
end


