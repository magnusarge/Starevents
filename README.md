Task overview
PHP test work
Billity

There will be an imaginary event for star observers held in different Estonian cities – in
Tallinn, Tartu, Narva, Pärnu, Jõhvi, Jõgeva, Põlva and Valga. People will gather to observe and learn about the stars.
For the next 5 upcoming days, there will be 4 sessions available for registrations for each city. Sessions will be 2,5h long and will start at 20:00, 23:00, 02:00, and 05:00. There is no limit for registrations, but an event takes place only in case of a clear sky.
We will build an API for registrations.
Task specifics
1. Create an API endpoint for listing available clear sky events. City coupled with the date and time.
To retrieve information about the weather in a given place and time please use the service of openweathermap.org, “5 day/3 hour” forecast in specific (https://openweathermap.org/forecast5). Our free API key can be used – xxxxxxxxxxxxxxxxxxxxx. Please notice that max 60 calls per minute are allowed. If you exceed that then please register your own key.
2. Create an API endpoint for registrations to an event
3. Once submitted, insert the data into a MySQL or PostgreSQL database.
4. Make an API endpoint for listing all the registrations.
           
Task limitations
Do not build the application on top of any framework – we want you to solve it from the beginning to the end by yourself.
Task tip
Build and present your work as if it would be a real project – pay attention to human understandable responses, security, and readability of your code.
Submitting the task

1. Send us the public link to the three endpoints (if you have made it public).
2. Compress all the PHP files + SQL dump (of table’s definition) into a zip file and send it to indrek@billity.no or use GitHub/GitLab if you prefer.
3. Include your textual explanations (if needed).
If you have any questions, feel free to send me an email at indrek@billity.no Good luck!
