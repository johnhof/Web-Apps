#Assignment 4: Hangman

/Core/Backend/GameHandler.php
------------------------------------------------

###Request
```javascript
  type: "POST",
  data: {
    'request': 'user_state'
  },
  dataType: "xml"
```

###Response
The Database is querried for user state and appropriate action is taken

* if the user is not in a current game, they will be placed in the queue and XML returned
* if the user is in a game as the maker, the XML for the game is returned
* if the user is in a game as the guesser, the XML for the game is returned

------------------------------------------------

###Request
```javascript
form {
  foo: bar
}
```

###Response
foo bar

* lorum ipsum

-----------------------------------------------
