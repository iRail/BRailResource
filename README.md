# iRail TDT-Module

For The DataTank (TDT) see next chapter. We created a module upon our own generic API-system. This allows us to only focus on the most important thing in this repository: data-mapping. By data-mapping we mean: getting the right data from who knows where and putting it in a nice object model.

What this module can do:
 * It handles data from:
    * NMBS
    * NS
    * De Lijn
    * MIVB
    * All kinds of GTFS
    * ... more to come
 * It creates a RESTful API for
    * Liveboard: live departure and arrival times
    * Connections: a route from A to B
    * Vehicle: realtime information about a certain vehicle
    * Stations: station information

# The DataTank

The DataTank (source: http://github.com/iRail/The-DataTank/) is a system to make your data available as easy as possible. Whether you are an app-developer or a data-publisher, you're at the right address:

It is a product made by the iRail NPO (vzw/asbl) to build a better back-end. We believe that in a lot of cases organizations are appdevelopers and data-owners.

## The DataTank for a datapublisher

### Non-technical datapublisher

Hi, sorry for making all this documentation so utterly boring and difficult. You should just go to http://thedatatank.com and look for the "publish my data" button.

### Technical datapublisher

Hi! You probably want to set up an API (or webservice) of your own containing all your data in a RESTful (read: easy) way. The DataTank does not only contain source-code for you to create your own web-service, it will as well try to understand your data, add data that may interest you, add errorhandling and self-documentation, add business intelligence tools, feedback mechanisms and so on. Feel free to test our main-set-up at http://www.thedatatank.com.

You can go several ways to publish your data:

 * If you know PHP, let's create a module ourself. You just need implement the AResource.class.php and map all information to an object model.
 * A lot of data is already in a computer readable format. We provide a dropbox style interface for these kinds of data (CSV, RDF, XML, KML, RDBMS...). Try it!
 * Your data can be in a DataTank already! In that case you may want to collect your own feedback and collect your own statistics. You can do this by adding the right URI to your own datatank set-up.

## The DataTank for an app-developer

Finally! Where have you been so long? We have a huge amount of data that we want you to work with! Set up a datatank on your server and collect statistics, create a feedback system, have good errorhandling, for you app's data-needs.

# Setting up your own iRail API

If you want to set up your own server with this data there may be several reasons:

 1. You fiercly hate us and want to fork the code so you can work without us
 2. The quality of our servers is below zero
 3. This project became inactive
 4. You don't know what you're doing
 5. You need a local test-server
 6. Other? Let us know - info at iRail.be

The code is open-source because we believe the code that is running on our servers should be readable by anyone for these reasons

 * You should have the freedom to learn how software is made
 * You should have the freedom to correct our mistakes
 * You should have the freedom to help us out
 * You should have the freedom to go your own way if you disagree with us

If you want to continue setting up your own server, follow these instruction:

 1. Set up a DataTank: http://github.com/iRail/The-DataTank
 2. git clone this repository
 3. Put the modules directory in the root of your DataTank - instance
 4. Don't forget to fill out the right parameters in the Config file
 5. DONE

# iRail

iRail is an attempt to make transportation time schedules easily available for anyone. 

We are doing this by creating an Application Programming Interface. This interface is implemented in PHP and can be reused by various of other projects.

Our main site consists of a very easy mobile website to look up time schedules using our own API.

Native applications using the iRail API and created or supported by the iRail team are named BeTrains.

All information can be found on [Project iRail](http://project.irail.be/).

Some interesting links:

  * Source: <http://github.com/iRail/iRail>
  * Mailing: <http://list.irail.be/>
  * Trac: <http://project.irail.be/>
  * API: <http://api.irail.be/>
  * BeTrains: <http://betrains.mobi/>