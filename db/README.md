# Database directory

Each time 10 rows are included as the static data for the API. We cannot distribute the raw data as this is not allowed by De Lijn, neither allowed by MIVB/STIB. If you are an iRail member and you don't have the credentials to the FTP, you can drop Yeri a note: yeri aŧ iRail.be.

## Getting started:

Execute the SQL queries in this directory to your database

## A cronjob: ran every 2 weeks on our servers

    #!/bin/bash
    wget ftp://irailvzw:********@poseidon.delijn.be/current/*.zip -O /tmp/dl.zip
    unzip /tmp/dl.zip
    
    # calendar.csv places.csv routes.csv segments.csv stops.csv trips.csv
    
    mysql irail-data -h localhost -uXXXXXXXXXXX -pXXXXXXXXXXXXXXXXX -e "LOAD DATA LOCAL INFILE 'calendar.csv' REPLACE INTO TABLE DL_calendar FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n'"
    #mysql irail-data -h localhost -uXXXXXXXXXXX -pXXXXXXXXXXXXXXXXX -e "LOAD DATA LOCAL INFILE 'places.csv' REPLACE INTO TABLE DL_places FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n'"
    mysql irail-data -h localhost -uXXXXXXXXXXX -pXXXXXXXXXXXXXXXXX -e "LOAD DATA LOCAL INFILE 'routes.csv' REPLACE INTO TABLE DL_routes FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n'"
    mysql irail-data -h localhost -uXXXXXXXXXXX -pXXXXXXXXXXXXXXXXX -e "LOAD DATA LOCAL INFILE 'segments.csv' REPLACE INTO TABLE DL_segments FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n'"
    mysql irail-data -h localhost -uXXXXXXXXXXX -pXXXXXXXXXXXXXXXXX -e "LOAD DATA LOCAL INFILE 'stops.csv' REPLACE INTO TABLE DL_stops FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n'"
    mysql irail-data -h localhost -uXXXXXXXXXXX -pXXXXXXXXXXXXXXXXX -e "LOAD DATA LOCAL INFILE 'trips.csv' REPLACE INTO TABLE DL_trips FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n'"
    
    rm /tmp/dl.zip
    rm *.csv
    