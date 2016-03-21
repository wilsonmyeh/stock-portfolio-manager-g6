Given(/^fills Ticker as "([^"]*)"$/) do |ticker|
  fill_in('ticker', :with => ticker)
end

Given(/^fills Quantity as "([^"]*)"$/) do |quantity|
  fill_in('quantity', :with => quantity)
end

Given(/^fills Name as "([^"]*)"$/) do |name|
  fill_in('compName', :with => name)
end

Given(/^buys stock successfully$/) do
	click_button('Buy')
	text = page.driver.browser.switch_to.alert.text # Should be Successful log in! popup
	expect(text).to eq 'Transaction successful!' # RSpec to check string
	page.driver.browser.switch_to.alert.accept # Click through the popup
end

Given(/^buys stock insufficient funds$/) do
	click_button('Buy')
	text = page.driver.browser.switch_to.alert.text # Should be Successful log in! popup
	expect(text).to eq 'Insufficient funds. Transaction failed.' # RSpec to check string
	page.driver.browser.switch_to.alert.accept # Click through the popup
end
Given(/^buys nonexistent stock$/) do
	click_button('Buy')
	text = page.driver.browser.switch_to.alert.text # Should be Successful log in! popup
	expect(text).to eq 'Stock does not exist. Transaction failed.' # RSpec to check string
	page.driver.browser.switch_to.alert.accept # Click through the popup
end
Given(/^sells nonexistent stock$/) do
	click_button('Sell')
	text = page.driver.browser.switch_to.alert.text # Should be Successful log in! popup
	expect(text).to eq 'Stock does not exist. Transaction failed.' # RSpec to check string
	page.driver.browser.switch_to.alert.accept # Click through the popup
end
Given(/^sell stock insufficient funds$/) do
	click_button('Sell')
	text = page.driver.browser.switch_to.alert.text # Should be Successful log in! popup
	expect(text).to eq 'Insufficient quantity. Transaction failed.' # RSpec to check string
	page.driver.browser.switch_to.alert.accept # Click through the popup
end
Given(/^sells insufficient quantity stock$/) do
	click_button('Sell')
	text = page.driver.browser.switch_to.alert.text # Should be Successful log in! popup
	expect(text).to eq 'You do not own enough shares. Transaction failed.' # RSpec to check string
	page.driver.browser.switch_to.alert.accept # Click through the popup
end
Given(/^sells stock$/) do
	click_button('Sell')
	text = page.driver.browser.switch_to.alert.text # Should be Successful log in! popup
	expect(text).to eq 'Transaction successful!' # RSpec to check string
	page.driver.browser.switch_to.alert.accept # Click through the popup
end

Given(/^check update stock$/) do
	page.should have_css("#GOOG", :text => "Alphabet")
end