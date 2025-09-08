## Affinity System Monitor Task

## Which task you chose and why

I chose the System Monitor Plugin task. It stood out to me as bespoke plugins are my forte and it seemed the best optioj to demonstrate my skills.

## Key decisions you made

 - I used my own OOP boilerplate. This meant I only had to add the Admin class to register and render the widget.
 - On this occasion I chose to use require_once to include the admin class. This is due to there only being one file to include. For scalability or larger plugins I would use an auto-loader instead.

## One thing you'd improve with more time

 - Definitely styling. Even for a basic utility plugin it is a little dull.

## How you tested it

 - Created a new Docker container.
 - Copied my plugin files from local into WSL (I find Docker performance is appalling when mounting from the Windows filesystem).
 - Logged in as admin and activated the plugin.
 - Checked logs for any errors on activation.
 - Tested JSON download functionality.
 - Logged in as a user with "Editor" role to check that the widget is not displayed.