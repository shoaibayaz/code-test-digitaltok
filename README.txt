Choose ONE of the following tasks.
Please do not invest more than 2-4 hours on this.
Upload your results to a Github repo, for easier sharing and reviewing.

Thank you and good luck!



Code to refactor
=================
1) app/Http/Controllers/BookingController.php
2) app/Repository/BookingRepository.php

Code to write tests
=====================
3) App/Helpers/TeHelper.php method willExpireAt
4) App/Repository/UserRepository.php, method createOrUpdate


----------------------------

What I expect in your repo:

X. A readme with:   Your thoughts about the code. What makes it amazing code. Or what makes it ok code. Or what makes it terrible code. How would you have done it. Thoughts on formatting, structure, logic.. The more details that you can provide about the code (what's terrible about it or/and what is good about it) the easier for us to assess your coding style, mentality etc

I am not fan of repository pattern, but it makes controller and model so much cleaner which is the good thing but nowadays, everything is doable with model and controller
as we can add traits and many more things

There is no variable checking existence which terrible and horrible and at some place fetching all records from one table is not a good practice either

Y.  Refactor it if you feel it needs refactoring. The more love you put into it. The easier for us to asses your thoughts, code principles etc

I've tried to refactor as much as I could there is a lot of room for improvement as I have not enough knowledge of business and proper time refactor but here are some tips

there are some function in repository which are ginormous as good practice your function not exceed than 15 lines and in extreme condition max 30 or 40 excluding building an array
and on those function readability is problem I need to parse every condition as I go through the code and lastly if you find your self doing elseif try key value array instead I did on one place in repository
you can take a look


IMPORTANT: Make two commits. First commit with original code. Second with your refactor so we can easily trace changes. 


NB: you do not need to set up the code on local and make the web app run. It will not run as its not a complete web app. This is purely to assess you thoughts about code, formatting, logic etc


===== So expected output is a GitHub link with either =====

1. Readme described above (point X above) + refactored code 
OR
2. Readme described above (point X above) + a unit test of the code that we have sent

Thank you!


