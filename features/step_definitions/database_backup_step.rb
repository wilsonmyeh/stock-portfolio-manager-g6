Given(/^the clock is set to 12:59pm$/) do
	visit 'http://localhost/ChangeTime.php'
end

When(/^the clock ticks to 1:01pm$/) do
	sleep_time_seconds = 120 # Sleep for 2 minutes
	sleep(sleep_time_seconds)
end

Then(/^the stocks should be backed up to the database$/) do
 	result = true;
end

