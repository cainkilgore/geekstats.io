# geekstats.io

Geekstats.io is the alternative service which can monitor any and all services that you throw at it. Currently a work in progress, Geekstats will give you chart information and instant-ping response times. The option of having services public or private. 

# Requirements
`a2enmod rewrite`
`> php5.4`
`mysql`

# Known Issues
- Unable to create services (You can do so manually in the SQL table `stats`)

# Installation

1. Fork this repository and place it into a publicly accessible web directory. (Preferably give it it's own domain, like geekstats.domain.com)
2. Create a new MySQL Database and grant privileges to a new user.
3. Copy https://cain.sh/i/geekstats.sql and import it into the database you just created.
4. Edit config.php and update the `$databaseHostname, $databaseName, $databasePassword, $databaseUsername` details to reflect.
5. Edit core/cron-ping.php to reflect the file path that servicePing.php is in.
6. Create a `crontab -e` for the PHP script above.
7. Navigate to the web directory in your browser and register an account.
8. Done!


This is still a heavy work in progress and is not finished, _yet_. Please leave feedback and create Pull Requests if you feel a feature that isn't already in there should be.
