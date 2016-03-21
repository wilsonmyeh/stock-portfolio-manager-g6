Feature: Database Backup Feature
	At 4:00pm EST (1:00pm PST), all owned stocks should be backed up to the Parse database.

Scenario: Database Backup
	Given the clock is set to 12:59pm
	When the clock ticks to 1:01pm
	Then the stocks should be backed up to the database