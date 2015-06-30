<?php

class ErrorController extends Controller
{
    public function getError($exception, $code, $headline)
    {
        // for local testing and getting error emails just make not sign remove, i.e: if(Config::get('app.debug'))
        if(!Config::get('app.debug'))
          {
            // From where user is coming
            $previous_url = URL::previous();
            //The above one is for Laravel, you can use this also in PHP projects $previous_url = $_SERVER['HTTP_REFERER'];

            //User IP address
            $ip = Request::getClientIp();
            //The above one is for Laravel, you can use this also in PHP projects $ip = $_SERVER['REMOTE_ADDR'];

            // Get requested URL, Date and Time.
            $url = Request::url();
            $now = new DateTime();
            $errorDate = date_format($now, 'l, d-M-Y => H:i:s T');

            // Getting browser Info from models/Error404 class.
            $browserInfo = Error404::getBrowser();
            $browserName = $browserInfo['name'];
            $browserVersion = $browserInfo['version'];
            $platform = $browserInfo['platform'];

            // Getting Location Info passing the ip address to the function in models/Error404 class.
            $ipInfo = Error404::getIp($ip);
            $country = $ipInfo['country'];
            $state = $ipInfo['state'];
            $town = $ipInfo['town'];

            //generate more Info in mail-subject, for example for multiple web sites you can change MY Web to the name of your web
            $subject = 'My Web Error : ';

            // Log the info if you want to...
            Log::info("###### My Web ERROR ######");
            Log::info("IP: $ip");
            Log::info("URL: $url");
            Log::info("Date and Time: $errorDate");
            Log::info("Browser Name/Version: $browserName / $browserVersion");
            Log::info("Visitor's Country, State and City: $country, $state, $town");
            Log::info("Visitor coming from: $previous_url");
            Log::info("###### !ERROR ######\n");

            //Creating the final message to send via E-mail to web-admin
            $message = "###### ERROR ###### <br/>
            Error Code: <b>$code</b> <br/>
            IP: $ip <br/>
            URL: $url <br/>
            Date and Time: $errorDate <br/>
            Browser Name/Version: $browserName / $browserVersion <br/>
            Operating System: $platform <br/>
            Visitor's Country, State and City: $country, $state, $town  .<br/>
            Visitor coming from: $previous_url <br/>";

            if($code != 404) {
                $message .= "Exeption:<br/>$exception<br/>";
                $subject .= " php_error : $code";
            }else{
                $subject .= ' Route missing';
            }
            $message .= "###### !ERROR ######";

            // Sending Error Report via E-mail -> Please edit this and enter your email receiving and sending e-mail address
            try {
              Mail::send('emails.error_email', array('var' => $message), function($message) use ($subject)
                {
                    $message
                    ->to('reciver@yourdomain.com')
                    ->from('sender@yourdomain.com')
                    ->subject("$subject");
                });
            }
            catch (Exception $e) {
            Log::info("$e\n");
            }

            // Finally after reciving error email and loging the information show the HTML page to user you created for end-user.
            return View::make('view/error')->withCode($code)->withHeadline($headline);

          } //Closing of if(app.debug)
    }
}