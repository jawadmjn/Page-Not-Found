<h1>Fixed broken links and display user friendly error page if any exception occurs</h1>
Clicking on a broken link is a pain, but a witty and well-designed error page at least sweetens the pill. Here is Laravel configuration for tracking broken links, getting information what and from where user request the link.

## Installation Laravel:
* Setup your app/config/mail.php
* Setup your app/start/global.php file according to app/global.php file in this example.
Most important is this:
```
App::error(function(Exception $exception, $code)
{
  $headline = "Sorry, That Should Not Have Happened!";
  switch ($code)
  {
    case 400: // Bad Request
        return App::make("ErrorController")->getError($exception, $code, $headline);
      break;
    case 401: // Unauthorized
        return App::make("ErrorController")->getError($exception, $code, $headline);
      break;
    case 403: // Forbidden
        return App::make("ErrorController")->getError($exception, $code, $headline);
      break;
    case 404: // Not Found
      $headline="Sorry, The Page You Are Looking For Does Not Exist.";
        return App::make("ErrorController")->getError($exception, $code, $headline);
      break;
    case 500: // Internal Server Error
        return App::make("ErrorController")->getError($exception, $code, $headline);
      break;
    case 503: // Service Unavailable
        return App::make("ErrorController")->getError($exception, $code, $headline);
      break;
    default:
        return App::make("ErrorController")->getError($exception, $code, $headline);
      break;
  }
});

```
* Copy app/Error404.php and put in app/models (Error404.php is the class which is getting information).
* Copy app/ErrorController.php and put in app/controllers. (ErrorController.php detect machine environment, sending e-mail to web-admin and showing 404error page to end-user)
* Page to display end user should be in view folder and in this example you can find one app/views/error.html