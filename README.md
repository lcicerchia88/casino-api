# WordPress Developer Coding Exercise - CASINO API

The goal of this exercise is for you to create a Wordpress plugin which will fetch an array of reviews from an external REST API and display them in a nice list to the user.

Requirements:

1. The review list should be responsive and appear without issues in both mobile and desktop devices.
2. The JSON file contains multiple arrays of review objects. You need to display only those under the key 575.
3. Each review on the list should be populated from the data of a single object in the JSON array.
4. Review objects in the JSON array may appear out of order. The position that each review should appear on the list for the end user should be according to the order key in the JSON object, and not based on the position of the object in the array.
5. The word “Review” under the logo image and the logo image should link to /brand_id, where brand_id is the value found under the brand_id key in the corresponding JSON object.
6. The “PLAY NOW” button should link to the value found under the play_url key in the corresponding JSON object.
7. The plugin should be installed in a publicly hosted & accessible Wordpress installation.
8. All code needs to be in a code versioned repository of your choice, with detailed commits.
9. All code needs to be well commented.
10. Commit frequently instead of once in the end.

11. An endpoint to return the movies, ordered by creation.

# What was done

1. Connection to External REST API was made via cURL. Code was hardcoded at the end to use the data.json file provided by the company

2. JSON file was filtered: After testing that the data was being displayed in the front-end of the website, I proceeded to filter the data, as per requirements (Key: 575).

3. JSON file was orderded: Before printing the JSON file as a table in the front-end of the website, I ordered it, as per requirements of the excercise (Order by key: position)

4. Once the JSON file was ordered, I showed it on the website using a shortcode.

# What did I use

I used the functions wp_enqueue_style and wp_enqueue_script to enqueue bootstrap and a .css file for the plugin. This allowed me to style the table in the proper way.

I created a separate functions.php file inside the plugin folder to call all the functios needed for the plugin to work in the proper way.

I created an assets/css folder in where I added the css file of the plugin

# Possible improvements

- Add parameters to the shortcode, for example number of reviews to show. Ex: [show_reviews_casino reviews="3"]
- Build functionality for the scenario of receiving an empty json or that the list that we are showing in the front-end has no reviews. What would the table show?
- Add pagination on json responses
