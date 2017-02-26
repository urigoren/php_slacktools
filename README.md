Php Slack Tools
===

php functions for common slack integrations

Main functions:
  1. `slack()`  - send a messagge to che slack channel
  1. `slack_mails()`  - checks emails and sends the new ones to the slack channel

Use from PHP
---
   1. Clone the repository
   1. Update config.json to your slack channel's webhook, and imap server (is using slack_mails)
   1. Include "slack_tools.php" in your project

Set up a mail checker slack bot:
---
  1. Run `printf "include 'slack_tools.php';\nslack_mails()\n" > mailbot.php`
  1. Run `crontab -e` to start a new cronjob
  1. Add `*/5 * * * * php -c mailbot.php` to check for mails every 5 minutes
  1. Exit and save
