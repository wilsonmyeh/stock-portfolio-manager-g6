require 'selenium-webdriver'

driver = Selenium::WebDriver.for :firefox


When "I go to stock website" do
	driver.get "http://localhost/Frontend/login.html"
end