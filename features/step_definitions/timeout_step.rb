When(/^user is inactive for (\d+) minutes$/) do |minutes|
	sleep_time_seconds = minutes.to_i * 60 + 10; # Convert to seconds and add a 10 second buffer
	sleep(sleep_time_seconds) # Sleep until automatic timeout
end

Then(/^user should be automatically logged out$/) do
	text = page.driver.browser.switch_to.alert.text # Should be Successful log out! popup
	expect(text).to eq 'Successful log out!'
end