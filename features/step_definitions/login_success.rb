require 'selenium-webdriver'

driver = Selenium::WebDriver.for :firefox
arr = ["One", "Two"]

When "I go to stock website" do
	driver.get "http://localhost/Frontend/login.html"
	print arr[0]
end