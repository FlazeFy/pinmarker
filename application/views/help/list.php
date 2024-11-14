<style>
    .section-help {
        border: 1.5px solid var(--primaryColor);
        padding: var(--spaceXMD);
        border-radius: var(--roundedLG);
        margin-bottom: var(--spaceJumbo);
        -webkit-transition: all 0.35s;
        -o-transition: all 0.35s;
        transition: all 0.35s;
    }
    .section-help:hover {
        transform: scale(1.025);
    }
    .menu-btn-help {
        text-decoration: none;
        font-weight: bold;
        color: var(--primaryColor);
        cursor: pointer;
    }
</style>

<h1 class="fw-bold text-center mb-4">PinMarker User Manual</h1>
<div class="section-help">
    <h3>How to Join with PinMarker?</h3>
    ...
</div>

<div class="section-help">
    <h3>How to Login?</h3>
    <p>In this section, you must provide your username or email and password. So you can login through this apps</p>
    <b>The Steps : </b>
    <ol>
        <li>In the landing page, press the <b>Go to Login</b> button or find the section with title <b>"Welcome to PinMarker"</b></li>
        <li>In this form, fill the <b>Email / Username</b> and <b>Password</b> field</li>
        <li>Press the <b class="text-success">Sign In</b> button to verify your login</li>
    </ol>
</div>

<div class="section-help">
    <h2 class="fw-bold">Menu : Dashboard</h2>
    <h3>The Summary</h3>
    <p>In this section, we present your data analysis in text format. The analysis only includes current data, meaning deleted pins or markers will not be shown.</p>    
    <b>What's contain?</b>
    <ol> 
        <li><b>Total Markers</b> refers to the total number of markers or saved locations in the app</li> 
        <li><b>Total Favorite Pins</b> refers to the total of pins or markers that the user has liked or added to their favorites</li> 
        <li><b>Last Visit</b> shows the most recently visited place in your history</li> 
        <li><b>Most Visited</b> displays the name and total visits of the most visited pin or marker</li> 
        <li><b>Most Used Category</b> shows the name and total count of the most used category for creating pins or markers</li> 
        <li><b>Last Added</b> shows the most recently created pin</li> 
    </ol>
    <h3>The Stats</h3>
    <p>In this section, we present your data analysis using charts with their legend. The analysis only includes current data, meaning deleted pins or markers will not be shown.</p>    
    <b>What's contain?</b>
    <ol> 
        <li><b>Total Pins by Category</b> shows the 7 most used category to create a pins or markers. The data are presented using Pie Chart</li> 
        <li><b>Total Visit by Pin Category</b> displays the 7 most visited category for each pins or markers. The data are presented using Pie Chart</li> 
        <li><b>Total Gallery by Pin</b> lists the 7 pins or markers that have most galleries associated. The data are presented using Pie Chart</li> 
        <li><b>Total Visits By</b> shows the 7 most type of transportation that use to visits each pins or markers. The data are presented using Pie Chart</li> 
        <li><b>Total Visits With</b> displays the 7 people you visit with most often. The data are presented using Pie Chart</li> 
        <li><b>Total Visits by Month in [YEAR]</b> breaks down the total number of visits by month for the specified year. The data are presented using Line Chart</li> 
        <li><b>Distance Traveled in [YEAR]</b> shows the total distance traveled in kilometers for the specified year. The data are presented using Line Chart</li> 
    </ol>
    <b>Change the Year using in the Chart</b>
    <ol> 
        <li>Find a section with title <b>"Total Visit By Month"</b></li>
        <li>Next to it, you will see an option to select the <b>available years</b> that can be used to filter the stats. The years displayed are based on when your account was registered</li>
        <li>Select a year, and <b class="text-success">it will refresh</b> the <b>Total Visits by Month in [YEAR]</b> and <b>Distance Traveled in [YEAR]</b> based on your selected year</li>
    </ol>
</div>

<div class="section-help">
    <h2 class="fw-bold">Menu : Maps</h2>
    <h3>Find Pins or Markers by Name</h3>
    <p>Using the search bar, you can find one or many your pins by search it's name</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/MapsController'>Maps</a> page</li>
        <li>You will be taken to Maps page and then you will see section with title <b>"Maps"</b></li>
        <li>At the top of maps grid, fill the <b>Filter By Name</b> and press the <b>Search</b> button or click on the suggestion show at the bottom of the search bar</li>
        <li>The maps <b class="text-success">will be refresh</b> and show markers based on your search</li>
    </ol>
    <h3>Reset the Name Filter</h3>
    <p>After search some pin and you want back to show all available pins or marker, you can use reset filter</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/MapsController'>Maps</a> page</li>
        <li>You will be taken to Maps page and then you will see section with title <b>"Maps"</b></li>
        <li>At the top of maps grid, click on the button with reload icon</li>
        <li>The maps <b class="text-success">will be refresh</b> and show all markers</li>
    </ol>
    <h3>Find Pins or Markers by Category</h3>
    <p>Using the dropdown, you can find one or many your pins by search it's category</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/MapsController'>Maps</a> page</li>
        <li>You will be taken to Maps page and then you will see section with title <b>"Maps"</b></li>
        <li>At the top of maps grid, find dropdown <b>Filter By Category</b> and choose category of pin you want to show</li>
        <li>The maps <b class="text-success">will be refresh</b> and show markers based on your search</li>
    </ol>
</div>

<div class="section-help">
    <h2 class="fw-bold">Menu : Global-Collection</h2>
    <h3>Add New Global List</h3>
    <p>To create a new list you must provide the List Name</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/GlobalListController'>Global-Collection</a> page</li>
        <li>You will be taken to Global List page and then you will see section with title <b>"Global List"</b></li>
        <li>Click on the <b>Add Global List</b> button, and you will be redirected to Add Global List page</li>
        <li>Fill the form, start from mandatory field of <b>List Name</b></li>
        <li>And you can also fill the optional form like <b>List Code</b>, <b>List Description</b>, and select the tag in <b>Tags</b> area</li>
        <li>Attach some pins or markers if you want in the area of <b>Attach Some Pin</b></li>
        <li>And lastly just press <b class="text-success">Save Global List</b> to submit</li>
    </ol>
    <h3>See a Global List Detail</h3>
    <p>You can share a global list with others publicly</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/GlobalListController'>Global-Collection</a> page</li>
        <li>You will be taken to Global List page and then you will see section with title <b>"Global List"</b></li>
        <li>Choose a list you want to browse the pins or markers, and then click <b>See Detail</b></li>
        <li>You will be <b class='text-success'>taken to Detail Global List</b> page, and you can find the List Name, Tag Attached, Description, and Pins Attached on it</li>
    </ol>
    <h3>Delete a Global List</h3>
    <p>After delete a list, other people can't access it. And this only remove attached pins not permentally delete the pins</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/GlobalListController'>Global-Collection</a> page</li>
        <li>You will be taken to Global List page and then you will see section with title <b>"Global List"</b></li>
        <li>Choose a list you want to browse the pins or markers, and then click <b>See Detail</b></li>
        <li>You will be taken to Detail Global List page, at the top of the page click <b>Delete Global List</b> and a popup will appear</li>
        <li>In the confirmation popup just press the <b class="text-success">Yes, delete it!</b></li>
    </ol>
    <h3>Remove Pin From Global List</h3>
    <p>After delete a list, other people can't access it. And this only remove attached pins not permentally delete the pins</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/GlobalListController'>Global-Collection</a> page</li>
        <li>You will be taken to Global List page and then you will see section with title <b>"Global List"</b></li>
        <li>Choose a list you want to browse the pins or markers, and then click <b>See Detail</b></li>
        <li>You will be taken to Detail Global List page, choose a pin you want to delete and click <b>Remove</b> and a popup will appear</li>
        <li>In the confirmation popup just press the <b class="text-success">Yes, delete it!</b></li>
    </ol>
    <h3>Share a Global List</h3>
    <p>You can share a global list with others publicly</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/GlobalListController'>Global-Collection</a> page</li>
        <li>You will be taken to Global List page and then you will see section with title <b>"Global List"</b></li>
        <li>Choose a list you want to share, and then click <b class="text-success">Share</b>. Finally the global list URL has been copied to your clipboard and ready to be share</li>
    </ol>
</div>

<div class="section-help">
    <h2 class="fw-bold">Menu : List</h2>
    <h3>Add New Marker</h3>
    <p>To create a new marker you must provide the Location Name, Coordinate, and Category</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/ListController'>List</a> page</li>
        <li>You will be taken to List page and then you will see section with title <b>"My Marker"</b></li>
        <li>Click on the <b>Add Marker</b> button, and you will be redirected to Add Marker page</li>
        <li>Fill the form, start from mandatory field like <b>Pin Name</b>, <b>Pin Category</b>, <b>Pin Lat</b>, and <b>Pin Long</b></li>
        <li>For the coordinate, you can <b>input it manually</b> or <b>select on the maps board</b></li>
        <li>And you can also fill the optional form like <b>Pin Desc</b>, <b>Pin Address</b>, <b>Pin Call</b>, <b>Pin Person</b>, <b>Pin Email</b>, and check the box of <b>Is Favorite</b> if the location is one of your favorite</li>
        <li>To submit, just pressed the <b class="text-success">Save Marker</b> button or if you want auto set direction in Google Maps, press the <b class="text-success">Save Marker & Set Direction</b></li>
    </ol>
    <h3>Find Pins or Markers by Name</h3>
    <p>Using the search bar, you can find one or many your pins by search it's name</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/ListController'>List</a> page</li>
        <li>You will be taken to List page and then you will see section with title <b>"My Marker"</b></li>
        <li>Fill the <b>Filter By Name</b> and press the <b>Search</b> button</li>
        <li>The list <b class="text-success">will be refresh</b> and show pins based on your search</li>
    </ol>
    <h3>Reset the Name Filter</h3>
    <p>After search some pin and you want back to show all available pins or marker, you can use reset filter</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/ListController'>List</a> page</li>
        <li>You will be taken to List page and then you will see section with title <b>"My Marker"</b></li>
        <li>Click on the button with reload icon</li>
        <li>The maps <b class="text-success">will be refresh</b> and show all pins</li>
    </ol>
    <h3>Export All Pins</h3>
    <p>Export all of your pins or markers in PDF format and sended it to your Telegram</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/ListController'>List</a> page</li>
        <li>You will be taken to List page and then you will see section with title <b>"My Marker"</b></li>
        <li>Click on the <b>Print</b> button</li>
        <li>System will be <b class="text-success">redirect you to preview</b> the document. And at the same time <b class="text-success">sending the file</b> to your <b>Telegram</b> chat</li>
    </ol>
    <h3>Show Deleted Pins or Markers</h3>
    <p>Showing all deleted pins or markers in last 30 days. In this page you can either recover or permentaly deleted the pin before the expired date</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/ListController'>List</a> page</li>
        <li>You will be taken to List page and then you will see section with title <b>"My Marker"</b></li>
        <li>Click on the <b>Trash</b> button</li>
        <li>System will be <b class="text-success">redirect</b> you to <a class='menu-btn-help'>Trash</a> page and you will see the list of deleted pins</li>
    </ol>
    <h3>Show Pins or Markers in Category Grouping View</h3>
    <p>Showing all pins or markers per category grouping</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/ListController'>List</a> page</li>
        <li>You will be taken to List page and then you will see section with title <b>"My Marker"</b></li>
        <li>Click on the <b>Catalog</b> button</li>
        <li>System will be <b class="text-success">refresh</b> and showing the pins or markers by its category</li>
        <li>If you want to back to see all pins view without grouping. Just press again the same button, but now the name of the button is <b>List</b></li>
    </ol>
    <h3>Show My List Pin Category</h3>
    <p>All category that show in this list can be used for creating a new pin</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/ListController'>List</a> page</li>
        <li>You will be taken to List page and then you will see section with title <b>"My Marker"</b></li>
        <li>Click on the <b>Set Category</b> button, and a popup that show list of category will appear</li>
    </ol>
    <h3>Edit the Category Name</h3>
    <p>The category name must be unique</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/ListController'>List</a> page</li>
        <li>You will be taken to List page and then you will see section with title <b>"My Marker"</b></li>
        <li>Click on the <b>Set Category</b> button, and a popup that show list of category will appear</li>
        <li>Choose and type in <b>Category Name</b> column's field. When you have finished editing, just <b class="text-success">escape the typing</b> mode</li>
    </ol>
    <h3>Edit the Category Color</h3>
    <p>This color will be used in dot color for the marker in maps</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/ListController'>List</a> page</li>
        <li>You will be taken to List page and then you will see section with title <b>"My Marker"</b></li>
        <li>Click on the <b>Set Category</b> button, and a popup that show list of category will appear</li>
        <li>Choose and select new color for the category in <b>Color</b> column's field. When you have finish selecting the color, just <b class="text-success">escape the selection</b> mode</li>
    </ol>
    <h3>Delete Category</h3>
    <p>This will delete the category and attached category in every pins or markers</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/ListController'>List</a> page</li>
        <li>You will be taken to List page and then you will see section with title <b>"My Marker"</b></li>
        <li>Click on the <b>Set Category</b> button, and a popup that show list of category will appear</li>
        <li>Choose and select the category you want to delete, click the button with <b>icon trash</b> to delete. After that a new popup confirmation will appear</li>
        <li>If the category has never been used before, in the confirmation just press the <b class="text-success">Yes, delete it!</b></li>
        <li>If the category has been used before, in the confirmation, you must choose where you want to migrate the pins or markers associated with that category. After that, you can press <b class="text-success">Continue delete</b></li>
    </ol>
</div>

<div class="section-help">
    <h2 class="fw-bold">Menu : History</h2>
    <h3>Export All Visit History</h3>
    <p>Export all of your visit historu in PDF format and sended it to your Telegram</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <a class='menu-btn-help' href='/HistoryController'>History</a> page</li>
        <li>You will be taken to History page and then you will see section with title <b>"My Visit"</b></li>
        <li>Click on the <b>Print</b> button</li>
        <li>System will be <b class="text-success">redirect you to preview</b> the document. And at the same time <b class="text-success">sending the file</b> to your <b>Telegram</b> chat</li>
    </ol>
</div>

<div class="section-help">
    <h2 class="fw-bold">Menu : Track</h2>
    ...
</div>

<div class="section-help">
    <h2 class="fw-bold">Menu : My Profile</h2>
    ...
</div>

<div class="section-help">
    <h2 class="fw-bold">Menu : Feedback</h2>
    <h3>Sending Your Feedback</h3>
    <p>In this section, you can share your honest thoughts about our app. Feel free to offer criticism or suggestions to help us improve. Your feedback will remain anonymous, so don't hesitate to be open and honest about your experience.</p>
    <b>The Steps : </b>
    <ol>
        <li>In the main menu, press the <b>Setting</b> dropdown and click the <a class='menu-btn-help' href='/FeedbackController'>Feedback</a> page</li>
        <li>You will be taken to Feedback page and then you will see section with title <b>"Give Us Feedback"</b></li>
        <li>In this form, fill the <b>Message</b> and choose the <b>Rate</b> for Us</li>
        <li>Press the <b class="text-success">Send Feedback</b> button to send your feedback</li>
    </ol>
</div>