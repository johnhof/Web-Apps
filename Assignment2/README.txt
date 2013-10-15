Files Required:
	-Assignment1.php 
		-HTML tag, which calls driver.php

	-driver.php
		-Handles calling and output of the controller

	-controller.php
		-Central class which links together each sub-problem to create a general solution

	-FileHandler.php
		-Handles I/O for the controller. abstracted for extensibility

	-HTMLTranslator
		-takes various input strings to format into desired HTML tags

	-utility.php
		-general purpose utilities, sucha s printing and formatting output

General Description:
	This project was build to follow the Controller design pattern. Things like I/o and HTML formatting are abstracted into subcalsses called in wrapper functions buy the constructor itself. Not HTML is written directly, but all generated through PHP string manipulation. Additionally, I/O function cals are left ambiguously named so that the I/O method can be unplugged and repleced with another (files are a poor way to do this), without greaty effecting the code written. Rather than calling I/o at ever table display and manipulation, the I/O is only used during setup and submission. Using a persistant internal representation allows for easy updating. if the file is changed befor a user submits their own change, the data set is reloaded by default, and the sumbission is applied to the updated dataset. this makes mutual editing siple. Locks are only used when an actual read or write taked place.

	Note, the internal table should be its own class if I were following the controller pattern more closely, but it initially did not seem to be necessary. Roughly half the code in controller sohuld be pushed to a table class.