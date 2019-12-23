[![Build status](https://img.shields.io/travis/slk500/fighterchamp/master.svg)](https://travis-ci.org/slk500/fighterchamp)

## Behat
To run behat tests just run command "make behat". 
With tests that have @javascript annotation like login.feature -> Scenario: Register as Fighter you can see live how
Selenium commands the browser. 
Just install some remote desktop viewer. 
Ex. Ubuntu/Debian sudo apt-get install vinagre. Then open program, chose protocol VNC and paste ip & port
from docker (run command "docker ps") image -> selenium/node-chrome-debug 0.0.0.0:32835->5555/tcp, 0.0.0.0:32834->5900/tcp 
it would be the second addres in my case 0.0.0.0:32834. 
To learn more about Behat I recommend this video tutorial https://symfonycasts.com/screencast/behat