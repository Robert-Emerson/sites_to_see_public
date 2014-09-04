#Sights to See!
---------------------------------------------------------------------------
####Built for hack.uva 2014
--------------------------------------------------------------------------
View it online at [Challenge Post] (http://challengepost.com/software/sights-to-see)!

A travel web-app, users can use **Sights to See!** to identify interesting landmarks in areas where they are or may visit. It'll display the 50 most interesting photos (according to Flickr) within a 20km radius of the entered location, and then users can click on photos to add them to a map. They can then share this map with others via URL or SMS text message.

Images are fetched from Flickr, parsed to produce a URL link to the actual image, associated with geographic coordinates, and then displayed in a fluid grid. If the user wants to find interesting locations around a city, the Google Geocoding API is used to determine latitude and longitude. Otherwise, these values are taken from the user's browser, if they consent. The app is responsive, allowing it to scale well from small phone screens up to much larger screens.

---------------------------------------------------------------------------
Currently configured for an Apache2 web server. Requires mod rewrite, as well as MongoDB for PHP installed. Other dependencies (Twilio, FlightPHP) are included with the code.
