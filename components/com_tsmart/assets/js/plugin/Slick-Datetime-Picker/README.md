# slickDTP
JQuery Calendar and Clock for choosing date and time

Please look at the sample provide.

Java script looks like this:

    var slickDTP = new SlickDTP();
    	
    $( "#mysqldate" ).click(function() {
    	slickDTP.pickDate('#mysqldate','#prettydate');
    });
    
    
where #mysqldate is the ID of the form element that will get the choosen date time in MySQl Format (YYYY-MM-DD HH:MM:SS).
where #prettydate is the ID of the form that will show the selected dat time but in a friendly way. i.g. June 25, 2015 @ 6:30 PM

I recommend using the form type hidden for the mysql date.
