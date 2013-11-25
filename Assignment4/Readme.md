#Assignment 4: Hangman

##User State

###Request
```javascript
{
  url : '/Core/Backend/GameHandler.php'
  type: "POST",
  data: {
    'request': 'user_state'
  },
  dataType: "xml"
}
```

###Response
The Database is querried for user state and appropriate action is taken

* if the user is not in a current game, they will be placed in the queue and XML returned
* if the user is in a game as the maker, the XML for the game is returned
* if the user is in a game as the guesser, the XML for the game is returned

------------------------------------------------

##EnQueue

###Request
```javascript
{
  url : '/Core/Backend/GameHandler.php'
  type: "POST",
  data: {
    'request': 'enQueue'
  }
}
```

###Response
```
'Success'/'Failure'
```

------------------------------------------------

##DeQueue

###Request
```javascript
{
  url : '/Core/Backend/GameHandler.php'
  type: "POST",
  data: {
    'request': 'deQueue'
  }
}
```

###Response
```
'Success'/'Failure'
```
-----------------------------------------------
