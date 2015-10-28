# ptonElections
Princeton Elections

## Goals

Create an app for district vote counters to quickly upload their counts to the muni website. This will allow for quick unofficial vote counts to get out to the public.

##Usage
Home page is: start.php

Done:
Get machines from db including mailin and provisional
Only completed districts added to .csv for mapping
GUI for database setup
Spreadsheet view with totals
Sign in
Auto refresh and auto csv creation

### Note: These variables need to be set in SaveElection.php
$variables['pathForCSVandJson'] = "/Users/Guest/tmp";
//Database
$variables['username'] = "root"; //insert database username
$variables['password'] = ""; //insert database password
$variables['database'] = "ptonElections"; //database name
